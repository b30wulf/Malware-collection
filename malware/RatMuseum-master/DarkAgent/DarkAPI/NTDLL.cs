using System;
using System.Collections.Generic;
using System.Text;
using System.Runtime.InteropServices;
using Microsoft.Win32.SafeHandles;
using System.IO;

namespace DarkAPI
{
    public class NTDLL
    {
        [DllImport("ntdll.dll", ExactSpelling = true, SetLastError = true)]
        public static extern int NtCreateFile(out SafeFileHandle handle, FileAccess access, OBJECT_ATTRIBUTES objectAttributes, out IntPtr ioStatus, ref long allocSize, uint fileAttributes, FileShare share, uint createDisposition, uint createOptions, IntPtr eaBuffer, uint eaLength);

        [DllImport("ntdll.dll")]
        public static extern int NtOpenDirectoryObject(out SafeFileHandle DirectoryHandle, uint DesiredAccess, ref OBJECT_ATTRIBUTES ObjectAttributes);

        [DllImport("ntdll.dll")]
        public static extern int NtOpenSymbolicLinkObject(out SafeFileHandle LinkHandle, uint DesiredAccess, ref OBJECT_ATTRIBUTES ObjectAttributes);

        [DllImport("ntdll.dll")]
        public static extern int NtQueryDirectoryObject(SafeFileHandle DirectoryHandle, IntPtr Buffer, int Length, bool ReturnSingleEntry, bool RestartScan, ref uint Context, out uint ReturnLength);

        [DllImport("ntdll.dll", SetLastError = true)]
        public static extern IntPtr NtQueryInformationFile(IntPtr fileHandle, ref IO_STATUS_BLOCK IoStatusBlock, IntPtr pInfoBlock, uint length, FILE_INFORMATION_CLASS fileInformation);

        [DllImport("ntdll.dll", SetLastError = true)]
        public static extern int NtQueryInformationProcess(IntPtr processHandle, int processInformationClass, IntPtr processInformation, uint processInformationLength, IntPtr returnLength);

        [DllImport("ntdll.dll")]
        public static extern int NtQuerySymbolicLinkObject(SafeFileHandle LinkHandle, ref UNICODE_STRING LinkTarget, out int ReturnedLength);

        [DllImport("ntdll.dll", SetLastError = true)]
        public static extern uint NtQuerySystemTime(out long SystemTime);

        [DllImport("ntdll.dll", SetLastError = true)]
        public static extern int NtQueryTimerResolution(out int MinimumResolution, out int MaximumResolution, out int ActualResolution);

        [DllImport("ntdll.dll")]
        public static extern void RtlFreeUnicodeString(ref UNICODE_STRING UnicodeString);

        [DllImport("ntdll.dll")]
        public static extern void RtlInitUnicodeString(ref UNICODE_STRING DestinationString, [MarshalAs(UnmanagedType.LPWStr)] string SourceString);

        [DllImport("ntdll.dll")]
        public static extern uint RtlNtStatusToDosError(int Status);

        [DllImport("ntdll.dll")]
        public static extern IntPtr ZwClose(IntPtr handle);

        [DllImport("ntdll.dll")]
        public static extern IntPtr ZwOpenSection(out IntPtr sectionHandle, uint desiredAccess, ref OBJECT_ATTRIBUTES attributes);

        public struct IO_STATUS_BLOCK
        {
            uint status;
            ulong information;
        }

        public enum FILE_INFORMATION_CLASS
        {
            FileDirectoryInformation = 1,
            FileFullDirectoryInformation = 2,
            FileBothDirectoryInformation = 3,
            FileBasicInformation = 4,
            FileStandardInformation = 5,
            FileInternalInformation = 6,
            FileEaInformation = 7,
            FileAccessInformation = 8,
            FileNameInformation = 9,
            FileRenameInformation = 10,
            FileLinkInformation = 11,
            FileNamesInformation = 12,
            FileDispositionInformation = 13,
            FilePositionInformation = 14,
            FileFullEaInformationv = 15,
            FileModeInformation = 16,
            FileAlignmentInformation = 17,
            FileAllInformation = 18,
            FileAllocationInformation = 19,
            FileEndOfFileInformation = 20,
            FileAlternateNameInformation = 21,
            FileStreamInformation = 22,
            FilePipeInformation = 23,
            FilePipeLocalInformation = 24,
            FilePipeRemoteInformation = 25,
            FileMailslotQueryInformation = 26,
            FileMailslotSetInformation = 27,
            FileCompressionInformation = 28,
            FileObjectIdInformation = 29,
            FileCompletionInformation = 30,
            FileMoveClusterInformation = 31,
            FileQuotaInformation = 32,
            FileReparsePointInformation = 33,
            FileNetworkOpenInformation = 34,
            FileAttributeTagInformation = 35,
            FileTrackingInformation = 36,
            FileIdBothDirectoryInformation = 37,
            FileIdFullDirectoryInformation = 38,
            FileValidDataLengthInformation = 39,
            FileShortNameInformation = 40,
            FileHardLinkInformation = 46
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct UNICODE_STRING : IDisposable
        {
            public ushort Length;
            public ushort MaximumLength;
            private IntPtr buffer;

            public UNICODE_STRING(string s)
            {
                Length = (ushort)(s.Length * 2);
                MaximumLength = (ushort)(Length + 2);
                buffer = Marshal.StringToHGlobalUni(s);
            }

            public void Dispose()
            {
                Marshal.FreeHGlobal(buffer);
                buffer = IntPtr.Zero;
            }

            public override string ToString()
            {
                return Marshal.PtrToStringUni(buffer);
            }
        }


        [StructLayout(LayoutKind.Sequential)]
        public struct OBJECT_ATTRIBUTES : IDisposable
        {
            public int Length;
            public IntPtr RootDirectory;
            private IntPtr objectName;
            public uint Attributes;
            public IntPtr SecurityDescriptor;
            public IntPtr SecurityQualityOfService;

            public OBJECT_ATTRIBUTES(string name, uint attrs)
            {
                Length = 0;
                RootDirectory = IntPtr.Zero;
                objectName = IntPtr.Zero;
                Attributes = attrs;
                SecurityDescriptor = IntPtr.Zero;
                SecurityQualityOfService = IntPtr.Zero;

                Length = Marshal.SizeOf(this);
                ObjectName = new UNICODE_STRING(name);
            }

            public UNICODE_STRING ObjectName
            {
                get
                {
                    return (UNICODE_STRING)Marshal.PtrToStructure(
                     objectName, typeof(UNICODE_STRING));
                }

                set
                {
                    bool fDeleteOld = objectName != IntPtr.Zero;
                    if (!fDeleteOld)
                        objectName = Marshal.AllocHGlobal(Marshal.SizeOf(value));
                    Marshal.StructureToPtr(value, objectName, fDeleteOld);
                }
            }

            public void Dispose()
            {
                if (objectName != IntPtr.Zero)
                {
                    Marshal.DestroyStructure(objectName, typeof(UNICODE_STRING));
                    Marshal.FreeHGlobal(objectName);
                    objectName = IntPtr.Zero;
                }
            }
        }
    }
}
