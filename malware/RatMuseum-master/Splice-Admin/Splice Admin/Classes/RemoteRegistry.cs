using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Splice_Admin.Classes
{
    class RemoteRegistry
    {
        public enum Hive : uint
        {
            HKEY_CLASSES_ROOT = 2147483648,
            HKEY_CURRENT_USER = 2147483649,
            HKEY_LOCAL_MACHINE = 2147483650,
            HKEY_USERS = 2147483651,
            HKEY_CURRENT_CONFIG = 2147483653
        }

        public enum ValueType
        {
            REG_SZ = 1,
            REG_EXPAND_SZ = 2,
            REG_BINARY = 3,
            REG_DWORD = 4,
            REG_MULTI_SZ = 7,
            REG_QWORD = 11
        }
    }
}
