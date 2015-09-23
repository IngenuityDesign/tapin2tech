<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package visainterns
 */

get_header(); ?>

  <div id="primary" class="content-area">

    <div class="container">

      <main id="main" class="site-main" role="main">

        <div class="row">
          <div class="col-md-9">

            <div class="single-page-wrapper">

              <?php while ( have_posts() ) : the_post(); ?>

              <?php get_template_part( 'template-parts/content', 'page' ); ?>

              <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                  comments_template();
                endif;
              ?>

            <?php endwhile; // End of the loop. ?>

            </div>

          </div> <!-- /.col -->

          <div class="col-md-3">
            <?php get_sidebar(); ?>
          </div>

        </div> <!-- .row -->

      </main><!-- #main -->

    </div> <!-- /.container -->

  </div><!-- #primary -->


<?php get_footer(); ?>
