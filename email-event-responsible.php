<?php
	$EventResponsible = get_field('event_responsible');
	$EventResponsibleEmail = get_field('event_responsible_email');
	$mail = new PHPMailer\PHPMailer\PHPMailer(true); // Passing `true` enables exceptions
		try {
		    
		    $mail->addAddress($EventResponsibleEmail, $EventResponsible);     // Add a recipient
		    $mail->addReplyTo('sportklubben.stockholm@gmail.com', 'Capgemini Sportklubben Stockholm');

		    //Attachments
		    //$mail->addAttachment('/var/tmp/file.tar.gz'); // Add attachments
		    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name

		    $query = "SELECT EventID,UserName,UserEmail,SpotNumber,SignUpDate,PhoneNumber,OtherInfo FROM EventSignUp WHERE EventID = $postID";
			if (!mysqli_query($con,$query)){
				echo("Error description: " . mysqli_error($con));
			}
			$result = mysqli_query ($con,$query);
			$participants = mysqli_fetch_assoc($result);



		    //Content
		    $mail->Subject = "Updated participant list for $EventName event";
		    $mail->Body    = "<h1>The participant list has been updated</h1><h2>Participants:</h2>
		    <table style="width:100%">
				  <tr>
					    <th>Spot Number</th>
					    <th>Name</th> 
					    <th>Email</th>
					    <th>Signup Date</th>
					    <th>Phone Number</th>
					    <th>Other Information</th>
				  </tr>
				  <tr>
					    <td>Jill</td>
					    <td>Smith</td> 
					    <td>50</td>
				  </tr>
				  <tr>
					    <td>Eve</td>
					    <td>Jackson</td> 
					    <td>94</td>
				  </tr>
			    foreach ($participants as $participant){
			    	<tr>
				    	<td>echo $participant['$SpotNumber'];</td>
				    	<td>echo $participant['$UserName'];</td>
				    	<td>echo $participant['$UserEmail'];</td>
				    	<td>echo $participant['$SignUpDate'];</td>
				    	<td>echo $participant['$PhoneNumber'];</td>
				    	<td>echo $participant['$OtherInfo'];</td>
			    	</tr>
			    }
			</table>";
		    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		    $mail->send();
?>