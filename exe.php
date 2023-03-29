<?php
// use PHPMailer\PHPMailer\PHPMailer;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load PHPMailer library
// require_once './PHPMailer-master/src/PHPMailer.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once './PHPMailer-master/src/Exception.php';
require_once './PHPMailer-master/src/PHPMailer.php';
require_once './PHPMailer-master/src/SMTP.php';

// Create a new PHPMailer instance
// $mail = new PHPMailer();

// Configure Mailtrap.io SMTP settings
$phpmailer = new PHPMailer();
$phpmailer->isSMTP();
$phpmailer->Host = 'sandbox.smtp.mailtrap.io';
$phpmailer->SMTPAuth = true;
$phpmailer->Port = 2525;
$phpmailer->Username = '15e4cd121528e0';
$phpmailer->Password = 'c354f71b5aeed4';

// Set the 'From' and 'Reply-To' addresses
$phpmailer->setFrom('hmcdonald47@rrc.ca', 'Hylton Test');
$phpmailer->addReplyTo('your-email-address@example.com', 'Your Name');

// Set the 'To' address
$phpmailer->addAddress('goregehylton@yahoo.com', 'Your Name');

// Set the email subject and message
$phpmailer->Subject = 'Registration Confirmation';
$phpmailer->Body = 'Thank you for registering.';

// Send the email
if (!$phpmailer->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}




// require("connect.php");
// $salt = bin2hex(random_bytes(16));
// $password = password_hash('gorgonzola7!' . $salt, PASSWORD_DEFAULT);
// $query = "SELECT part  FROM Comments";
// $stmt = $db->prepare($query);
// // $stmt->bindValue(':password', $password);
// // $stmt->bindValue(':salt', $salt);

// $result = $stmt->execute();

// echo $result;

// print_r($stmt->fetch());
?>