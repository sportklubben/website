<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	if (isset($_POST['submit'])) {

		//Create random string
		function rand_string( $length ) {
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	

			$size = strlen( $chars );
			for( $i = 0; $i < $length; $i++ ) {
				$str .= $chars[ rand( 0, $size - 1 ) ];
			}

			return $str;
		}
		$my_string = rand_string( 10 );

		//Variables
		$UserName = $_POST['UserName'];
		$UserEmail = $_POST['UserEmail'];
		$EventID = $_POST['EventID'];
		$SpotNumber = $_POST['SpotNumber'];
		$SignUpDate = date('Y-m-d H:i:s');
		$PhoneNumber = $_POST['PhoneNumber'];
		$EventName = $_POST['EventName'];
		$AvailableSpots = $_POST['AvailableSpots'];
		$RandomKey = $my_string;

		// Create connection
		$con = mysqli_connect("localhost", "root", "password");
		mysqli_connect("localhost", "root", "password") or die("not connected");
		mysqli_select_db($con, "sportklubben") or die("no db found");

		$query = "INSERT INTO EventSignUp (UserName, UserEmail, EventID, SpotNumber, SignUpDate, PhoneNumber, RandomKey) VALUES ('$UserName', '$UserEmail', '$EventID', '$SpotNumber', '$SignUpDate', '$PhoneNumber', '$RandomKey')";
		if (!mysqli_query($con,$query)){
			echo("Error description: " . mysqli_error($con));
		}
		// Import PHPMailer classes into the global namespace
		// These must be at the top of your script, not inside a function
		//use PHPMailer\src\PHPMailer;
		//use PHPMailer\src\Exception;

		//Load Composer's autoloader

		require_once './vendor/autoload.php';
		require 'phpmailer/Exception.php';
		require 'phpmailer/PHPMailer.php';
		require 'phpmailer/SMTP.php';
		require 'phpmailer/OAuth.php';

		$mail = new PHPMailer\PHPMailer\PHPMailer(true); // Passing `true` enables exceptions
		try {
		    //Server settings
		    //$mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
		    //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
		    $mail->isSMTP();                                      // Set mailer to use SMTP
		    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		    $mail->SMTPAuth = true;                              // Enable SMTP authentication

		    $mail->SMTPSecure = 'tls';  
		    $mail->Port = 587;   
		    $mail->Username = 'sportklubben.stockholm@gmail.com';                 // SMTP username
		    $mail->Password = 'Sportklubben2017';                           // SMTP password
		    //Recipients
		    $mail->setFrom('sportklubben.stockholm@gmail.com', 'Capgemini Sportklubben Stockholm');
		    $mail->addAddress($UserEmail, $UserName);     // Add a recipient
		    $mail->addReplyTo('sportklubben.stockholm@gmail.com', 'Capgemini Sportklubben Stockholm');

		    //Attachments
		    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		    //Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = "Booking confirmation for $EventName event";
		    if ($SpotNumber<=$AvailableSpots) {
			    $mail->Body    = "<h1>Congratulations, your spot has been booked!</h1><p>Your spot for the $EventName event has been booked. If you would like to cancel your booking, click this link: <a href='http://localhost:8888/cancelbooking.php?Key=$RandomKey'>Cancel booking</a>. <br><br> If you don't intend to attend the event, please make sure to cancel your booking as soon as possible so that others can sign up for the event.<br><br>Best regards,<br>Sportklubben Team<br>sportklubben.stockholm@gmail.com</p>";
			    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
			}
			else {
				$BackUpSpot = (int)$SpotNumber - (int)$AvailableSpots;
				$mail->Body    = "<h1>You have been placed as number $BackUpSpot on the back-up list for the $EventName event</h1><p>At the moment, all of the spots for the $EventName event are booked. You have been placed as number $BackUpSpot on the back-up list. If you want to cancel your booking, click this link: <a href='http://localhost:8888/cancelbooking.php?Key=$RandomKey'>Cancel booking</a>.<br><br>If anyone with a confirmed event spot cancels their booking, you will be moved up on the back-up list and we will inform you via email. If you are placed first on the back-up list you will be handed a spot for the event.<br><br>Best regards,<br>Sportklubben Team<br>sportklubben.stockholm@gmail.com</p>";
			    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
			}
		    $mail->send();
		    echo 'Message has been sent';
		} catch (Exception $e) {
		    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
	}
	header("Location: index.php"); /* Redirect browser */
	exit();
?>


