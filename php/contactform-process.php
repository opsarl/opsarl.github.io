<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
if (empty($_POST["name"])) {
    echo_result("Name is required ");
}


if(empty($_POST["email"]) ) {
    echo_result("Email is required ");
}

$domain = substr($_POST["email"], strpos($_POST["email"],"@")+1);
if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) || !checkdnsrr($domain) ) {
    echo_result("Valid Email address is required ");
}

if (empty($_POST["message"])) {
    echo_result("Message is required ");
}

// if (empty($_POST["terms"])) {
//     echo_result("Terms is required ");
// }

require('../../vendor/autoload.php');
$mail = new PHPMailer(true);

try {
    //Server settings
    #$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = '10.8.80.110';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'opsa150@postmailer.ch';                     // SMTP username
    $mail->Password   = 'E2da9354f11cccbd82c7eba6d881ec1b';                               // SMTP password
    #$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above or 587 for ENCRYPTION_STARTTLS
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    //Recipients
    $mail->setFrom('opsa150@postmailer.ch', 'Mailing Proxy');
    $mail->addReplyTo('dob@opsa.ch', "Dominic O'Brien");
    $mail->addAddress('dob@opsa.ch', "Dominic O'Brien");     // Add a recipient
#    $mail->addAddress('dob@opsa.ch', "Dominic O'Brien");     // Add another recipient
#   $mail->addCC('cc@example.com');
#   $mail->addBCC('bcc@example.com');

    // Attachments
#    $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
#    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = "Message from opsa.ch contact page";
    $mail->Body = "Name: ";
    $mail->Body.= $_POST["name"];
    $mail->Body.= "<br>";
    $mail->Body.= "Email: ";
    $mail->Body.= $_POST["email"];
    $mail->Body.= "<br>";
    $mail->Body.= "Message: ";
    $mail->Body.= $_POST["message"];
    $mail->Body.= "<br>";
    $mail->AltBody = str_replace("<br>",", ",$mail->Body);
    $mail->send();
    echo_result("success");
} catch (Exception $e) {
    echo_result("Sorry, something went wrong! Err: {$mail->ErrorInfo}");
}

function echo_result($str){
    echo $str;
    exit;
}