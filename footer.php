<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package visainterns
 */

?>

	</div><!-- #content -->
</div><!-- #page -->

<?php wp_footer(); ?>
<footer>
	<div class="row">
		<div class="col-md-7 col-md-offset-1">
			<img src="<?php get_images_path();?>visa_logo_grey.png" /><br class="visible-xs" />
			<span><a target="_blank" href="https://www.facebook.com/Visa">  FACEBOOK </a></span>
			<span><a target="_blank" href="https://instagram.com/visa_us/?hl=en">  INSTAGRAM</a> </span>
			<span><a target="_blank" href="https://twitter.com/Visa"> TWITTER </a></span>
			<span><a target="_blank" href="https://www.linkedin.com/company/visa"> LINKEDIN </a></span>
			<!--<span>CAMPUS TOUR </span>-->
		</div>
		<div class="col-md-4 footer-copy">&copy; Copyright  1996-<?php echo date('Y'); ?> Visa. All Rights Reserved.</div>
	</div>
	<img class="border_bottom" src="<?php get_images_path();?>footer_border.png" />
</footer>
</body>
</html>
