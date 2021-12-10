Public Class Intro
    Public OK As Boolean = False
    Private Sub Form2_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Try
            Me.Text = "AsyncRAT " + Settings.VER
            If GTV("PORTS") <> Nothing Then
                TextBox1.Text = GTV("PORTS")
            Else
                TextBox1.Text = "6603,6604,6605,6606"
            End If
            If GTV("KEY") <> Nothing Then
                TextBox2.Text = GTV("KEY")
            Else
                TextBox2.Text = "<AsyncRAT123>"
            End If
        Catch ex As Exception
        End Try
    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        If Not String.IsNullOrWhiteSpace(TextBox1.Text) AndAlso Not String.IsNullOrWhiteSpace(TextBox2.Text) Then
            OK = True
            Me.Hide()
        End If
    End Sub
End Class