	<!-- FLICKETY TEST -->
	<style type="text/css">
		* {
		  -webkit-box-sizing: border-box;
		  box-sizing: border-box;
		}

		body { font-family: sans-serif; }


	</style>
			<div class="js-flickity">
				<?php /* Start the Loop */ $count=0; ?>
				<?php
		    		$args = array(
					  'post_status'     => 'publish',
					  'posts_per_page'  => 3,
					  'meta_key'        => 'event_start_date',
					  'orderby'         => 'meta_value_num',
					  'order'           => 'ASC',
					  'category_name'	=> 'featured',
					);
					$lastposts = get_posts( $args );
					foreach ( $lastposts as $post ) :
				  	setup_postdata( $post );
				  	
			  	?>
				<div class="gallery-cell" <?php if($count === 0) { echo 'active'; } ?>" <?php $image = get_field('event_image'); ?> style="background-image: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo $image['url']; ?>);">
				    <div class="carousel-caption d-none d-md-block">
						<h1>
							<?php 
			            		the_field('event_name');
			            	?>
		            	</h1>
						<p class="carousel-description"><?php the_field('event_description')?></p>
						<form target="_blank" action="<?php echo the_field('featured_button_link');?>">
							<input type="submit" value="<?php echo the_field('featured_button_name');?>" class="btn btn-primary" name ="submit">
						</form>
					</div>
				</div>

				<?php $count++;
					if ($count == 3) 
						break;
						endforeach; 
				?>						
			</div>


	<!--CAROUSEL--><!--
		<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
			<ol class="carousel-indicators">
				<?php /* Start the Loop */ /*$count=0; ?>
					<?php
			    		$args = array(
						  'post_status'     => 'publish',
						  'posts_per_page'  => 3,
						  'meta_key'        => 'event_start_date',
						  'orderby'         => 'meta_value_num',
						  'order'           => 'ASC',
						  'category_name'	=> 'featured',
						);
						$lastposts = get_posts( $args );
						foreach ( $lastposts as $post ) :
					  	setup_postdata( $post );
					  	
				  	?>
				<li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $count?>" class="<?php if($count == 0) { echo 'active'; } ?>"></li>
				<?php 
					$count++;
					if ($count == 3)
						break;
						endforeach;
				?>
			</ol>

			<div class="carousel-inner">
				<?php /* Start the Loop */ /*$count=0; ?>
					<?php
			    		$args = array(
						  'post_status'     => 'publish',
						  'posts_per_page'  => 3,
						  'meta_key'        => 'event_start_date',
						  'orderby'         => 'meta_value_num',
						  'order'           => 'ASC',
						  'category_name'	=> 'featured',
						);
						$lastposts = get_posts( $args );
						foreach ( $lastposts as $post ) :
					  	setup_postdata( $post );
				  	?>
					<div class="carousel-item <?php if($count === 0) { echo 'active'; } ?>" <?php $image = get_field('event_image'); ?> style="height:400px; background-image: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo $image['url']; ?>); background-size: cover;background-position: center center;">
					    <div class="carousel-caption d-none d-md-block">
							<h1>
								<?php 
				            		the_field('event_name');
				            	?>
			            	</h1>
							<p class="carousel-description"><?php the_field('event_description')?></p>
							<form target="_blank" action="<?php echo the_field('featured_button_link');?>">
								<input type="submit" value="<?php echo the_field('featured_button_name');?>" class="btn btn-primary" name ="submit">
							</form>
						</div>
					</div>
				<?php $count++;
				if ($count == 3) 
					break;
					endforeach; 
				?>
			</div>

			<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>

		-->