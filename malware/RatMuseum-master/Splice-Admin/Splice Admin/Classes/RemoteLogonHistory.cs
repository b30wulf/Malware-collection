using System;
using System.Collections.Generic;
using System.Diagnostics.Eventing.Reader;
using System.Text.RegularExpressions;

namespace Splice_Admin.Classes
{
    public class RemoteLogonHistory
    {
        public static TaskResult Result { get; set; }

        public string LogonDomainAndName { get { return this.LogonDomain.Length > 0 ? $@"{this.LogonDomain}\{this.LogonName}" : this.LogonName; } }
        public string LogonTypeAndAction
        {
            get
            {
                string logonTypeAndAction;
                if (this.LogonType == "2")
                    logonTypeAndAction = "Logon (Console)";
                else if (this.LogonType == "10")
                    logonTypeAndAction = "Logon (RDP)";
                else
                    logonTypeAndAction = this.LogonType;

                if (!string.IsNullOrEmpty(this.LogonAction))
                    logonTypeAndAction += " " + this.LogonAction;

                return logonTypeAndAction;
            }
        }
        public string LogonName { get; set; }
        public string LogonDomain { get; set; }
        public string LogonType { get; set; }
        public string LogonAction { get; set; }
        public string IpAddress { get; set; }
        public DateTime LogonTime { get; set; }


