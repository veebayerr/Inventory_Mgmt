<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class Send_Report
    Inherits System.Windows.Forms.Form

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()> _
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
    <System.Diagnostics.DebuggerStepThrough()> _
    Private Sub InitializeComponent()
        Me.components = New System.ComponentModel.Container()
        Me.WebBrowser1 = New System.Windows.Forms.WebBrowser()
        Me.WebBrowser2 = New System.Windows.Forms.WebBrowser()
        Me.WebBrowser3 = New System.Windows.Forms.WebBrowser()
        Me.MenuStrip1 = New System.Windows.Forms.MenuStrip()
        Me.SettingsToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.SetStoreNumberToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.ToolStripTextBox1 = New System.Windows.Forms.ToolStripTextBox()
        Me.SetReportTimeToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.ToolStripTextBox3 = New System.Windows.Forms.ToolStripTextBox()
        Me.SetSendAddressToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem()
        Me.ToolStripTextBox2 = New System.Windows.Forms.ToolStripTextBox()
        Me.Button1 = New System.Windows.Forms.Button()
        Me.Timer1 = New System.Windows.Forms.Timer(Me.components)
        Me.Label1 = New System.Windows.Forms.Label()
        Me.Timer2 = New System.Windows.Forms.Timer(Me.components)
        Me.WebBrowser4 = New System.Windows.Forms.WebBrowser()
        Me.MenuStrip1.SuspendLayout()
        Me.SuspendLayout()
        '
        'WebBrowser1
        '
        Me.WebBrowser1.Location = New System.Drawing.Point(604, 138)
        Me.WebBrowser1.MinimumSize = New System.Drawing.Size(20, 20)
        Me.WebBrowser1.Name = "WebBrowser1"
        Me.WebBrowser1.Size = New System.Drawing.Size(76, 86)
        Me.WebBrowser1.TabIndex = 0
        Me.WebBrowser1.Visible = False
        '
        'WebBrowser2
        '
        Me.WebBrowser2.Location = New System.Drawing.Point(604, 254)
        Me.WebBrowser2.MinimumSize = New System.Drawing.Size(20, 20)
        Me.WebBrowser2.Name = "WebBrowser2"
        Me.WebBrowser2.Size = New System.Drawing.Size(57, 36)
        Me.WebBrowser2.TabIndex = 1
        Me.WebBrowser2.Visible = False
        '
        'WebBrowser3
        '
        Me.WebBrowser3.Location = New System.Drawing.Point(599, 296)
        Me.WebBrowser3.MinimumSize = New System.Drawing.Size(20, 20)
        Me.WebBrowser3.Name = "WebBrowser3"
        Me.WebBrowser3.Size = New System.Drawing.Size(62, 52)
        Me.WebBrowser3.TabIndex = 2
        Me.WebBrowser3.Visible = False
        '
        'MenuStrip1
        '
        Me.MenuStrip1.Items.AddRange(New System.Windows.Forms.ToolStripItem() {Me.SettingsToolStripMenuItem})
        Me.MenuStrip1.Location = New System.Drawing.Point(0, 0)
        Me.MenuStrip1.Name = "MenuStrip1"
        Me.MenuStrip1.Size = New System.Drawing.Size(251, 24)
        Me.MenuStrip1.TabIndex = 4
        Me.MenuStrip1.Text = "MenuStrip1"
        '
        'SettingsToolStripMenuItem
        '
        Me.SettingsToolStripMenuItem.DropDownItems.AddRange(New System.Windows.Forms.ToolStripItem() {Me.SetStoreNumberToolStripMenuItem, Me.SetReportTimeToolStripMenuItem, Me.SetSendAddressToolStripMenuItem})
        Me.SettingsToolStripMenuItem.Name = "SettingsToolStripMenuItem"
        Me.SettingsToolStripMenuItem.Size = New System.Drawing.Size(61, 20)
        Me.SettingsToolStripMenuItem.Text = "Settings"
        '
        'SetStoreNumberToolStripMenuItem
        '
        Me.SetStoreNumberToolStripMenuItem.DropDownItems.AddRange(New System.Windows.Forms.ToolStripItem() {Me.ToolStripTextBox1})
        Me.SetStoreNumberToolStripMenuItem.Name = "SetStoreNumberToolStripMenuItem"
        Me.SetStoreNumberToolStripMenuItem.Size = New System.Drawing.Size(167, 22)
        Me.SetStoreNumberToolStripMenuItem.Text = "Set Store Number"
        '
        'ToolStripTextBox1
        '
        Me.ToolStripTextBox1.Name = "ToolStripTextBox1"
        Me.ToolStripTextBox1.Size = New System.Drawing.Size(100, 23)
        '
        'SetReportTimeToolStripMenuItem
        '
        Me.SetReportTimeToolStripMenuItem.DropDownItems.AddRange(New System.Windows.Forms.ToolStripItem() {Me.ToolStripTextBox3})
        Me.SetReportTimeToolStripMenuItem.Name = "SetReportTimeToolStripMenuItem"
        Me.SetReportTimeToolStripMenuItem.Size = New System.Drawing.Size(167, 22)
        Me.SetReportTimeToolStripMenuItem.Text = "Set Report Time"
        '
        'ToolStripTextBox3
        '
        Me.ToolStripTextBox3.Name = "ToolStripTextBox3"
        Me.ToolStripTextBox3.Size = New System.Drawing.Size(100, 23)
        '
        'SetSendAddressToolStripMenuItem
        '
        Me.SetSendAddressToolStripMenuItem.DropDownItems.AddRange(New System.Windows.Forms.ToolStripItem() {Me.ToolStripTextBox2})
        Me.SetSendAddressToolStripMenuItem.Name = "SetSendAddressToolStripMenuItem"
        Me.SetSendAddressToolStripMenuItem.Size = New System.Drawing.Size(167, 22)
        Me.SetSendAddressToolStripMenuItem.Text = "Set Send Address"
        '
        'ToolStripTextBox2
        '
        Me.ToolStripTextBox2.Name = "ToolStripTextBox2"
        Me.ToolStripTextBox2.Size = New System.Drawing.Size(100, 23)
        '
        'Button1
        '
        Me.Button1.Location = New System.Drawing.Point(12, 59)
        Me.Button1.Name = "Button1"
        Me.Button1.Size = New System.Drawing.Size(75, 23)
        Me.Button1.TabIndex = 5
        Me.Button1.Text = "Run Now"
        Me.Button1.UseVisualStyleBackColor = True
        '
        'Timer1
        '
        Me.Timer1.Interval = 5000
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Location = New System.Drawing.Point(12, 24)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(39, 13)
        Me.Label1.TabIndex = 6
        Me.Label1.Text = "Label1"
        '
        'Timer2
        '
        Me.Timer2.Enabled = True
        '
        'WebBrowser4
        '
        Me.WebBrowser4.Location = New System.Drawing.Point(280, 91)
        Me.WebBrowser4.MinimumSize = New System.Drawing.Size(20, 20)
        Me.WebBrowser4.Name = "WebBrowser4"
        Me.WebBrowser4.Size = New System.Drawing.Size(250, 250)
        Me.WebBrowser4.TabIndex = 7
        '
        'Send_Report
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(6.0!, 13.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(251, 124)
        Me.Controls.Add(Me.WebBrowser4)
        Me.Controls.Add(Me.Label1)
        Me.Controls.Add(Me.Button1)
        Me.Controls.Add(Me.WebBrowser3)
        Me.Controls.Add(Me.WebBrowser2)
        Me.Controls.Add(Me.WebBrowser1)
        Me.Controls.Add(Me.MenuStrip1)
        Me.ForeColor = System.Drawing.SystemColors.ControlText
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.Fixed3D
        Me.MaximizeBox = False
        Me.Name = "Send_Report"
        Me.ShowIcon = False
        Me.Text = "Store Report Mailer"
        Me.MenuStrip1.ResumeLayout(False)
        Me.MenuStrip1.PerformLayout()
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub

    Friend WithEvents WebBrowser1 As WebBrowser
    Friend WithEvents WebBrowser2 As WebBrowser
    Friend WithEvents WebBrowser3 As WebBrowser
    Friend WithEvents MenuStrip1 As MenuStrip
    Friend WithEvents SettingsToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents SetStoreNumberToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents SetReportTimeToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents ToolStripTextBox1 As ToolStripTextBox
    Friend WithEvents Button1 As Button
    Friend WithEvents Timer1 As Timer
    Friend WithEvents Label1 As Label
    Friend WithEvents SetSendAddressToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents ToolStripTextBox2 As ToolStripTextBox
    Friend WithEvents ToolStripTextBox3 As ToolStripTextBox
    Friend WithEvents Timer2 As Timer
    Friend WithEvents WebBrowser4 As WebBrowser
End Class
