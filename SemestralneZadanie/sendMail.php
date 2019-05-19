<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
//$mail = new PHPMailer(true);

$email_template_text = 'Dobrý deň,
na predmete Webové technológie 2 budete mať k dispozícii vlastný virtuálny linux server, ktorý budete
používať počas semestra, a na ktorom budete vypracovávať zadania. Prihlasovacie údaje k Vašemu serveru
su uvedené nižšie.
ip adresa: {{verejnaIP}}
prihlasovacie meno: {{login}}
heslo: {{heslo}}
Vaše web stránky budú dostupné na: http:// {{verejnaIP}}:{{http}}
S pozdravom,
{{sender}}';

$tag_public_ip = '{{verejnaIP}}';
$tag_login = '{{login}}';
$tag_pass = '{{heslo}}';
$tag_http_port = '{{http}}';
$tag_sender = '{{sender}}';

//$email_text = str_replace($tag_public_ip,'192.168.1.1',$email_template_text);
//$hlavicka = array('verejnaIP','login');
//$data = array('9.5.2.6','xlendac');
//sendEmail('xlendac@stuba.sk','heslo','xlendac@stuba.sk','Feri','Subject','','html',$email_template_text,$hlavicka,$data);
function sendEmail($smtp_username,$smtp_password,$smtp_from_email,$smtp_from_name,$smtp_subject,$smtp_attachment,$smtp_text_type_is_html,$template_id,$template_text,$array_what_to_replace,$array_to_replace_with,$conn)
{
	$mail = new PHPMailer(true);
	/*
	echo $smtp_username;
	echo $smtp_password;
	echo $smtp_subject;
	//echo $smtp_attachment;
	echo $smtp_method;
	echo "<br>";
	echo $template_text;
	print_r($array_what_to_replace);
	echo "<br>";
	print_r($array_to_replace_with);
	echo "<br>";
	echo "<br>";
	*/
	
	$email_to = '';
	$email_to_name = '';
	$email_text = $template_text;
	
	$i=0;
	foreach($array_what_to_replace as $tag) //replace all the tags for their data
    {
		$email_text = str_replace('{{'.$tag.'}}',$array_to_replace_with[$i],$email_text);
		if($tag == 'Email')
		{
			$email_to = $array_to_replace_with[$i]; //gimme email
		}
		if($tag == 'meno')
		{
			$email_to_name = $array_to_replace_with[$i]; //gimme meno
		}
		$i++;
	}
	$email_text = str_replace('{{sender_email}}',$smtp_from_email,$email_text);
	$email_text = str_replace('{{sender_name}}',$smtp_from_name,$email_text);
	//echo $email_text;
	
	try {
		//Server settings
		$mail->SMTPDebug = 2;                                       // Enable verbose debug output
		$mail->isSMTP();                                            // Set mailer to use SMTP
		$mail->Host       = 'mail.stuba.sk';  // Specify main and backup SMTP servers
		$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		$mail->Username   = $smtp_username;                     // SMTP username
		$mail->Password   = $smtp_password;                               // SMTP password
		$mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
		$mail->Port       = 587;                                    // TCP port to connect to

		//Recipients
		$mail->setFrom($smtp_from_email, $smtp_from_name);
		$mail->addAddress($email_to, $email_to_name);     // Add a recipient
		//$mail->addAddress('ellen@example.com');               // Name is optional
		$mail->addReplyTo($smtp_from_email, $smtp_from_name);
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');

		// Attachments
		if (isset($smtp_attachment) && $smtp_attachment != '')
		{
			$mail->addAttachment($smtp_attachment);         // Add attachments
		}
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		// Content
		$mail->isHTML($smtp_text_type_is_html);                                  // Set email format to HTML
		$mail->Subject = $smtp_subject;
		$mail->Body    = $email_text;
		//$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		$mail->send();
		echo 'Message has been sent';
		echo $email_to_name;
		$sql18 = "INSERT INTO mail_log (sent, name, subject, template_id) VALUES ('".date('Y-m-d')."','".$email_to_name."', '".$smtp_subject."',".$template_id.")";
		echo $sql18;
		if ($conn->query($sql18) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
	
}