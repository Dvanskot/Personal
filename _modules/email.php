<?php
//THIS CLASS SENDS EMAILS TO CUSTOMERS AND ADMIN
//require_once("settings.php");

class Email {
	
	public function sendMail($email, $subject, $message, $from = EMAIL_SYSTEM) {
		mail($email , $subject, $message, "From: \"KGL Book Store\" <$from>\r\n". 
		"Reply-To: ".EMAIL_NOTIFY."\r\n" . 
		"X-Mailer: PHP/" . phpversion());
	}
}

?>