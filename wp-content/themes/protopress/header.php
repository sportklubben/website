<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package protopress
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link href="/wp-content/themes/protopress/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="/wp-content/themes/protopress/flickity.css" media="screen">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<!--<div id="top-bar">
		<div class="container">
			<div id="top-menu">
				<?php wp_nav_menu( array( 'theme_location' => 'top' ) ); ?>
			</div>
		</div>
	</div>-->
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'protopress' ); ?></a>
	<div id="jumbosearch">
		<span class="fa fa-remove closeicon"></span>
		<div class="form">
			<?php get_search_form(); ?>
		</div>
	</div>
	
	
	
	<header id="masthead" class="site-header" role="banner">
		<div class="container conatiner-header">
			<!--menu
			<div id="top-menu">
				<?php wp_nav_menu( array( 'theme_location' => 'top' ) ); ?>
			</div>
			-->
			<!--logo-->
			<div class="site-branding">
				<a href="<?php header("Location: index.php"); ?>"><img src="<?php  $actual_link = "http://$_SERVER[HTTP_HOST]"; echo ($actual_link . '/wp-content/uploads/2018/04/Sportklubben-Logo.png'); ?>" style="max-width: 350px;"></a>
			</div>
		</div>

		
	</header><!-- #masthead -->
	<div>
		<?php get_template_part('carousel');?>
	</div>
	
	<div class="mega-container" style="background-color: #eaeaea !important; margin-bottom: 40px;">
		<?php get_template_part('planned', 'activities'); ?>
	</div>	














