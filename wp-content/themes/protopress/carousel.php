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
					  'orderby'         => 'meta_value_num',
					  'order'           => 'DSC',
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