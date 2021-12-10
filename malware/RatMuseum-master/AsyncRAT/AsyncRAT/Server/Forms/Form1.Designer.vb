<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()>
Partial Class Form1
    Inherits System.Windows.Forms.Form

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()>
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        Try
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
        Finally
            MyBase.Dispose(disposing)
        End Try
    End Sub

    'Required by the Windows Form Designer
    Private components As System.ComponentModel.IContainer

    'NOTE: The following procedure is required by the Windows Form Designer
    'It can be modified using the Windows Form Designer.  
    'Do not modify it using the code editor.
    <System.Diagnostics.DebuggerStepThrough()>
    Private Sub InitializeComponent()
        Me.components = New System.ComponentModel.Container()
        Dim resources As System.ComponentModel.ComponentResourceManager = New System.ComponentModel.ComponentResourceManager(GetType(Form1))
        Me.LV1 = New System.Windows.Forms.ListView()
        Me._IP = CType(New System.Windows.Forms.ColumnHeader(), System.Windows.Forms.ColumnHeader)
        Me._COUNTRY = CType(New System.Windows.Forms.ColumnHeader(), System.Windows.Forms.ColumnHeader)
        Me._ID = CType(New System.Windows.Forms.ColumnHeader(), System.Windows.Forms.ColumnHeader)
        Me._Username = CType(New System.Windows.Forms.ColumnHeader(), System.Windows.Forms.ColumnHeader)
        Me._OS = CType(New System.Windows.Forms.ColumnHeader(), System.Windows.Forms.ColumnHeader)
        Me._VER = CType(New System.Windows.Forms.ColumnHeader(), System.Windows.Forms.ColumnHeader)
        Me._TASKS = CType(New System.Windows.Forms.ColumnHeader(), System.Windows.Forms.ColumnHeader)
        Me.ClientMenu = New System.Windows.Forms.ContextMenuStrip(Me.components)
        Me.DownloadAndExecuteToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.DROPANDEXECUTEToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.LOADERToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.RemoteDesktopToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.CLIENTToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.CLOSEToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.UPDATEToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.UNINSTALLToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.ToolStripSeparator1 = New System.Windows.Forms.ToolStripSeparator()
        Me.BUILDERToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.ToolStripSeparator2 = New System.Windows.Forms.ToolStripSeparator()
        Me.AboutToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.Timer_Ping = New System.Windows.Forms.Timer(Me.components)
        Me.StatusStrip1 = New System.Windows.Forms.StatusStrip()
        Me.ToolStripStatusLabel1 = New System.Windows.Forms.ToolStripStatusLabel()
        Me.Timer_Status = New System.Windows.Forms.Timer(Me.components)
        Me.TabControl1 = New System.Windows.Forms.TabControl()
        Me.TabPage1 = New System.Windows.Forms.TabPage()
        Me.TabPage3 = New System.Windows.Forms.TabPage()
        Me.LV2 = New System.Windows.Forms.ListView()
        Me.TabPage2 = New System.Windows.Forms.TabPage()
        Me.LV3 = New System.Windows.Forms.ListView()
        Me._NUM = CType(New System.Windows.Forms.ColumnHeader(), System.Windows.Forms.ColumnHeader)
        Me._CMD = CType(New System.Windows.Forms.ColumnHeader(), System.Windows.Forms.ColumnHeader)
        Me._EXE = CType(New System.Windows.Forms.ColumnHeader(), System.Windows.Forms.ColumnHeader)
        Me.TaskMenu = New System.Windows.Forms.ContextMenuStrip(Me.components)
        Me.AddTaskToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.RemoveTaskToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.ClientMenu.SuspendLayout()
        Me.StatusStrip1.SuspendLayout()
        Me.TabControl1.SuspendLayout()
        Me.TabPage1.SuspendLayout()
        Me.TabPage3.SuspendLayout()
        Me.TabPage2.SuspendLayout()
        Me.TaskMenu.SuspendLayout()
        Me.SuspendLayout()
        '
        'LV1
        '
        Me.LV1.BorderStyle = System.Windows.Forms.BorderStyle.None
        Me.LV1.Columns.AddRange(New System.Windows.Forms.ColumnHeader() {Me._IP, Me._COUNTRY, Me._ID, Me._Username, Me._OS, Me._VER, Me._TASKS})
        Me.LV1.ContextMenuStrip = Me.ClientMenu
        Me.LV1.Dock = System.Windows.Forms.DockStyle.Fill
        Me.LV1.Font = New System.Drawing.Font("Verdana", 8.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.LV1.FullRowSelect = True
        Me.LV1.GridLines = True
        Me.LV1.Location = New System.Drawing.Point(3, 3)
        Me.LV1.Name = "LV1"
        Me.LV1.Size = New System.Drawing.Size(1022, 300)
        Me.LV1.TabIndex = 0
        Me.LV1.UseCompatibleStateImageBehavior = False
        Me.LV1.View = System.Windows.Forms.View.Details
        '
        '_IP
        '
        Me._IP.Text = "IP"
        Me._IP.Width = 101
        '
        '_COUNTRY
        '
        Me._COUNTRY.Text = "Country"
        Me._COUNTRY.Width = 111
        '
        '_ID
        '
        Me._ID.Text = "ID"
        Me._ID.Width = 154
        '
        '_Username
        '
        Me._Username.Text = "Username"
        Me._Username.Width = 159
        '
        '_OS
        '
        Me._OS.Text = "Operating System"
        Me._OS.Width = 150
        '
        '_VER
        '
        Me._VER.Text = "Version"
        Me._VER.Width = 148
        '
        '_TASKS
        '
        Me._TASKS.Text = "Last Task"
        Me._TASKS.Width = 103
        '
        'ClientMenu
        '
        Me.ClientMenu.Font = New System.Drawing.Font("Tahoma", 9.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.ClientMenu.ImageScalingSize = New System.Drawing.Size(24, 24)
        Me.ClientMenu.Items.AddRange(New System.Windows.Forms.ToolStripItem() {Me.DownloadAndExecuteToolStripMenuItem, Me.RemoteDesktopToolStripMenuItem, Me.CLIENTToolStripMenuItem, Me.ToolStripSeparator1, Me.BUILDERToolStripMenuItem, Me.ToolStripSeparator2, Me.AboutToolStripMenuItem})
        Me.ClientMenu.Name = "ClientMenu"
        Me.ClientMenu.Size = New System.Drawing.Size(267, 166)
        '
        'DownloadAndExecuteToolStripMenuItem
        '
        Me.DownloadAndExecuteToolStripMenuItem.DropDownItems.AddRange(New System.Windows.Forms.ToolStripItem() {Me.DROPANDEXECUTEToolStripMenuItem, Me.LOADERToolStripMenuItem})
        Me.DownloadAndExecuteToolStripMenuItem.Image = Global.Server.My.Resources.Resources._007_cloud_computing
        Me.DownloadAndExecuteToolStripMenuItem.Name = "DownloadAndExecuteToolStripMenuItem"
        Me.DownloadAndExecuteToolStripMenuItem.Size = New System.Drawing.Size(266, 30)
        Me.DownloadAndExecuteToolStripMenuItem.Text = "DOWNLOAD EXECUTE"
        '
        'DROPANDEXECUTEToolStripMenuItem
        '
        Me.DROPANDEXECUTEToolStripMenuItem.Image = Global.Server.My.Resources.Resources.cloud_computing
        Me.DROPANDEXECUTEToolStripMenuItem.Name = "DROPANDEXECUTEToolStripMenuItem"
        Me.DROPANDEXECUTEToolStripMenuItem.Size = New System.Drawing.Size(259, 30)
        Me.DROPANDEXECUTEToolStripMenuItem.Text = "DROP AND EXECUTE"
        '
        'LOADERToolStripMenuItem
        '
        Me.LOADERToolStripMenuItem.Image = Global.Server.My.Resources.Resources.cloud_computing
        Me.LOADERToolStripMenuItem.Name = "LOADERToolStripMenuItem"
        Me.LOADERToolStripMenuItem.Size = New System.Drawing.Size(259, 30)
        Me.LOADERToolStripMenuItem.Text = "RUN IN MEMORY"
        '
        'RemoteDesktopToolStripMenuItem
        '
        Me.RemoteDesktopToolStripMenuItem.Image = Global.Server.My.Resources.Resources._003_monitors
        Me.RemoteDesktopToolStripMenuItem.Name = "RemoteDesktopToolStripMenuItem"
        Me.RemoteDesktopToolStripMenuItem.Size = New System.Drawing.Size(266, 30)
        Me.RemoteDesktopToolStripMenuItem.Text = "REMOTE DESKTOP"
        '
        'CLIENTToolStripMenuItem
        '
        Me.CLIENTToolStripMenuItem.DropDownItems.AddRange(New System.Windows.Forms.ToolStripItem() {Me.CLOSEToolStripMenuItem, Me.UPDATEToolStripMenuItem, Me.UNINSTALLToolStripMenuItem})
        Me.CLIENTToolStripMenuItem.Image = Global.Server.My.Resources.Resources._020_group_4
        Me.CLIENTToolStripMenuItem.Name = "CLIENTToolStripMenuItem"
        Me.CLIENTToolStripMenuItem.Size = New System.Drawing.Size(266, 30)
        Me.CLIENTToolStripMenuItem.Text = "CLIENT"
        '
        'CLOSEToolStripMenuItem
        '
        Me.CLOSEToolStripMenuItem.Image = Global.Server.My.Resources.Resources._036_warning
        Me.CLOSEToolStripMenuItem.Name = "CLOSEToolStripMenuItem"
        Me.CLOSEToolStripMenuItem.Size = New System.Drawing.Size(185, 30)
        Me.CLOSEToolStripMenuItem.Text = "CLOSE"
        '
        'UPDATEToolStripMenuItem
        '
        Me.UPDATEToolStripMenuItem.Image = Global.Server.My.Resources.Resources._014_forward
        Me.UPDATEToolStripMenuItem.Name = "UPDATEToolStripMenuItem"
        Me.UPDATEToolStripMenuItem.Size = New System.Drawing.Size(185, 30)
        Me.UPDATEToolStripMenuItem.Text = "UPDATE"
        '
        'UNINSTALLToolStripMenuItem
        '
        Me.UNINSTALLToolStripMenuItem.Image = Global.Server.My.Resources.Resources._029_power_off
        Me.UNINSTALLToolStripMenuItem.Name = "UNINSTALLToolStripMenuItem"
        Me.UNINSTALLToolStripMenuItem.Size = New System.Drawing.Size(185, 30)
        Me.UNINSTALLToolStripMenuItem.Text = "UNINSTALL"
        '
        'ToolStripSeparator1
        '
        Me.ToolStripSeparator1.Name = "ToolStripSeparator1"
        Me.ToolStripSeparator1.Size = New System.Drawing.Size(263, 6)
        '
        'BUILDERToolStripMenuItem
        '
        Me.BUILDERToolStripMenuItem.Image = Global.Server.My.Resources.Resources._025_settings
        Me.BUILDERToolStripMenuItem.Name = "BUILDERToolStripMenuItem"
        Me.BUILDERToolStripMenuItem.Size = New System.Drawing.Size(266, 30)
        Me.BUILDERToolStripMenuItem.Text = "BUILDER"
        '
        'ToolStripSeparator2
        '
        Me.ToolStripSeparator2.Name = "ToolStripSeparator2"
        Me.ToolStripSeparator2.Size = New System.Drawing.Size(263, 6)
        '
        'AboutToolStripMenuItem
        '
        Me.AboutToolStripMenuItem.Image = Global.Server.My.Resources.Resources._008_email
        Me.AboutToolStripMenuItem.Name = "AboutToolStripMenuItem"
        Me.AboutToolStripMenuItem.Size = New System.Drawing.Size(266, 30)
        Me.AboutToolStripMenuItem.Text = "ABOUT"
        '
        'Timer_Ping
        '
        Me.Timer_Ping.Enabled = True
        Me.Timer_Ping.Interval = 30000
        '
        'StatusStrip1
        '
        Me.StatusStrip1.ImageScalingSize = New System.Drawing.Size(24, 24)
        Me.StatusStrip1.Items.AddRange(New System.Windows.Forms.ToolStripItem() {Me.ToolStripStatusLabel1})
        Me.StatusStrip1.Location = New System.Drawing.Point(0, 337)
        Me.StatusStrip1.Name = "StatusStrip1"
        Me.StatusStrip1.Padding = New System.Windows.Forms.Padding(1, 0, 16, 0)
        Me.StatusStrip1.Size = New System.Drawing.Size(1036, 23)
        Me.StatusStrip1.TabIndex = 1
        Me.StatusStrip1.Text = "StatusStrip1"
        '
        'ToolStripStatusLabel1
        '
        Me.ToolStripStatusLabel1.Font = New System.Drawing.Font("Verdana", 8.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.ToolStripStatusLabel1.Name = "ToolStripStatusLabel1"
        Me.ToolStripStatusLabel1.Size = New System.Drawing.Size(20, 18)
        Me.ToolStripStatusLabel1.Text = ".."
        '
        'Timer_Status
        '
        Me.Timer_Status.Enabled = True
        Me.Timer_Status.Interval = 500
        '
        'TabControl1
        '
        Me.TabControl1.Controls.Add(Me.TabPage1)
        Me.TabControl1.Controls.Add(Me.TabPage3)
        Me.TabControl1.Controls.Add(Me.TabPage2)
        Me.TabControl1.Dock = System.Windows.Forms.DockStyle.Fill
        Me.TabControl1.Font = New System.Drawing.Font("Verdana", 8.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.TabControl1.Location = New System.Drawing.Point(0, 0)
        Me.TabControl1.Name = "TabControl1"
        Me.TabControl1.SelectedIndex = 0
        Me.TabControl1.Size = New System.Drawing.Size(1036, 337)
        Me.TabControl1.TabIndex = 2
        '
        'TabPage1
        '
        Me.TabPage1.Controls.Add(Me.LV1)
        Me.TabPage1.ImageKey = "(none)"
        Me.TabPage1.Location = New System.Drawing.Point(4, 27)
        Me.TabPage1.Name = "TabPage1"
        Me.TabPage1.Padding = New System.Windows.Forms.Padding(3)
        Me.TabPage1.Size = New System.Drawing.Size(1028, 306)
        Me.TabPage1.TabIndex = 0
        Me.TabPage1.Text = "Clients List"
        Me.TabPage1.UseVisualStyleBackColor = True
        '
        'TabPage3
        '
        Me.TabPage3.Controls.Add(Me.LV2)
        Me.TabPage3.Location = New System.Drawing.Point(4, 27)
        Me.TabPage3.Name = "TabPage3"
        Me.TabPage3.Padding = New System.Windows.Forms.Padding(3)
        Me.TabPage3.Size = New System.Drawing.Size(1028, 306)
        Me.TabPage3.TabIndex = 2
        Me.TabPage3.Text = "Logs"
        Me.TabPage3.UseVisualStyleBackColor = True
        '
        'LV2
        '
        Me.LV2.BorderStyle = System.Windows.Forms.BorderStyle.None
        Me.LV2.Dock = System.Windows.Forms.DockStyle.Fill
        Me.LV2.Font = New System.Drawing.Font("Verdana", 8.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.LV2.HeaderStyle = System.Windows.Forms.ColumnHeaderStyle.None
        Me.LV2.Location = New System.Drawing.Point(3, 3)
        Me.LV2.MultiSelect = False
        Me.LV2.Name = "LV2"
        Me.LV2.Size = New System.Drawing.Size(1022, 300)
        Me.LV2.TabIndex = 0
        Me.LV2.UseCompatibleStateImageBehavior = False
        Me.LV2.View = System.Windows.Forms.View.List
        '
        'TabPage2
        '
        Me.TabPage2.Controls.Add(Me.LV3)
        Me.TabPage2.Location = New System.Drawing.Point(4, 27)
        Me.TabPage2.Name = "TabPage2"
        Me.TabPage2.Padding = New System.Windows.Forms.Padding(3)
        Me.TabPage2.Size = New System.Drawing.Size(1028, 306)
        Me.TabPage2.TabIndex = 1
        Me.TabPage2.Text = "Tasks List"
        Me.TabPage2.UseVisualStyleBackColor = True
        '
        'LV3
        '
        Me.LV3.BorderStyle = System.Windows.Forms.BorderStyle.None
        Me.LV3.Columns.AddRange(New System.Windows.Forms.ColumnHeader() {Me._NUM, Me._CMD, Me._EXE})
        Me.LV3.ContextMenuStrip = Me.TaskMenu
        Me.LV3.Dock = System.Windows.Forms.DockStyle.Fill
        Me.LV3.Font = New System.Drawing.Font("Verdana", 8.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.LV3.FullRowSelect = True
        Me.LV3.GridLines = True
        Me.LV3.Location = New System.Drawing.Point(3, 3)
        Me.LV3.Name = "LV3"
        Me.LV3.Size = New System.Drawing.Size(1022, 300)
        Me.LV3.TabIndex = 0
        Me.LV3.UseCompatibleStateImageBehavior = False
        Me.LV3.View = System.Windows.Forms.View.Details
        '
        '_NUM
        '
        Me._NUM.Text = "Task"
        Me._NUM.Width = 109
        '
        '_CMD
        '
        Me._CMD.Text = "Command"
        Me._CMD.Width = 156
        '
        '_EXE
        '
        Me._EXE.Text = "Execution"
        Me._EXE.Width = 148
        '
        'TaskMenu
        '
        Me.TaskMenu.ImageScalingSize = New System.Drawing.Size(24, 24)
        Me.TaskMenu.Items.AddRange(New System.Windows.Forms.ToolStripItem() {Me.AddTaskToolStripMenuItem, Me.RemoveTaskToolStripMenuItem})
        Me.TaskMenu.Name = "TaskMenu"
        Me.TaskMenu.Size = New System.Drawing.Size(201, 64)
        '
        'AddTaskToolStripMenuItem
        '
        Me.AddTaskToolStripMenuItem.Name = "AddTaskToolStripMenuItem"
        Me.AddTaskToolStripMenuItem.Size = New System.Drawing.Size(200, 30)
        Me.AddTaskToolStripMenuItem.Text = "ADD TASK"
        '
        'RemoveTaskToolStripMenuItem
        '
        Me.RemoveTaskToolStripMenuItem.Name = "RemoveTaskToolStripMenuItem"
        Me.RemoveTaskToolStripMenuItem.Size = New System.Drawing.Size(200, 30)
        Me.RemoveTaskToolStripMenuItem.Text = "REMOVE TASK"
        '
        'Form1
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(10.0!, 18.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(1036, 360)
        Me.Controls.Add(Me.TabControl1)
        Me.Controls.Add(Me.StatusStrip1)
        Me.Font = New System.Drawing.Font("Verdana", 8.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Icon = CType(resources.GetObject("$this.Icon"), System.Drawing.Icon)
        Me.Name = "Form1"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = " AsyncRAT"
        Me.ClientMenu.ResumeLayout(False)
        Me.StatusStrip1.ResumeLayout(False)
        Me.StatusStrip1.PerformLayout()
        Me.TabControl1.ResumeLayout(False)
        Me.TabPage1.ResumeLayout(False)
        Me.TabPage3.ResumeLayout(False)
        Me.TabPage2.ResumeLayout(False)
        Me.TaskMenu.ResumeLayout(False)
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub

    Friend WithEvents LV1 As ListView
    Friend WithEvents _IP As ColumnHeader
    Friend WithEvents _Username As ColumnHeader
    Friend WithEvents _OS As ColumnHeader
    Friend WithEvents ClientMenu As ContextMenuStrip
    Friend WithEvents DownloadAndExecuteToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents Timer_Ping As Timer
    Friend WithEvents RemoteDesktopToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents StatusStrip1 As StatusStrip
    Friend WithEvents ToolStripStatusLabel1 As ToolStripStatusLabel
    Friend WithEvents Timer_Status As Timer
    Friend WithEvents _VER As ColumnHeader
    Friend WithEvents _ID As ColumnHeader
    Friend WithEvents CLIENTToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents CLOSEToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents UPDATEToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents ToolStripSeparator1 As ToolStripSeparator
    Friend WithEvents AboutToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents TabControl1 As TabControl
    Friend WithEvents TabPage1 As TabPage
    Friend WithEvents TabPage2 As TabPage
    Friend WithEvents LV3 As ListView
    Friend WithEvents _NUM As ColumnHeader
    Friend WithEvents _CMD As ColumnHeader
    Friend WithEvents _EXE As ColumnHeader
    Friend WithEvents TaskMenu As ContextMenuStrip
    Friend WithEvents AddTaskToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents RemoveTaskToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents _TASKS As ColumnHeader
    Friend WithEvents BUILDERToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents ToolStripSeparator2 As ToolStripSeparator
    Friend WithEvents TabPage3 As TabPage
    Friend WithEvents LV2 As ListView
    Friend WithEvents UNINSTALLToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents _COUNTRY As ColumnHeader
    Friend WithEvents DROPANDEXECUTEToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents LOADERToolStripMenuItem As ToolStripMenuItem
End Class
