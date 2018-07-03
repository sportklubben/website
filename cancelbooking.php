<?php
	//debug messages
	$deleteKey = $_GET["Key"];
	//echo ('deleteKey = ' . $deleteKey . '<br>');
	//$availableSpots = $_GET["Available"];
	//echo 'Available Spots = ' . $availableSpots . '<br>';
	$EventResponsible = $_GET["EventResponsible"];
	$EventResponsibleEmail = $_GET["EventResponsibleEmail"];
	$EventName = $_GET["EventName"];

	// Create connection
	$con = mysqli_connect("localhost", "root", "password");
	mysqli_connect("localhost", "root", "password") or die("not connected");
	mysqli_select_db($con, "sportklubben") or die("no db found");

	//Before deleting: get the spot number for the booking and move up all bookings that have a higher spot number. Then send an email to everyone with the booking that has been updated. (NEEDS TO BE ADDED)

	//Get the spot number for the booking
	$query = "SELECT SpotNumber,EventID,AvailableSpots FROM EventSignUp WHERE RandomKey = '$deleteKey'";	
	if (!mysqli_query($con,$query)){
		echo("Error description: " . mysqli_error($con));
	}
	$result = mysqli_query ($con,$query);
	$row = mysqli_fetch_assoc($result);
	$SpotNumber = (int)$row['SpotNumber'];
	$EventID = (int) $row['EventID'];
	$availableSpots = (int) $row['AvailableSpots'];
	//echo ('the spot number for this booking is: ' . $SpotNumber . '<br>');

	//Change spot for everyone with spotnumber higher than deleted booking
	$query = "SELECT SpotNumber,UserID,UserEmail,UserName,RandomKey FROM EventSignUp WHERE EventID = $EventID AND SpotNumber > $SpotNumber";	
	if (!mysqli_query($con,$query)){
		echo("Error description: " . mysqli_error($con));
	}
	$result = mysqli_query ($con,$query);
	$row = mysqli_fetch_all($result, MYSQLI_ASSOC);
	if ($SpotNumber > 0){
		//echo ('the number of rows in this array is: ' . count($row) . '<br>');
		for ($i=0; $i < (count($row)); $i++) {
			$SpotNumberOld = $row[$i]['SpotNumber'];
			$SpotNumberNew = (int)$row[$i]['SpotNumber'] - 1;
			$query = "UPDATE EventSignUp SET SpotNumber=$SpotNumberNew WHERE SpotNumber=$SpotNumberOld AND EventID=$EventID";
			if (!mysqli_query($con,$query)){
				echo("Error description: " . mysqli_error($con));
			};
			//Send email to people on the back-up list
			if ($SpotNumberOld > $availableSpots){
				//Set variables
				$RandomKey = $row[$i]['RandomKey'];
				$UserName = $row[$i]['UserName'];
				$UserEmail = $row[$i]['UserEmail'];
				//Send email
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
				    $mail->isHTML(true); // Set email format to HTML
				    //Send special email to person who now has original spot on list
					if ($SpotNumberNew == $availableSpots){
						$mail->Subject = "You now have a spot on the $EventName event!";
						$mail->Body    = "<h1>Congratulations, you now have a spot on the $EventName event!</h1><p>Someone has canceled their booking and you now have a spot on the event. If you would like to cancel your booking, click this link: <a href='http://localhost:8888?Status=Deleted&Key=$RandomKey&Available=$AvailableSpots&EventResponsible=$EventResponsible&EventResponsibleEmail=$EventResponsibleEmail&EventName=$EventName'>Cancel booking</a>. <br><br> If you don't intend to attend the event, please make sure to cancel your booking as soon as possible so that others can sign up for the event.<br><br>Best regards,<br>Sportklubben Team<br>sportklubben.stockholm@gmail.com</p>";
					    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
					    $mail->send();
					} else { //Send email to everyone else up backup-list that they have moved up
						$BackupNumber = (int)$SpotNumberNew - (int)$availableSpots;
						$mail->Subject = "You been moved up on the backup-list for the $EventName event!";
						$mail->Body    = "<h1>Congratulations, you have been moved up on the backup-list for the $EventName event!</h1><p>Someone has canceled their booking and you are now number $BackupNumber on the backup-list. If you would like to cancel your booking, click this link: <a href='http://localhost:8888?Status=Deleted&Key=$RandomKey&Available=$AvailableSpots&EventResponsible=$EventResponsible&EventResponsibleEmail=$EventResponsibleEmail&EventName=$EventName'>Cancel booking</a>. <br><br> If you don't intend to attend the event, please make sure to cancel your booking as soon as possible so that others can sign up for the event.<br><br>Best regards,<br>Sportklubben Team<br>sportklubben.stockholm@gmail.com</p>";
					    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
					    $mail->send();
					}
				}
				catch (Exception $e) {
				    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
				}
			}
		} 
		//Delete booking
		$query = "DELETE FROM EventSignUp WHERE RandomKey = '$deleteKey'";
		if (!mysqli_query($con,$query)){
			echo("Error description: " . mysqli_error($con));
		}
		else{$deleteStatus = true;

			//Email event responsible
			//Load Composer's autoloader and required files for PHPMailer
			require_once './vendor/autoload.php';
			require 'phpmailer/Exception.php';
			require 'phpmailer/PHPMailer.php';
			require 'phpmailer/SMTP.php';
			require 'phpmailer/OAuth.php';
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
		};
	} else {
		$deleteStatus = false;
	}
	unset ($status);
?>
<script type="text/javascript">
	$(window).load(function(){        
	$('#ConfirmModal').modal('show');
	});
</script>
<!-- Modal -->
<div class="modal fade" id=ConfirmModal tabindex="-1" role="dialog" aria-labelledby="ConfirmModal" aria-hidden="true" style="display:initial;opacity: 1;padding-top:250px;background-color: rgba(0, 0, 0, 0.5);">
	<div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content" style="box-shadow: none; border: none;">
		    <!--display this modal if success-->
		    <?php if($deleteStatus == true):?>
			    <div class="modal-header" style="background: #489665;">
			        <h2 class="modal-title" id="exampleModalLongTitle" style="color: white;">
			        	Booking Deleted
			        </h2>
			    </div>
			    <div class="modal-body">
			      	<div class="modal-body-article" style="display: flex; justify-content: center;">
						<p class="fa fa-check" style="font-size:30px;color:#489665;flex:0 1 20%;align-self: center;margin: 0;text-align: center;"></p>
						<p style="text-align: left; margin: 30px 0;">Your booking has been deleted.</p>
	                </div>
	            </div>
        	<?php else:?>
        		<div class="modal-header" style="background: #dc3030;">
			        <h2 class="modal-title" id="exampleModalLongTitle" style="color: white;">
			        	Error
			        </h2>
			    </div>
			    <div class="modal-body">
			      	<div class="modal-body-article" style="display: flex; justify-content: center;">
						<p class="fa fa-times" style="font-size:30px;color:#dc3030;flex:0 1 20%;align-self: center;margin: 0;text-align: center;"></p>
						<p style="text-align: left; margin: 30px 0;">There is no booking associated with this link. Please contact sportklubben.stockholm@gmail.com or the event responsible.</p>
	                </div>
	            </div>
        	<?php endif; ?>
		    </div>
		    <form action="index.php" id="myform">
			    <input type="submit" value="CLOSE" class="confirm-close">
			</form>
		</div>
	</div>
</div>
<!-- END MODAL -->
