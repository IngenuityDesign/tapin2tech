<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package visainterns
 */

get_header(); ?>

  <div id="primary" class="content-area">

    <div class="container-fluid">

      <main id="main" class="site-main" role="main">

        <div class="row">
          <div class="col-md-10 col-md-offset-1">
            <section id="interstitial1" class="interstital">
              <h3 class="bold">SCROLL THROUGH TO FIND YOUR CAMPUS AND CLICK FOR MORE DETAILS FOR HOW TO FIND US<br>
              <small class="subtitle">Don’t see your campus? Apply today because we believe great talent can come from everywhere.</small> 
              </h3>
              <div class="clear"></div>
            </section>

            <div class="clear"></div>
            <section id="top-carousel" class="carousel2">
              <?php get_template_part( 'template-parts/content-carousel2', 'carousel2' ); ?>
            </section>
            <section id="hidden-carousel" class="carousel1"> <!--shown-->
              <?php get_template_part( 'template-parts/content-internsslider', 'internsslider' ); ?>
              <div class="clear"></div>
            </section>
            <div class="clear"></div>
            <section id="interstitial2" class="interstital">
              <h3 class="bold">JOIN THE CONVERSATION<br>
              <small class="subtitle">Hear what others are saying about Visa Inc.</small> 
              </h3>
              <div class="clear"></div>
            </section>

            <section id="social_feeds">
              <?php get_template_part( 'template-parts/content-feed', 'feeds' ); ?>
              <div class="clear"></div>
            </section>
          </div> <!-- /.col -->

        </div> <!-- .row -->

      </main><!-- #main -->

    </div> <!-- /.container -->

  </div><!-- #primary -->


<?php get_footer(); ?>