        public static List<RemoteLogonHistory> GetLogonHistory()
        {
            var logonHistory = new List<RemoteLogonHistory>();
            Result = new TaskResult();

            const int logonEventId = 4624;
            const int logoffEventIdA = 4634;
            const int logoffEventIdB = 4647;
            const int landeskRemoteControlEventId = 2;

            string queryString =
                "<QueryList><Query Id='1'>" +
                "<Select Path='Security'>" +
                "*[System[Provider[@Name='Microsoft-Windows-Security-Auditing'] and " +
                "(EventID=" + logonEventId + ")]] and " +
                "*[EventData[Data[@Name='LogonType'] and (Data='2' or Data='10')]] and " +
                "*[EventData[Data[@Name='LogonGuid'] != '{00000000-0000-0000-0000-000000000000}']] and " +
                "*[EventData[Data[@Name='LogonProcessName'] != 'seclogo']]" +
                "</Select>" +
                "<Select Path='Security'>" +
                "*[System[Provider[@Name='Microsoft-Windows-Security-Auditing'] and " +
                "(EventID=" + logonEventId + ")]] and " +
                "*[EventData[Data[@Name='LogonType'] and (Data='2' or Data='10')]] and " +
                "*[EventData[Data[@Name='TargetDomainName'] = '" + RemoteLogonSession.ComputerName.ToUpper().Trim() + "']]" +
                "</Select>" +
                "<Select Path='Security'>" +
                //"*[System[Provider[@Name='Microsoft-Windows-Security-Auditing'] and " +
                //"(EventID=" + logoffEventIdA + ")]] and " +
                //"*[EventData[Data[@Name='LogonType'] and (Data='2' or Data='10')]] or " +
                "*[System[Provider[@Name='Microsoft-Windows-Security-Auditing'] and " +
                "(EventID=" + logoffEventIdB + ")]]" +
                "</Select>" +
                "<Select Path='Application'>" +
                "*[System[Provider[@Name='LANDESK Remote Control Service'] and (EventID=" + landeskRemoteControlEventId + ")]]" +
                "</Select>" +
                "</Query></QueryList>";

            try
            {
                var eventLogSession = new EventLogSession(RemoteLogonSession.ComputerName);
                var eventLogQuery = new EventLogQuery("Security", PathType.LogName, queryString);
                eventLogQuery.ReverseDirection = true;
                eventLogQuery.Session = eventLogSession;

                using (
                        GlobalVar.UseAlternateCredentials
                        ? UserImpersonation.Impersonate(GlobalVar.AlternateUsername, GlobalVar.AlternateDomain, GlobalVar.AlternatePassword)
                        : null)
                using (var eventLogReader = new EventLogReader(eventLogQuery))
                {
                    for (EventRecord eventLogRecord = eventLogReader.ReadEvent(); null != eventLogRecord; eventLogRecord = eventLogReader.ReadEvent())
                    {
                        string regexString;

                        switch (eventLogRecord.Id)
                        {
                            case (logonEventId):
                                regexString = @"An account was successfully logged on.*Logon Type:\s+(?<logonType>.*?)\r" +
                                    @".*\tAccount Name:\s+(?<accountName>.*?)\r" +
                                    @".*\tAccount Domain:\s+(?<accountDomain>.*?)\r" +
                                    @".*Network Information:.*Source Network Address:\s+(?<sourceIpAddress>.*?)\r";
                                break;
                            case (landeskRemoteControlEventId):
                                regexString = @"^Remote control action: (?<controlAction>\w+?) Remote Control  Initiated from (?<sourceHostname>.*?) by user " +
                                    @"(?<accountName>.*?), Security Type";
                                break;
                            case (logoffEventIdA):
                                regexString = @"An account was logged off" +
                                    @".*Subject:.*Account Name:\s+(?<accountName>.*?)\r" +
                                    @".*Account Domain:\s+(?<accountDomain>.*?)\r" +
                                    @".*Logon Type:\s+(?<logonType>.*?)\r";
                                break;
                            case (logoffEventIdB):
                                regexString = @"User initiated logoff" +
                                    @".*Subject:.*Account Name:\s+(?<accountName>.*?)\r" +
                                    @".*Account Domain:\s+(?<accountDomain>.*?)\r";
                                break;
                            default:
                                regexString = string.Empty;
                                break;
                        }
                        var match = Regex.Match(eventLogRecord.FormatDescription(), regexString, RegexOptions.Singleline);

                        if (match.Success)
                        {
                            switch (eventLogRecord.Id)
                            {
                                case (logonEventId):
                                    logonHistory.Add(new RemoteLogonHistory
                                    {
                                        LogonTime = eventLogRecord.TimeCreated.Value,
                                        LogonDomain = match.Groups["accountDomain"].Value,
                                        LogonName = match.Groups["accountName"].Value,
                                        LogonType = match.Groups["logonType"].Value,
                                        IpAddress = match.Groups["sourceIpAddress"].Value
                                    });
                                    break;
                                case (landeskRemoteControlEventId):
                                    logonHistory.Add(new RemoteLogonHistory
                                    {
                                        LogonTime = eventLogRecord.TimeCreated.Value,
                                        LogonName = match.Groups["accountName"].Value,
                                        LogonDomain = string.Empty,
                                        LogonType = "LANDesk",
                                        LogonAction = match.Groups["controlAction"].Value,
                                        IpAddress = match.Groups["sourceHostname"].Value
                                    });
                                    break;
                                case (logoffEventIdA):
                                    logonHistory.Add(new RemoteLogonHistory
                                    {
                                        LogonTime = eventLogRecord.TimeCreated.Value,
                                        LogonDomain = match.Groups["accountDomain"].Value,
                                        LogonName = match.Groups["accountName"].Value,
                                        LogonType = "Logoff"
                                    });
                                    break;
                                case (logoffEventIdB):
                                    logonHistory.Add(new RemoteLogonHistory
                                    {
                                        LogonTime = eventLogRecord.TimeCreated.Value,
                                        LogonDomain = match.Groups["accountDomain"].Value,
                                        LogonName = match.Groups["accountName"].Value,
                                        LogonType = "Logoff"
                                    });
                                    break;
                            }
                        }
                    }

                    Result.DidTaskSucceed = true;
                }
            }
            catch (UnauthorizedAccessException)
            {
                Result.DidTaskSucceed = false;
                Result.MessageBody = "This feature is currently only supported on Windows Vista and Server 2008 or higher.";
            }
            catch
            {
                Result.DidTaskSucceed = false;
            }

            return logonHistory;
        }
    }
}
