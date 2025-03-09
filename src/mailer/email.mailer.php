<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;

// Include Composer's autoloader
require __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

function sendWithMailerSend($recipientAddress, $recipientName, $subject, $body)
{
    try {
        // Initialize the MailerSend client with your API key
        $mailersend = new MailerSend(['api_key' => $_ENV['MAILSEND_API_KEY']]);

        // Define the recipient
        $recipients = [
            new Recipient($recipientAddress, $recipientName),
        ];

        // Configure the email parameters
        $emailParams = (new EmailParams())
            ->setFrom($_ENV['MAILSEND_EMAIL_ADDRESS'])
            ->setFromName('E-LIFE')
            ->setRecipients($recipients)
            ->setSubject($subject)
            ->setHtml($body)
            ->setText(strip_tags($body)); // Fallback text version

        // Send the email and get the response
        $response = $mailersend->email->send($emailParams);

        // Check the response status from the returned array
        if (isset($response['status_code'])) {
            return true;
        } else {
            // Log the error with the full response for debugging
            throw new Exception("MailerSend failed with response: " . json_encode($response));
            return false;
        }
    } catch (Exception $e) {
        // Log the error message and return false
        echo $e->getMessage() . "<br>";
        error_log("MailerSend Error: " . $e->getMessage());
        return false;
    }
}


function sendWithPhpMailer($recipientAddress, $recipientName, $subject, $body)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $_ENV['PHP_MAILER_EMAIL_ADDRESS'];            //SMTP username
        $mail->Password   = $_ENV['PHP_MAILER_EMAIL_APP_PASSWORD'];                    //SMTP password
        $mail->SMTPSecure = 'tls';                                  //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($_ENV['PHP_MAILER_EMAIL_ADDRESS'], 'E-LIFE TEAM');
        $mail->addAddress($recipientAddress, $recipientName);     //Add a recipient
        $mail->addReplyTo($_ENV['PHP_MAILER_EMAIL_ADDRESS'], 'E-LIFE TEAM');



        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        $mail->send();
        echo 'Message has been sent';
        return true;
    } catch (Exception $err) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}


// Main function to send email
function sendEmail($recipientAddress, $recipientName, $subject, $body)
{
    // Try PHPMailer first
    if (sendWithPHPMailer($recipientAddress, $recipientName, $subject, $body)) {
        echo "Email sent using PHPMailer!";
    } else {
        // If PHPMailer fails, fallback to SendGrid
        if (sendWithMailerSend($recipientAddress, $recipientName, $subject, $body) === true) {
            echo "Email sent using SendGrid!";
        } else {
            echo "<br>Failed to send email with both SendGrid and PHPMailer.";
        }
    }
}

// sendEmail("kingsleyikenna2019@gmail.com", "Kingsley", "Test", "This is to test the email section");
