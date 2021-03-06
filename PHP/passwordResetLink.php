<?php

include("./includes/encryption.php");

if(isset($_POST['resetLink'])){
    require "./includes/connection.php";
    require("./includes/table_columns_name.php");

    $email = protect($_POST['email']);

    $str = "/6G6F;WvK7;s{au/6G6F;WvK7;s{au";
    $key = md5($str);

    $encryptedEmail = encryptData($email, $key, $str);

    $sql = "SELECT $username_column, uId FROM users WHERE $email_column = '$encryptedEmail'";
    $result = mysqli_query($connect, $sql);

        if(mysqli_num_rows($result) > 0){
            require('PHPMailer/PHPMailerAutoload.php');

            $row = mysqli_fetch_assoc($result);
            $name = $row[$username_column];
            $uId = $row['uId'];
            
            $url = "http://localhost/Minor_Project_SBR/PAGES/resetPassword.php?uId=$uId";
            // $secureUrl = "https://localhost/college_project/Minor_Project_SBR/PAGES/changePassword.php?uId=$uId";

            // * Message body of the mail
            $mssgBody = 
            "
            <h2>Hello, $name </h2>
            <p>
            You have requested a password reseting link for your account.<br>
            Click the link below to change your password<br><br>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
            <tr>
                <td>
                <table border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                    <td>
                        <a href='$url' target='_blank' style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #000; text-decoration: none; border-radius: 3px; background-color: #f8e9a1; border-top: 12px solid #f8e9a1; border-bottom: 12px solid #f8e9a1; border-right: 18px solid #f8e9a1; border-left: 18px solid #f8e9a1; display: inline-block;'>Change your Password</a>
                    </td>
                    </tr>
                </table>
                </td>
            </tr>
            </table>
            </p>
            ";

            // If you didn't requested for a password reset link<br>
            // Click <a href='$secureUrl'>here</a> for changing your password and securing
            // your data.
            $adminEmail = 'swiftbluebook10@gmail.com';

            // * creating a PHPMailer instance
            $mail = new PHPMailer(true);

            // * configuring the PHPMailer to SMTP for gmail
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = $adminEmail;
            $mail->Password = 'SBR12345rbs';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            include("./includes/phpMailer_fix.php");

            // * setting the email address and name of sender
            $mail->setFrom($adminEmail, 'Swift Bluebook');

            // * setting email address and name of reciver
            $mail->addAddress($email, $name);

            // * Setting subject of the email
            $mail->Subject = 'Password Reset';

            // * defining the body message contains HTML
            $mail->IsHTML(true);

            // * setting the body of the email
            $mail->Body = $mssgBody;

            // * sending the email and checking error
            if(!$mail->send()){
                header("location: ../PAGES/forgetPassword.php?error=SendMailError");
            }else{
                header("location: ../PAGES/forgetPassword.php?mssg=CheckEmailForPass");
            }

        }else{
            header("location: ../PAGES/forgetPassword.php?error=NotaUser");
        }

}else{
    header("location: ../PAGES/forgetPassword.php?error=IllegalWay");
}