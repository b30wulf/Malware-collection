using System.Collections.Generic;

namespace Slave.Commands.KeyLogger
{
    internal static class KeyboardKeys
    {
        /// <summary>
        /// Virtual Keys
        /// </summary>
        public static Dictionary<int, string> VKeys = new Dictionary<int, string>()
        {
            { 0x01, "LBUTTON" },     // Left mouse button
            { 0x02, "RBUTTON" },     // Right mouse button
            { 0x03, "CANCEL" },      // Control-break processing
            { 0x04, "MBUTTON" },     // Middle mouse button (three-button mouse)
            { 0x05, "XBUTTON1" },    // Windows 2000/XP: X1 mouse button
            { 0x06, "XBUTTON2" },    // Windows 2000/XP: X2 mouse button
            { 0x08, "BACK" },        // BACKSPACE key
            { 0x09, "TAB" },         // TAB key
            { 0x0C, "CLEAR" },       // CLEAR key
            { 0x0D, "RETURN" },      // ENTER key
            { 0x10, "SHIFT" },       // SHIFT key
            { 0x11, "CONTROL" },     // CTRL key
            { 0x12, "MENU" },        // ALT key
            { 0x13, "PAUSE" },       // PAUSE key
            { 0x15, "JpChar" },
            { 0x14, "CAPITAL" },     // CAPS LOCK key
            { 0x17, "JUNJA" },       // IME Junja mode
            { 0x18, "FINAL" },       // IME final mode
            { 0x19, "JpChar2" },
            { 0x1B, "ESCAPE" },      // ESC key
            { 0x1C, "CONVERT" },     // IME convert
            { 0x1D, "NONCONVERT" },  // IME nonconvert
            { 0x1E, "ACCEPT" },      // IME accept
            { 0x1F, "MODECHANGE" },  // IME mode change request
            { 0x20, "SPACE" },       // SPACEBAR
            { 0x21, "PRIOR" },       // PAGE UP key
            { 0x22, "NEXT" },        // PAGE DOWN key
            { 0x23, "END" },         // END key
            { 0x24, "HOME" },        // HOME key
            { 0x25, "LEFT" },        // LEFT ARROW key
            { 0x26, "UP" },          // UP ARROW key
            { 0x27, "RIGHT" },       // RIGHT ARROW key
            { 0x28, "DOWN" },        // DOWN ARROW key
            { 0x29, "SELECT" },      // SELECT key
            { 0x2A, "PRINT" },       // PRINT key
            { 0x2B, "EXECUTE" },     // EXECUTE key
            { 0x2C, "SNAPSHOT" },    // PRINT SCREEN key
            { 0x2D, "INSERT" },      // INS key
            { 0x2E, "DELETE" },      // DEL key
            { 0x2F, "HELP" },        // HELP key
            { 0x30, ":SPC_0" },       // 0 key
            { 0x31, ":SPC_1" },       // 1 key
            { 0x32, ":SPC_2" },       // 2 key
            { 0x33, ":SPC_3" },       // 3 key
            { 0x34, ":SPC_4" },       // 4 key
            { 0x35, ":SPC_5" },       // 5 key
            { 0x36, ":SPC_6" },       // 6 key
            { 0x37, ":SPC_7" },       // 7 key
            { 0x38, ":SPC_8" },       // 8 key
            { 0x39, ":SPC_9" },       // 9 key
            { 0x41, "A" },       // A key
            { 0x42, "B" },       // B key
            { 0x43, "C" },       // C key
            { 0x44, "D" },       // D key
            { 0x45, "E" },       // E key
            { 0x46, "F" },       // F key
            { 0x47, "G" },       // G key
            { 0x48, "H" },       // H key
            { 0x49, "I" },       // I key
            { 0x4A, "J" },       // J key
            { 0x4B, "K" },       // K key
            { 0x4C, "L" },       // L key
            { 0x4D, "M" },       // M key
            { 0x4E, "N" },       // N key
            { 0x4F, "O" },       // O key
            { 0x50, "P" },       // P key
            { 0x51, "Q" },       // Q key
            { 0x52, "R" },       // R key
            { 0x53, "S" },       // S key
            { 0x54, "T" },       // T key
            { 0x55, "U" },       // U key
            { 0x56, "V" },       // V key
            { 0x57, "W" },       // W key
            { 0x58, "X" },       // X key
            { 0x59, "Y" },       // Y key
            { 0x5A, "Z" },       // Z key
            { 0x5B, "LWIN" },        // Left Windows key (Microsoft Natural keyboard)
            { 0x5C, "RWIN" },        // Right Windows key (Natural keyboard)
            { 0x5D, "APPS" },        // Applications key (Natural keyboard)
            { 0x5F, "SLEEP" },       // Computer Sleep key
            { 0x60, "0" },     // Numeric keypad 0 key
            { 0x61, "1" },     // Numeric keypad 1 key
            { 0x62, "2" },     // Numeric keypad 2 key
            { 0x63, "3" },     // Numeric keypad 3 key
            { 0x64, "4" },     // Numeric keypad 4 key
            { 0x65, "5" },     // Numeric keypad 5 key
            { 0x66, "6" },     // Numeric keypad 6 key
            { 0x67, "7" },     // Numeric keypad 7 key
            { 0x68, "8" },     // Numeric keypad 8 key
            { 0x69, "9" },     // Numeric keypad 9 key
            { 0x6A, "MULTIPLY" },    // Multiply key
            { 0x6B, "ADD" },         // Add key
            { 0x6C, "SEPARATOR" },   // Separator key
            { 0x6D, "SUBTRACT" },    // Subtract key
            { 0x6E, "DECIMAL" },     // Decimal key
            { 0x6F, "DIVIDE" },      // Divide key
            { 0x70, "F1" },          // F1 key
            { 0x71, "F2" },          // F2 key
            { 0x72, "F3" },          // F3 key
            { 0x73, "F4" },          // F4 key
            { 0x74, "F5" },          // F5 key
            { 0x75, "F6" },          // F6 key
            { 0x76, "F7" },          // F7 key
            { 0x77, "F8" },          // F8 key
            { 0x78, "F9" },          // F9 key
            { 0x79, "F10" },         // F10 key
            { 0x7A, "F11" },         // F11 key
            { 0x7B, "F12" },         // F12 key
            { 0x7C, "F13" },         // F13 key
            { 0x7D, "F14" },         // F14 key
            { 0x7E, "F15" },         // F15 key
            { 0x7F, "F16" },         // F16 key
            { 0x80, "F17" },         // F17 key
            { 0x81, "F18" },         // F18 key
            { 0x82, "F19" },         // F19 key
            { 0x83, "F20" },         // F20 key
            { 0x84, "F21" },         // F21 key
            { 0x85, "F22" },         // F22 key, (PPC only) Key used to lock device.
            { 0x86, "F23" },         // F23 key
            { 0x87, "F24" },         // F24 key
            { 0x90, "NUMLOCK" },     // NUM LOCK key
            { 0x91, "SCROLL" },      // SCROLL LOCK key
            { 0xA0, "LSHIFT" },      // Left SHIFT key
            { 0xA1, "RSHIFT" },      // Right SHIFT key
            { 0xA2, "LCONTROL" },    // Left CONTROL key
            { 0xA3, "RCONTROL" },    // Right CONTROL key
            { 0xA4, "LMENU" },       // Left MENU key
            { 0xA5, "ALTGR" },       // Right MENU key
            { 0xA6, "BROWSER_BACK" },    // Windows 2000/XP: Browser Back key
            { 0xA7, "BROWSER_FORWARD" }, // Windows 2000/XP: Browser Forward key
            { 0xA8, "BROWSER_REFRESH" }, // Windows 2000/XP: Browser Refresh key
            { 0xA9, "BROWSER_STOP" },    // Windows 2000/XP: Browser Stop key
            { 0xAA, "BROWSER_SEARCH" },  // Windows 2000/XP: Browser Search key
            { 0xAB, "BROWSER_FAVORITES" },  // Windows 2000/XP: Browser Favorites key
            { 0xAC, "BROWSER_HOME" },    // Windows 2000/XP: Browser Start and Home key
            { 0xAD, "VOLUME_MUTE" },     // Windows 2000/XP: Volume Mute key
            { 0xAE, "VOLUME_DOWN" },     // Windows 2000/XP: Volume Down key
            { 0xAF, "VOLUME_UP" },  // Windows 2000/XP: Volume Up key
            { 0xB0, "MEDIA_NEXT_TRACK" },// Windows 2000/XP: Next Track key
            { 0xB1, "MEDIA_PREV_TRACK" },// Windows 2000/XP: Previous Track key
            { 0xB2, "MEDIA_STOP" }, // Windows 2000/XP: Stop Media key
            { 0xB3, "MEDIA_PLAY_PAUSE" },// Windows 2000/XP: Play/Pause Media key
            { 0xB4, "LAUNCH_MAIL" },     // Windows 2000/XP: Start Mail key
            { 0xB5, "LAUNCH_MEDIA_SELECT" },  // Windows 2000/XP: Select Media key
            { 0xB6, "LAUNCH_APP1" },     // Windows 2000/XP: Start Application 1 key
            { 0xB7, "LAUNCH_APP2" },     // Windows 2000/XP: Start Application 2 key
            { 0xBA, ":SPC_14" },       // Used for miscellaneous characters; it can vary by keyboard.
            { 0xBB, ":SPC_12" },    // Windows 2000/XP: For any country/region, the '+' key
            { 0xBC, ":SPC_17" },   // Windows 2000/XP: For any country/region, the ',' key
            { 0xBD, "OEM_MINUS" },   // Windows 2000/XP: For any country/region, the '-' key
            { 0xBE, ":SPC_18" },  // Windows 2000/XP: For any country/region, the '.' key
            { 0xBF, ":SPC_19" },       // Used for miscellaneous characters; it can vary by keyboard.
            { 0xC0, ":SPC_15" },       // Used for miscellaneous characters; it can vary by keyboard.
            { 0xDB, ":SPC_11" },       // Used for miscellaneous characters; it can vary by keyboard.
            { 0xDC, ":SPC_16" },       // Used for miscellaneous characters; it can vary by keyboard.
            { 0xDD, ":SPC_13" },       // Used for miscellaneous characters; it can vary by keyboard.
            { 0xDE, ":SPC_10" },       // Used for miscellaneous characters; it can vary by keyboard.
            { 0xDF, ":SPC_20" },       // Used for miscellaneous characters; it can vary by keyboard.
            { 0xE2, ":SPC_21" },     // Windows 2000/XP: Either the angle bracket key or the backslash key on the RT 102-key keyboard
            { 0xE5, "PROCESSKEY" },  // Windows 95/98/Me, Windows NT 4.0, Windows 2000/XP: IME PROCESS key
            { 0xE7, "PACKET" },      // Windows 2000/XP: Used to pass Unicode characters as if they were keystrokes. The VK_PACKET key is the low word of a 32-bit Virtual key value used for non-keyboard input methods. For more information, see Remark in KEYBDINPUT, SendInput, WM_KEYDOWN, and WM_KEYUP
            { 0xF6, "ATTN" },        // Attn key
            { 0xF7, "CRSEL" },       // CrSel key
            { 0xF8, "EXSEL" },       // ExSel key
            { 0xF9, "EREOF" },       // Erase EOF key
            { 0xFA, "PLAY" },        // Play key
            { 0xFB, "ZOOM" },        // Zoom key
            { 0xFC, "NONAME" },      // Reserved
            { 0xFD, "PA1" },         // PA1 key
            { 0xFE, "OEM_CLEAR" }    // Clear key
        };

