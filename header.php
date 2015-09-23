<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package visainterns
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-67189127-1', 'auto');
  ga('send', 'pageview');

</script>
<?php wp_head(); ?>
</head>
<?php $custom_txt = TEXT_BY_LOCATION::checkDo(); ?>
<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link sr-only" href="#content"><?php esc_html_e( 'Skip to content', 'visainterns' ); ?></a>
      
      <div class="jumbotron" id="main-jumbo">
     <?php if($custom_txt):?>
      <header class="" >
        <!--<div class="logo"><img src="<?php //get_images_path(); ?>visa_logo_white.png"></div>-->
        <p class="replace-location"><?php echo $custom_txt['loc']; ?></p><br>
        <div class="clear"></div>
        <div class="session-details">JOIN OUR INFO SESSION: <span class="replace-details"><?php echo $custom_txt['sess']; ?></span></div>
        <div class="clear"></div>
      </header>
    <?php endif; ?>
        <div class="container-fluid">
            <div class="row">  
                
                <img class="img-responsive" src="<?php get_images_path(); ?>visa_head_img.png" />
                <div class="head-content">
                <?php if (is_front_page() && have_posts()) : while (have_posts()) : the_post(); ?>
                  <?php echo get_the_content(); ?>
                <?php endwhile; endif; ?>   
                   <a href="https://visa.recsolucampus.com/candidatepreferenceform.php?formId=YQ%3D%3D" target="_blank" class="apply-btn btn">APPLY TODAY</a>
                </div>
            </div> <!-- /.row -->
          </div><!-- /.container-fluid -->
        <div class="clear"></div>
      </div><!-- /.jumbotron -->
    <div class="clear"></div>
	<div id="content" class="site-content">
