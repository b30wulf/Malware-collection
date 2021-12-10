Public Class Loader
    Public o As OpenFileDialog
    Public isOK As Boolean = False
    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        Me.Close()
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
        If ComboBox1.SelectedIndex = 0 Then
            ComboBox2.Visible = True
            Label2.Visible = True
            Button1.Enabled = False
            Button2.Enabled = False
        Else
            ComboBox2.Visible = False
            Label2.Visible = False
            Button1.Enabled = True
            Button2.Enabled = True
        End If
    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        o = New OpenFileDialog
        If o.ShowDialog = DialogResult.OK Then
            Try
                Reflection.Assembly.LoadFile(o.FileName)
                isOK = True
                Label3.Text = IO.Path.GetFileName(o.FileName)
            Catch ex As Exception
                isOK = False
                MsgBox(IO.Path.GetFileName(o.FileName) + " is not a managed file.", MsgBoxStyle.Exclamation)
                Label3.Text = ""
            End Try
        End If
    End Sub
End Class