using System.Collections.Generic;

namespace Splice_Admin.Classes
{
    public class RemoteBulkQuery
    {
        public enum QueryType
        {
            File,
            InstalledApplication,
            LoggedOnUser,
            Registry,
            Process,
            Service
        }

        public string SearchPhrase { get; set; }
        public QueryType SearchType { get; set; }
        public List<string> TargetComputerList { get; set; }
    }

    public class QueryResult
    {
        public enum Type
        {
            Match = 0,
            NoMatch = 1,
            ProgressReport = 100
        }

        public string ComputerName { get; set; }
        public string ResultText { get; set; }
    }
}
