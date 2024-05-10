<?php
session_start();
require 'includes/database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor\PHPMailer-master/src/Exception.php';
require 'vendor\PHPMailer-master/src/PHPMailer.php';
require 'vendor\PHPMailer-master/src/SMTP.php';

function generateOTP()
{
    $otp = "";
    $digits = 6; // Number of digits in the OTP

    for ($i = 0; $i < $digits; $i++) {
        $otp .= random_int(0, 9); // Append a random digit (0-9)
    }

    return $otp;
}

function send_password_reset($get_name, $get_email, $token)
{
    $conn = getDB();
    $errors = [];

    if (filter_var($get_email, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = 'please enter a valid email address';
    }

    if (empty($errors)) {

        $mail = new PHPMailer(true);
        $otp = generateOTP();


        try {

            $timestamp =  $_SERVER["REQUEST_TIME"];
            $_SESSION['time'] = $timestamp;

            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = "true";
            $mail->Username = "kajaz6563@gmail.com";
            $mail->Password = "ezgxliszxuixmyix";
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;

            $mail->setFrom('kajaz6563@gmail.com', $get_name);
            $mail->addAddress($get_email);

            $mail->isHTML(true);
            $mail->Subject = "Reset Password Notification";
            $email_template = "<h2>Hello</h2
                              <h3>You are receiving this email because we received a password reset request for your account. </h3>
                              <br/>
                              <p>Your one time email verification code is . $otp
                              <br/><br/>
                              <a href='http://localhost:3000/verification_form.php'>  Click Me </a>
                              ";
            $mail->Body = $email_template;

            $mail->send();
            $sent = true;
            $_SESSION['email'] = $get_email;
            $select = mysqli_query($conn, "SELECT * FROM `otpverify` WHERE email = '$get_email'") or die('query failed');

            if (mysqli_num_rows($select) > 0) {

                $sql = "UPDATE `otpverify` SET otp='$otp', created_at=DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email='$get_email'";
                $result = mysqli_query($conn, $sql);
            } else {

                $sql = "INSERT INTO otpverify (user_id, OTP, email, created_at) VALUES((SELECT id FROM user WHERE email='$get_email'), '$otp','$get_email',DATE_ADD(NOW(), INTERVAL 15 MINUTE))";

                $result = mysqli_query($conn, $sql);
            }
        } catch (Exception $e) {
            $errors[] = $mail->ErrorInfo;
        }
    }
}



if (isset($_POST['password_reset_link'])) {

    $conn = getDB();

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $token = md5(rand());


    $check_email = "SELECT email FROM user WHERE email= '$email' LIMIT 1";
    $check_email_run = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($check_email_run) > 0) {
        $row = mysqli_fetch_array($check_email_run);
        $get_name = $row['username'];
        $get_email = $row['email'];

        $update_token = "UPDATE user SET verify_token='$token' WHERE email='$get_email' LIMIT 1";
        $update_token_run = mysqli_query($conn, $update_token);

        if ($update_token_run) {
            send_password_reset($get_name, $get_email, $token);
            $_SESSION['status'] = "We e-mailed you a password reset link and otp";
            header("Location:password-reset.php");
            exit(0);
        } else {
            $_SESSION['status'] = "Something Went Wrong. #1 ";
            header("Location:password-reset.php");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "No Email Found";
        header("Location:password-reset.php");
        exit(0);
    }
}

if (isset($_POST['password_update'])) {

    $conn = getDB();

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $token = mysqli_real_escape_string($conn, $_POST['password_token']);


    if (!empty($new_password) && !empty($confirm_password)) {



        if ($new_password == $confirm_password) {
            $update_password = "UPDATE user SET password='$new_password' WHERE verify_token='$token' LIMIT 1";
            $update_password_run = mysqli_query($conn, $update_password);

            if ($update_password_run) {
                $_SESSION['status'] = "New Password Successfully Updates.!";

                $errors = [];

                if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    $errors[] = 'please enter a valid email address';
                }

                if (empty($errors)) {

                    $mail = new PHPMailer(true);

                    try {

                        $mail->isSMTP();
                        $mail->Host = "smtp.gmail.com";
                        $mail->SMTPAuth = "true";
                        $mail->Username = "kajaz6563@gmail.com";
                        $mail->Password = "ezgxliszxuixmyix";
                        $mail->SMTPSecure = "tls";
                        $mail->Port = 587;

                        $mail->setFrom('kajaz6563@gmail.com');
                        $mail->addAddress($_POST["email"]);
                        $mail->addReplyTo($email);
                        $mail->isHTML(true);
                        $mail->Subject = "Reset Successful Notification";
                        $mail->Body = "<html><body>
                                                 <style type='text/css'>
                                                tg {border-collapse:collapse;border-spacing:0;}
                                                   </style>
                                                  <table class='tg' style='border-style: dotted;'>
                                    
                                    <tr><td class='tg-3zav'>Your New Password is : </td><td class='tg-3zav'>" . $_POST['new_password'] . "</td></tr>
                                    </table></body></html>";

                        $mail->send();
                        $sent = true;
                    } catch (Exception $e) {
                        $errors[] = $mail->ErrorInfo;
                    }
                }


                header("Location:login.php");
                exit(0);
            } else {
                $_SESSION['status'] = "Did not update password .Something went wrong.!";
                header("Location:password-change.php?token=$token&email=$email");
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Password and Confirm Password does not match";
            header("Location:password-change.php?token=$token&email=$email");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "All Field are Mandatary";
        header("Location:password-change.php");
        exit(0);
    }
}

$email = $_SESSION['email'];

if (isset($_POST['submit_otp'])) {

    $conn = getDB();
    $otp = mysqli_real_escape_string($conn, $_POST['otp']);

    $select = mysqli_query($conn, "SELECT * FROM `otpverify` WHERE email = '$email'") or die('query failed');
    if (mysqli_num_rows($select) > 0) {
        $fetch = mysqli_fetch_assoc($select);
        $ur_otp = $fetch['otp'];
        $ur_time = $fetch['created_at'];
        date_default_timezone_set('Asia/Kolkata');
        $current_time = date("H:i:s");
        echo "$current_time";
        $ur_time = ($ur_time);
        if ($ur_time >= $current_time) {
            if ($otp == $ur_otp) {
                header("location: password-change.php");
            } else {
                $message[] = 'OTP NOT MATCHED';
            }
        } else {
            echo '<script>';
            echo 'alert("Your session has expired");';
            echo '</script>';
        }
    } else {
        $message[] = 'NOT ASKED FOR OTP';
    }
}
