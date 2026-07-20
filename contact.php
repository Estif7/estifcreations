<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'wp-content/PHPMailer/vendor/autoload.php'; // Adjust path if needed

$response = ['status' => '', 'message' => '', 'debug' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['your-name'] ?? '');
    $email = htmlspecialchars($_POST['your-email'] ?? '');
    $message = htmlspecialchars($_POST['your-message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $response = ['status' => 'error', 'message' => 'All fields are required.'];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = ['status' => 'error', 'message' => 'Invalid email address.'];
    } else {
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 2; // Enable debugging
            $mail->Debugoutput = function($str, $level) use (&$response) {
                $response['debug'] .= "[$level] $str\n";
            };
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'estifanosedejene7@gmail.com';
            $mail->Password = 'kfoq dgol iriz whog'; // Replace with your App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('estifanosedejene7@gmail.com', 'Contact Form');
            $mail->addAddress('estifanosedejene7@gmail.com');
            $mail->addReplyTo($email, $name);
            $mail->isHTML(true);
            $mail->Subject = 'Contact Form Message from ' . $name;
            $mail->Body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f5f1ed; margin: 0; padding: 40px; }
                    .container { max-width: 650px; margin: auto; background: #ffffff; border-radius: 15px; box-shadow: 0 15px 30px rgba(0,0,0,0.15); padding: 40px; border: 1px solid #78cc6d; }
                    .header { text-align: center; margin-bottom: 50px; padding: 20px; background: linear-gradient(135deg, #1a1a1a, #2f2f2f); border-radius: 15px 15px 0 0; color: #78cc6d; }
                    .header h1 { font-size: 32px; margin: 0; font-weight: 500; letter-spacing: 1.5px; }
                    .header p { font-size: 18px; color: #78cc6d; margin: 10px 0 0; }
                    .content { margin-bottom: 50px; }
                    .content p { font-size: 18px; line-height: 1.9; color: #1a1a1a; margin: 15px 0; }
                    .content .label { color: #78cc6d; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 16px; }
                    .content .value { color: #333; font-size: 18px; margin-left: 10px; }
                    .content blockquote { border-left: 5px solid #78cc6d; padding: 20px 30px; background-color: #faf8f5; margin: 25px 0; font-size: 19px; line-height: 1.7; color: #1a1a1a; font-style: italic; border-radius: 5px; }
                    .footer { text-align: center; font-size: 16px; color: #666; border: 1px solid #78cc6d; padding-top: 30px; margin-top: 50px; background: #faf8f5; border-radius: 0 0 15px 15px; }
                    .footer p { margin: 8px 0; font-style: italic; }
                    .footer a { color: #78cc6d; text-decoration: none; font-weight: 600; transition: color 0.3s ease; }
                    .footer a:hover { color: #5a9e4f; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Message from estifcreations.com</h1>
                        <p>A New Message Awaits Your Attention</p>
                    </div>
                    <div class='content'>
                        <p><span class='label'>Sender:</span><span class='value'>$name</span></p>
                        <p><span class='label'>Email:</span><span class='value'>$email</span></p>
                        <p><span class='label'>Message:</span></p>
                        <blockquote>$message</blockquote>
                    </div>
                    <div class='footer'>
                        <p>This message was sent from your website's contact form.</p>
                        <p><a href='https://estifcreations.com'>Visit estifcreations.com</a></p>
                    </div>
                </div>
            </body>
            </html>
            ";

            if ($mail->send()) {
                $response = ['status' => 'success', 'message' => 'Your message was sent successfully! <br> I will get back to you as soon as possible.', 'debug' => $response['debug']];
            } else {
                $response = ['status' => 'error', 'message' => 'Failed to send message.', 'debug' => $response['debug']];
            }
        } catch (Exception $e) {
            $response = ['status' => 'error', 'message' => 'Failed to send message: ' . $e->getMessage(), 'debug' => $response['debug']];
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}