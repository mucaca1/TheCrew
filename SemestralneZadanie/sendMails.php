<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 2;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
	$mail->SMTPKeepAlive = true;
    $mail->Host       = 'mail.stuba.sk';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'xlendac@stuba.sk';                     // SMTP username
    $mail->Password   = 'Daubiconsistam6477107';                               // SMTP password
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('xlendac@stuba.sk', 'Mailer');
    
    //$mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo('xlendac@stuba.sk', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    // Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
	for ($x = 0; $x <= 10; $x++) {
		$mail->Body    = $x. 'This is the HTML message body <b>in bold!</b> '. $x;
		$mail->addAddress('lubos.len@gmail.com', 'Joe User');     // Add a recipient
		$mail->send();
		$mail->ClearAddresses();
	}
	$mail->SmtpClose();
    echo 'Messages has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}