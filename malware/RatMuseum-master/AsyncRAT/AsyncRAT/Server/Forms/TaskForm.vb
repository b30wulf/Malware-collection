Imports System.IO

Public Class TaskForm
    Public OK As Boolean = False
    Public _FILE As String = Nothing
    Public _CMD As String = Nothing
    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        If ComboBox1.Text.Length > 0 AndAlso Button1.Text <> "Select File" Then
            OK = True
            Me.Hide()
        End If
    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        Try

            Dim o As New OpenFileDialog
            With o
                .Filter = "(*.*)|*.*"
                .Title = "Select File"
            End With

            If o.ShowDialog = Windows.Forms.DialogResult.OK Then
                _FILE = o.FileName
                Button1.Text = Path.GetFileName(o.FileName)
            End If
        Catch ex As Exception
            Debug.WriteLine("Task Open File " + ex.Message)
        End Try
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
        If ComboBox1.SelectedIndex = 0 Then
            _CMD = "UPDATE"
        ElseIf ComboBox1.SelectedIndex = 1 Then
            _CMD = "DW"
        End If
    End Sub
End Class