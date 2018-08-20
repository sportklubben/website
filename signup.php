
<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	if (isset($_POST['submit'])) {

		//Create random string
		function generateRandomString($length = 10) {
		    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
		    return $randomString;
		}

		$my_string = generateRandomString();

		//Variables
		$UserName = $_POST['UserName'];
		$UserEmail = $_POST['UserEmail'];
		$EventID = $_POST['EventID'];
		$SpotNumber = $_POST['SpotNumber'];
		$SignUpDate = date('Y-m-d H:i:s');
		$PhoneNumber = $_POST['PhoneNumber'];
		$PhonePref = substr($PhoneNumber, 0,2);
		if ($PhonePref == "+4"){
			$PhoneNumber = "0" . substr($PhoneNumber, -9,9);
		}
		$EventName = $_POST['EventName'];
		$AvailableSpots = $_POST['AvailableSpots'];
		$RandomKey = $my_string;
		$OtherInfoValue = $_POST['OtherInfo'];
		$EventResponsible = $_POST['EventResponsible'];
		$EventResponsibleEmail = $_POST['EventResponsibleEmail'];
		$EventStartDate = $_POST['EventStartDate'];
		$EventStartTime = $_POST['EventStartTime'];
		$EventLocation = $_POST['EventLocation'];

		// Create connection
		$con = mysqli_connect("localhost", "root", "password");
		mysqli_connect("localhost", "root", "password") or die("not connected");
		mysqli_select_db($con, "sportklubben") or die("no db found");

		//Check if booking already exists for user and event
		//ADD CODE!

		//Check if user already has booked the event
		$query = "SELECT UserEmail, EventID FROM EventSignUp WHERE UserEmail = '$UserEmail' AND EventID = $EventID";	
	    if (!$query){
	        die('Error: ' . mysqli_error($con));
	    }
	    $result = mysqli_query ($con,$query);
	    if(mysqli_num_rows($result) > 0){
			header("Location: index.php" . "?Status=Existing"); /* Redirect browser */
			exit();
		}else{
			
			//Insert booking into database
			$query = "INSERT INTO EventSignUp (UserName, UserEmail, EventID, SpotNumber, SignUpDate, PhoneNumber, RandomKey, OtherInfo, AvailableSpots) VALUES ('$UserName', '$UserEmail', '$EventID', '$SpotNumber', '$SignUpDate', '$PhoneNumber', '$RandomKey', '$OtherInfoValue', '$AvailableSpots')";
			if (!mysqli_query($con,$query)){
				echo("Error description: " . mysqli_error($con));
			}

			//Load Composer's autoloader and required files for PHPMailer
			require_once './vendor/autoload.php';
			require 'phpmailer/Exception.php';
			require 'phpmailer/PHPMailer.php';
			require 'phpmailer/SMTP.php';
			require 'phpmailer/OAuth.php';

			$mail = new PHPMailer\PHPMailer\PHPMailer(true); // Passing `true` enables exceptions
			try {
			    //Server settings
			    //$mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
			    //$mail->SMTPDebug = 2; // Enable verbose debug output
			    $mail->isSMTP(); // Set mailer to use SMTP
			    $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
			    $mail->SMTPAuth = true; // Enable SMTP authentication

			    $mail->SMTPSecure = 'tls';  
			    $mail->Port = 587;   
			    $mail->Username = 'sportklubben.stockholm@gmail.com'; // SMTP username
			    $mail->Password = 'Sportklubben2017'; // SMTP password
			    //Recipients
			    $mail->setFrom('sportklubben.stockholm@gmail.com', 'Capgemini Sportklubben Stockholm');
			    $mail->addAddress($UserEmail, $UserName);     // Add a recipient
			    $mail->addReplyTo('sportklubben.stockholm@gmail.com', 'Capgemini Sportklubben Stockholm');

			    //Attachments
			    //$mail->addAttachment('/var/tmp/file.tar.gz'); // Add attachments
			    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name

			    //Content
			    $mail->isHTML(true); // Set email format to HTML
			    $mail->CharSet = 'UTF-8';
			    $mail->Subject = "Booking confirmation for $EventName event";
			    $actual_link = "http://$_SERVER[HTTP_HOST]";

			    //Email for normal spot
			    if ($SpotNumber<=$AvailableSpots) {
				    $mail->Body    = "
				    	<style>
			    			h3{
			    				margin-bottom:0px;
			    			}
			    			p{
			    				margin-top:0px;
			    			}
			    		</style>
				    	<h1>
				    		Congratulations, your spot has been booked!
				    	</h1>
				    	<p>
				    		Your spot for the $EventName event has been booked. If you would like to cancel your booking, <a href='$actual_link?Status=Deleted&Key=$RandomKey&EventResponsible=$EventResponsible&EventResponsibleEmail=$EventResponsibleEmail&EventName=$EventName'>Click here</a>. If you don't intend to attend the event, please make sure to cancel your booking as soon as possible so that others can sign up for the event. If you have any questions, contact $EventResponsible at $EventResponsibleEmail.
				    	</p>
			    		<h3>
			    			Event Information
		    			</h3>
		    			<p>
		    				Time: $EventStartDate $EventStartTime<br>Location: $EventLocation <br><br> Stay healthy,<br>Sportklubben Team<br>sportklubben.stockholm@gmail.com
						</p>
						";
				    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
				}

				//Email for backup-spot
				else {
					$BackUpSpot = (int)$SpotNumber - (int)$AvailableSpots;
					$mail->Body    = "
						<style>
			    			h3{
			    				margin-bottom:0px;
			    			}
			    			p{
			    				margin-top:0px;
			    			}
			    		</style>
			    		<h1>
			    			You have been placed as number $BackUpSpot on the back-up list for the $EventName event
		    			</h1>
		    			<p>
		    				At the moment, all of the spots for the $EventName event are booked. You have been placed as number $BackUpSpot on the back-up list. If you want to cancel your booking, click this link: <a href='$actual_link?Status=Deleted&Key=$RandomKey&EventResponsible=$EventResponsible&EventResponsibleEmail=$EventResponsibleEmail&EventName=$EventName'>Cancel booking</a>.<br><br>If anyone with a confirmed event spot cancels their booking, you will be moved up on the back-up list and we will inform you via email. If you are placed first on the back-up list you will be handed a spot for the event. If you have any questions, contact $EventResponsible at $EventResponsibleEmail.
		    			</p>
		    			<h3>
			    			Event Information
		    			</h3>
		    			<p>
		    				Time: $EventStartDate $EventStartTime<br>Location: $EventLocation <br><br> Stay healthy,<br>Sportklubben Team<br>sportklubben.stockholm@gmail.com
						</p>
						";
				    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
				}
			    $mail->send();
			}
			catch (Exception $e) {
			    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
			}


			

			//Email event responsible
			unset($mail);
			$mail = new PHPMailer\PHPMailer\PHPMailer(true); // Passing `true` enables exceptions
			try {
			    //Server settings
			    //$mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
			    //$mail->SMTPDebug = 2; // Enable verbose debug output
			    $mail->isSMTP(); // Set mailer to use SMTP
			    $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
			    $mail->SMTPAuth = true; // Enable SMTP authentication

			    $mail->SMTPSecure = 'tls';  
			    $mail->Port = 587;   
			    $mail->Username = 'sportklubben.stockholm@gmail.com'; // SMTP username
			    $mail->Password = 'Sportklubben2017'; // SMTP password
			    //Recipients
			    $mail->setFrom('sportklubben.stockholm@gmail.com', 'Capgemini Sportklubben Stockholm');
			    $mail->addAddress($EventResponsibleEmail, $EventResponsible);     // Add a recipient
			    $mail->addReplyTo('sportklubben.stockholm@gmail.com', 'Capgemini Sportklubben Stockholm');

			    //Attachments
			    //$mail->addAttachment('/var/tmp/file.tar.gz'); // Add attachments
			    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name

			    //Content
			    $mail->CharSet = 'UTF-8';
			    $query = "SELECT EventID,UserName,UserEmail,SpotNumber,SignUpDate,PhoneNumber,OtherInfo FROM EventSignUp WHERE EventID = $EventID";
				if (!mysqli_query($con,$query)){
					echo("Error description: " . mysqli_error($con));
				}
				
				$result = mysqli_query ($con,$query);
			    $mail->isHTML(true); // Set email format to HTML
			    $mail->Subject = "Updated participant list";

			    $body = "<style>
			    			th{
			    				text-align:left;
			    				padding-right:20px;
			    				font-weight:normal;
			    			}
			    		</style>
			    		<h3>You are recieving this email since you are the event responsible for the $EventName event. The participant list for the $EventName event has been updated. Here's the updated list:</h3>
			    		<table>
			    			<tr>
			    				<th>Spot Number</th>
			    				<th>Name</th>
			    				<th>Email</th>
			    				<th>Phone Number</th>
			    				<th>Other Info</th>
		    				</tr>";

			    while($row = mysqli_fetch_array($result)){
					$body = $body . "<tr><th>" . $row['SpotNumber']. "</th><th>". $row['UserName'] . "</th><th>" . $row['UserEmail'] . "</th><th>" . $row['PhoneNumber'] . "</th><th>" . $row['OtherInfo'] . "</th></tr>";
				}
				$body = $body . "</table>";
			    
			    $mail->Body = "$body";

			    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
			    $mail->send();
			}
			catch (Exception $e) {
			    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
			}

		}
	} //END IF POST SUBMIT

	header("Location: index.php" . "?Status=Confirmed"); /* Redirect browser */
	exit();
?>