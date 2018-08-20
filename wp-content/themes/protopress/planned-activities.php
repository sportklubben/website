<?php if ( get_theme_mod('protopress_box_enable') && is_front_page() ) : ?>

<!-- Loader -->
<div id="pageloader">
   <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
</div>

<!--Planned activites-->
<div class="featured-2">
	<div class="container">
		<div class="popular-articles col-md-12">

			<!-- Check URL status -->
			<?php
				$status = $_GET['Status'];
				//Check if confirm box should be displayed
				if ($status == 'Confirmed'){ 
					get_template_part('confirm');
				}
				//Check if delete box should be displayed
				elseif ($status == 'Deleted'){
					get_template_part('../../cancelbooking');
				}
				//Check if existing booking box should be displayed
				elseif ($status == 'Existing'){
					get_template_part('../../existingbooking');
				}
				else {
					//Do nothing
				}
			?>

			<!-- Planned Activites -->
			<div class="section-title">
				<h2>Planned Activities</h2>
			</div>
			<?php /* Start the Loop */ $count=0; ?>
			<?php
	    		$args = array(
		        	'numberposts' => -1,
					'post_status' => 'publish',
					'meta_key' => 'event_start_date',
					'orderby' => 'meta_value_num',
					'order' => 'ASC'
				);
				$lastposts = get_posts( $args );
				foreach ( $lastposts as $post ) :
			  	setup_postdata( $post ); 
			  	$eventDate = get_field('event_start_date');
			  	$EventName = get_field('event_name');
			  	date_default_timezone_set('Sweden/Stockholm');
			  	$date = date('Ymd');

			  	//Get categories for the event
			  	$allCatMeta = get_the_category($id);
			  	$allCat = (array_column($allCatMeta,'cat_name'));
			  	$continue = false;
			  	foreach ($allCat as $cat) {
			  		if ($cat != 'Event'){
			  			$continue = true;
			  		}
			  	}
			  	if ($eventDate < $date OR $continue == true){
			  		continue;
			  	}
		  	?>
	  		<div>

				<!-- MODAL -->

				<!-- Check if signup is open for event -->
				<?php
					$today = date("Ymd");
					$signUpOpen = get_field('sign-up_open_date');
					if ($today < $signUpOpen){
						$signup = false;
					}
					else{
						$signup = true;
					}
					$daysLeft = $signUpOpen - $today;
    				if ($daysLeft>1){
    					$dayPlural = ' days';
    				}
    				else{
    					$dayPlural = ' day';
    				}
				?>

				<div class="modal fade" id=<?php echo '"eventModal'; the_ID(); echo '"';?> tabindex="-1" role="dialog" aria-labelledby=<?php echo '"#eventModalTitle'; echo $count; echo '"';?> aria-hidden="true" data-backdrop="static" data-keyboard="false">
					<div class="modal-dialog modal-dialog-centered" role="document">
					    <div class="modal-content">
						    <div class="modal-header" <?php $image = get_field('event_image'); ?> style="background-image: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo $image['url']; ?>); background-size: cover;background-position: center center;">
						        <h2 class="modal-title" id="exampleModalLongTitle" style="color: white;"><?php the_field('event_name'); ?></h2>
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          		<span aria-hidden="true">&times;</span>
						        </button>
						        <div class="modal-header-time">
	    							<p>
								        <?php 
								        	$time = strtotime(get_field('event_start_date'));
								        	echo date('j M',$time);
								        	the_field('event_start_time');
								        	$time = strtotime(get_field('event_end_date'));
								        	if (!$time == ""){ 
								        		echo ("&nbsp;-&nbsp;" . date('j',$time) . " " . date('M',$time) . " "); 
								        		echo the_field('event_end_time');} 
								        	?>
								    </p>
								</div>
								<div class="modal-header-location">
								    <p>
								    	<?php
										$map_location = get_field( 'event_location' );
										echo $map_location;
										?> 
									</p>
							    </div>
						    </div>
						    <div class="modal-body">
						      	<div class="modal-body-article">
						      		<h4>Description</h4>
	    							<p><?php the_field('event_description')?></p>
				                </div>
				                <div class="modal-body-article">
						      		<h4>Time</h4>
	    							<p><?php $time = strtotime(get_field('event_start_date')); ?>
								        <?php echo date('j',$time);?> <?php echo date('M',$time); ?>
								        <?php the_field('event_start_time'); ?>
								        <?php $time = strtotime(get_field('event_end_date')); ?>
								        <?php if (!$time == ""){ echo ("&nbsp;-&nbsp;" . date('j',$time) . " " . date('M',$time) . " "); echo the_field('event_end_time');} ?>
							        </p>
				                </div>
				                <div class="modal-body-article">
						      		<h4>Location</h4>
	    							<p><?php echo $map_location; ?></p>
				                </div>
				                <div class="modal-body-article">
						      		<h4>Spots available</h4>
						      		<?php 
										$con = mysqli_connect("localhost", "root", "password");
										mysqli_select_db($con, "sportklubben") or die("no db found");
										$postID = get_the_id();
										$query = "SELECT EventID, COUNT(*) AS EventSpot FROM EventSignUp WHERE EventID = $postID";
										if (!mysqli_query($con,$query)){
											echo("Error description: " . mysqli_error($con));
										}
										$result = mysqli_query ($con,$query);
										$row = mysqli_fetch_assoc($result);
										$available_spots = get_field('available_spots');
										$spots_left = (int)$available_spots - (int)$row['EventSpot'];
									?>
	    							<?php if ($spots_left > 0):?>
	    								<p><?php echo $spots_left; echo " of "; the_field('available_spots');?> available</p>
	    							<?php else: ?>
	    								<p style="color:red; margin-bottom:0;">FULLY BOOKED</p><p style="font-size: 10px;">(You can still sign up for the back-up list)</p>
	    							<?php endif?>
					            	<span style="font-weight: normal;"></span>
				                </div>
				                <div class="modal-body-article">
						      		<h4>Cost</h4>
	    							<p><?php the_field('event_cost')?></p>
				                </div>
				                <div class="modal-body-article">
						      		<h4>Event responsible</h4>
	    							<p><?php the_field('event_responsible')?></p>
	    							<p><?php the_field('event_responsible_email')?></p>
	    							<p><?php the_field('event_responsible_phone_number')?></p>
				                </div>
				                <div class='modal-body-article'>
				                	<h4>Sign up</h4>
				                <?php //only display sign up if sign up is open				                	
					                if ($signup == true){
						                get_template_part('form');
					            	}
					            	else{
					            		echo ('
					            			<p>Event sign up will open in ' . $daysLeft . $dayPlural . '</p>
					            			<div class="modal-button-container">
												<button type="button" class="btn btn-secondary" id="closeModal" data-dismiss="modal">Close</button>
											</div>');
					            	}
					            	echo ("</div>");
					            ?>
						    </div>
						</div>
					</div>
				</div>
				<!-- END MODAL -->

				<!-- Event box -->
				<div class="col-md-4 col-sm-4 imgcontainer">
			    	<div class="popimage" style="background-image: url(<?php echo $image['url']; ?>);background-size: cover; background-position: center center; ">
		    		</div>

		    		<!-- Dispay if event is not yet open for sign up -->
		    		<?php
		    			if ($signup == false){
		    				echo '
		    				<div class="pop-image overlay">
		    					<p>Event sign up will open in ' . $daysLeft . $dayPlural . '</p>
		    				</div>';
						}
		    		?>

		    		<!-- Check if the number of spots for the event has changed and update bookings in database -->
		    		<?php
		    			//Change spot for everyone with spotnumber higher than deleted booking
						$query = "UPDATE EventSignUp SET AvailableSpots=$available_spots WHERE EventID=$postID AND AvailableSpots<>$available_spots";
						if (!mysqli_query($con,$query)){
							echo("Error description: " . mysqli_error($con));
						}
						//ADD: if the number of spots have been increased, make sure to send email to people on the back-up list that the have gotten a spot.
		    		?>

					<div class="eventbox">
			            <h4>
			            	<?php 
			            		the_field('event_name');
			            	?>
			            </h4>
			            <div class="event-metadata">
			            	<div class="event-metadata-icon">
					            <p>
					            	<span class="fa fa-calendar" style="font-size:12px;color:#616161;margin-right: 5px;"></span>
					            </p>
				        	</div>
				        	<div class="event-metadata-text">
								<p>
									<?php
										$time = strtotime(get_field('event_start_date'));
										echo date('j',$time);
									?> 
									<?php
										echo date('M',$time);
									?> 
									<?php
										echo date('Y',$time);
									?>
								</p>
							</div>
						</div>
						<div class="event-metadata">
							<div class="event-metadata-icon">
								<p>
									<span class="fa fa-map-marker" style="font-size:16px;color:#616161;margin-right: 5px;"></span>
								</p>
							</div>
							<div class="event-metadata-text">
								<p>
									<?php
										$map_location = get_field( 'event_location' );
										echo $map_location;
									?>
								</p>
							</div>
						</div>
						<div class="event-metadata">
				            <div class="event-metadata-icon">
				            	<p>
				            		<span class="fa fa-user" style="font-size:16px;color:#616161;margin-right: 5px;"></span>
				            	</p>
				            </div>
				            <div class="event-metadata-text">
				            	<p>
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
										$available_spots = get_field('available_spots');
										$spots_left = (int)$available_spots - (int)$row['EventSpot'];
									?>
	    							<?php if ($spots_left > 0):?>
	    								<p><?php echo $spots_left; echo " of "; the_field('available_spots');?> available</p>
	    							<?php else: ?>
	    								<p style="color:red;">FULLY BOOKED</p><p style="font-size: 10px;">(You can still sign up for the back-up list)</p>
	    							<?php endif?>
					            	<span style="font-weight: normal;"></span>
					            </p>
				            </div>
				        </div>
			        </div>
			    	<!-- Button trigger modal -->
					<button type="button" class="btn <?php if($signup == true){echo('btn-primary');}else{echo('btn-secondary');} ?>" id="openModal" data-toggle="modal" data-target= <?php echo '"#eventModal'; the_ID(); echo '"';?>>
					  	<p>READ MORE
					  	<?php if($signup == true){echo(' & SIGN UP');} ?>
					  	</p>
					</button>
	        	</div>
		    </div>
		    <?php $count++;
				if ($count == 6) break;
				endforeach; 
			?>
			<?php if ($count==0) {
				echo'<h3 style="text-align:center;margin:60px 0;color:#969696;font-weight:100;font-size:22px;">Hang tight! We will be posting new activites here soon.</h3>';
			}
			?>
			<!-- End Event Box -->
			<!-- End Planned Activites -->
		</div>
	</div>
</div>
<?php 
	endif; 
?>









