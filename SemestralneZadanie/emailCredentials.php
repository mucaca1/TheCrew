<?php
session_start();
include_once "config.php";  //include database. Use $conn.
ini_set('display_errors', 1);
include_once("csvReader.php");
//include_once("sendMail.php");

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';



$language = "sk";
if(isset($_SESSION['language'])){
    $language = $_SESSION['language'];
}

if(isset($_GET['language'])){
    $_SESSION['language'] = $_GET['language'];
    $language = $_GET['language'];
}

if(isset($_SESSION['csvData'])){    
	
	// Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);
	$mail->SMTPKeepAlive = true;
		
    $csvArray = $_SESSION['csvData'];
	try {
		//Server settings
		//$mail->SMTPDebug = 2;                                       // Enable verbose debug output
		$mail->isSMTP();                                            // Set mailer to use SMTP
		$mail->Host       = 'mail.stuba.sk';  // Specify main and backup SMTP servers
		$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		$mail->Username   = $_SESSION["smtp_username"];                     // SMTP username
		$mail->Password   = $_SESSION["smtp_password"];                               // SMTP password
		$mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
		$mail->Port       = 587;                                    // TCP port to connect to

		//Recipients
		$mail->setFrom($_SESSION["smtp_from_email"], $_SESSION["smtp_from_name"]);
		//$mail->addAddress('ellen@example.com');               // Name is optional
		$mail->addReplyTo($_SESSION["smtp_from_email"], $_SESSION["smtp_from_name"]);
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');
		// Attachments
		if (isset($_SESSION["attachmentFilePath"]) && $_SESSION["attachmentFilePath"] != '')
		{
			$mail->addAttachment($_SESSION["attachmentFilePath"]);         // Add attachments
		}
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		// Content
		$mail->isHTML($_SESSION["text_type"]);                                  // Set email format to HTML
		$mail->Subject = $_SESSION["smtp_subject"];

		$array_what_to_replace = '';
		$i = 0;
		foreach($csvArray as &$array_to_replace_with)
		{
			if($i == 0)
				$array_what_to_replace = $array_to_replace_with;
			else
			{
				//echo $_SESSION["template_id"];
				$sql9 = "SELECT TEMPLATE FROM mail_template WHERE ID='" . $_SESSION["template_id"]."'";
				//echo $sql9;
				$result9 = $conn->query($sql9);
				$template_text = '';
				while ($row9 = $result9->fetch_assoc()){
					$template_text = $row9['TEMPLATE'];
				}
				//echo $template_text;
				
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
				
				
					$mail->addAddress($email_to, $email_to_name);     // Add a recipient

					$mail->Body    = $email_text;
					//$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
					//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

					$mail->send();
					$mail->ClearAddresses();
					
					//echo 'Messages has been sent';
					//echo $email_to_name;
					$sql18 = "INSERT INTO mail_log (sent, name, subject, template_id) VALUES ('".date('Y-m-d')."','".$email_to_name."', '".$_SESSION["smtp_subject"]."',".$_SESSION["template_id"].")";
					//echo $sql18;
					if ($conn->query($sql18) === TRUE) {
						//echo "New record created successfully";
					} else {
						echo "Error: " . $sql . "<br>" . $conn->error;
					}
				
				//sendEmail(,,,,,,,,,$array_what_to_replace,$array_to_replace_with,$conn);
				//function sendEmail($smtp_username,$smtp_password,$smtp_from_email,$smtp_from_name,$smtp_subject,$smtp_attachment,$smtp_method,$template_text,$array_what_to_replace,$array_to_replace_with)
			}
			$i++;
		}
		echo 'Messages have been sent';
		$mail->SmtpClose();
		//exportuj do CSV
		//arrayToCSVDownload($csvArray,"logins.csv",$dlm);
	} catch (Exception $e) {
				echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
}
?>