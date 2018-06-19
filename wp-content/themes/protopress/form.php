<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<form name="signup" method="post" action="signup.php" id="myform">
	<div class="form-group">
		<p>Name</p>
		<input type="text" name="UserName" class="form-control" value="" pattern=".{3,}" required title="Please enter your full name" placeholder="e.g. Wayne Gretzky">
	</div>
	<div class="form-group">
		<p>Email</p>
		<input type="email" name="UserEmail" class="form-control" value="" required pattern="^[a-zA-Z0-9_.+-]+@(?:(?:[a-zA-Z0-9-]+\.)?[a-zA-Z]+\.)?(capgemini|sogeti)\.?(com|se)$" title="Please provide a Capgemini or Sogeti email address" placeholder="e.g. wayne.gretzky@capgemini.com">
	</div>
	<div class="form-group">
		<p>Phone Number</p>
		<input type="text" name="PhoneNumber" class="form-control" value="" pattern=".{10,}" required title="Please enter your phone number with the format 07X XXX XX XX" placeholder="e.g. 079 999 99 99">
	</div>
	<div class="form-group" <?php $OtherInfo = get_field('other_information'); if ($OtherInfo ==""){echo 'style="display: none;"';} ?>>
		<p>Other information: <?php echo $OtherInfo; ?></p>
		<input name="OtherInfo" <?php if ($OtherInfo ==""){echo 'type="hidden"';} ?> class="form-control" value="" pattern=".{1,}" required title="Please enter the requested information" placeholder="" style="min-width:100%;max-width: 100%;max-height: 150px;"></textarea>
	</div>
	<div>
		<!--<p>Spot Number</p>-->
		<?php 
			$con = mysqli_connect("localhost", "root", "password");
			//mysqli_connect("localhost", "root", "password") or die("not connected");
			mysqli_select_db($con, "sportklubben") or die("no db found");
			$postID = get_the_id();
			$query = "SELECT EventID, COUNT(*) AS EventSpot FROM EventSignUp WHERE EventID = $postID";
			if (!mysqli_query($con,$query)){
				echo("Error description: " . mysqli_error($con));
			}
			$result = mysqli_query ($con,$query);
			$row = mysqli_fetch_assoc($result);
		?>
		<input type="hidden" name="SpotNumber" value="<?php echo $row['EventSpot']+1; ?>">
		<input type="hidden" name="EventID" value="<?php the_id(); ?>">
		<input type="hidden" name="EventName" value="<?php the_field('event_name'); ?>">
		<input type="hidden" name="AvailableSpots" value="<?php the_field('available_spots'); ?>">
		<input type="hidden" name="EventResponsible" value="<?php the_field('event_responsible'); ?>">
		<input type="hidden" name="EventResponsibleEmail" value="<?php the_field('event_responsible_email'); ?>">
		<input type="hidden" name="EventStartTime" value="<?php the_field('event_start_time'); ?>">
		<?php $time = strtotime(get_field('event_start_date')); ?>
		<input type="hidden" name="EventStartDate" value="<?php echo date('j',$time) . " "; echo date('M',$time) . " "; echo date('Y',$time);?>">
		<input type="hidden" name="EventLocation" value="<?php the_field('event_location'); ?>">

	</div>
	<div class="modal-button-container">
		<button type="button" class="btn btn-secondary" id="closeModal" data-dismiss="modal">Close</button>
		<input type="submit" value="Submit" class="btn btn-primary" name ="submit">
	</div>
</form>


