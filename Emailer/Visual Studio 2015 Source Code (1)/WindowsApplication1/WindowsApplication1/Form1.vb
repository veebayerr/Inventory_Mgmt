Imports System.Net.Mail

'Group Five Inventory Management Software Fall 2016
'Matthew Mitchell Brita Ramsay Nathanael Baker Trevor Absher Vanessa Pena




Public Class Send_Report
    Private Sub Form1_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        ToolStripTextBox1.Text = My.Settings.StoreNo        'load the information when the window is loaded
        ToolStripTextBox2.Text = My.Settings.SendAddress
        ToolStripTextBox3.Text = My.Settings.RunTime
        Label1.Text = "Auto Report runs at " + My.Settings.RunTime
    End Sub

    Private Sub ToolStripTextBox1_Click(sender As Object, e As EventArgs) Handles ToolStripTextBox1.TextChanged
        My.Settings.StoreNo = ToolStripTextBox1.Text
    End Sub


    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        generateReport()    'manually run the report with a button
    End Sub
    Function generateReport()
        WebBrowser1.Navigate("http://groupfive.ddns.net/Scripts/email_payment_report.php?start_date=" + CDate(Now).ToString("yyyy-MM-dd") + "&end_date=" + CDate(Now).ToString("yyyy-MM-dd") + "&Submit=Run&storeNo=" + My.Settings.StoreNo)
        WebBrowser2.Navigate("http://groupfive.ddns.net/Scripts/email_item_report.php?start_date=" + CDate(Now).ToString("yyyy-MM-dd") + "&end_date=" + CDate(Now).ToString("yyyy-MM-dd") + "&Submit=Run&storeNo=" + My.Settings.StoreNo)
        WebBrowser3.Navigate("http://groupfive.ddns.net/Scripts/email_trigger_point_items.php?userId=549&storeNo=" + My.Settings.StoreNo)
        WebBrowser4.Navigate("http://groupfive.ddns.net/Scripts/email_expired_items.php?userId=549&storeNo=1")
        Timer1.Enabled = True
        'this method is tom foolery, has 3 webbrowswers load the reports by store and by date and turns them into an email later
    End Function

    Private Sub Timer1_Tick(sender As Object, e As EventArgs) Handles Timer1.Tick
        Dim outStr As String = ""

        Try
            'if the browsers are done loading generate the report
            outStr = WebBrowser1.Document.Body.InnerText + vbNewLine + "____________________________________________" + vbNewLine + WebBrowser2.Document.Body.InnerText + vbNewLine + "____________________________________________" + vbNewLine + WebBrowser3.Document.Body.InnerText + vbNewLine + "____________________________________________" + vbNewLine + WebBrowser2.Document.Body.InnerText + vbNewLine + "____________________________________________" + vbNewLine + WebBrowser4.Document.Body.InnerText + vbNewLine

            Timer1.Enabled = False
        Catch ex As Exception

        End Try

        Try
            'mail the report
            Dim mail As New MailMessage
            Dim SMTP As New SmtpClient("smtp.gmail.com")
            mail.Subject = CDate(Now).ToString("yyyy-MM-dd") + " Store Report"
            mail.From = New MailAddress("GroupFiveInventorySoftware@gmail.com")
            SMTP.Credentials = New System.Net.NetworkCredential("GroupFiveInventorySoftware@gmail.com", "uJaXPg7(mgYY?j7>")
            mail.To.Add(My.Settings.SendAddress)
            mail.Body = outStr
            SMTP.EnableSsl = True
            SMTP.Port = "587"
            SMTP.Send(mail)
            MsgBox(outStr)
        Catch ex As Exception

        End Try
    End Sub

    Private Sub ToolStripTextBox2_Click(sender As Object, e As EventArgs) Handles ToolStripTextBox2.TextChanged
        My.Settings.SendAddress = ToolStripTextBox2.Text 'update the email address to send to
    End Sub

    Private Sub ToolStripTextBox3_Click(sender As Object, e As EventArgs) Handles ToolStripTextBox3.TextChanged
        If (IsDate(ToolStripTextBox3.Text)) Then
            My.Settings.RunTime = ToolStripTextBox3.Text
            Label1.Text = "Auto Report runs at " + My.Settings.RunTime  'the report will automatically run at this time
        End If
    End Sub

    Private Sub Timer2_Tick(sender As Object, e As EventArgs) Handles Timer2.Tick
        If My.Settings.RunTime = TimeOfDay Then
            generateReport() ' generates the report if the set runtime is now
        End If
    End Sub
End Class