        /// <summary>
        /// SHIFT equivalent of keys, no letters
        /// AZERTY specific
        /// </summary>
        public static Dictionary<string, string> SpecialShiftKeys = new Dictionary<string, string>()
        {
            { ":SPC_0", "0" },
            { ":SPC_1", "1" },
            { ":SPC_2", "2" },
            { ":SPC_3", "3" },
            { ":SPC_4", "4" },
            { ":SPC_5", "5" },
            { ":SPC_6", "6" },
            { ":SPC_7", "7" },
            { ":SPC_8", "8" },
            { ":SPC_9", "9" },
            { ":SPC_11", "°" },
            { ":SPC_12", "+" },
            { ":SPC_13", "¨" },
            { ":SPC_14", "£" },
            { ":SPC_15", "%" },
            { ":SPC_16", "µ" },
            { ":SPC_17", "?" },
            { ":SPC_18", "." },
            { ":SPC_19", "/" },
            { ":SPC_20", "§" },
            { ":SPC_21", ">" }
        };

        /// <summary>
        /// ALT equivalent of keys
        /// AZERTY specific
        /// </summary>
        public static Dictionary<string, string> SpecialAltGrKeys = new Dictionary<string, string>()
        {
            { ":SPC_0", "@" },
            { ":SPC_2", "~" },
            { ":SPC_3", "#" },
            { ":SPC_4", "{" },
            { ":SPC_5", "[" },
            { ":SPC_6", "|" },
            { ":SPC_7", "`" },
            { ":SPC_8", "\\" },
            { ":SPC_9", "^" },
            { ":SPC_11", "]" },
            { ":SPC_12", "}" },
            { ":SPC_14", "¤" }
        };

        /// <summary>
        /// Equivalent of special keys that are of interest to print
        /// AZERTY specific
        /// </summary>
        public static Dictionary<string, string> SpecialKeys = new Dictionary<string, string>()
        {
            { ":SPC_0", "à" },
            { ":SPC_1", "&" },
            { ":SPC_2", "é" },
            { ":SPC_3", "\"" },
            { ":SPC_4", "'" },
            { ":SPC_5", "(" },
            { ":SPC_6", "-" },
            { ":SPC_7", "è" },
            { ":SPC_8", "_" },
            { ":SPC_9", "ç" },
            { ":SPC_10", "²" },
            { ":SPC_11", ")" },
            { ":SPC_12", "=" },
            { ":SPC_13", "^" },
            { ":SPC_14", "$" },
            { ":SPC_15", "ù" },
            { ":SPC_16", "*" },
            { ":SPC_17", "," },
            { ":SPC_18", ";" },
            { ":SPC_19", ":" },
            { ":SPC_20", "!" },
            { ":SPC_21", "<" }
        };
    }
}
