Imports System.IO
Imports System.Text
'Aeonhack
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