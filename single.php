<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package visainterns
 */

get_header(); ?>

  <div id="primary" class="content-area">

    <div class="container">

      <main id="main" class="site-main" role="main">

        <div class="row">
          <div class="col-md-9">

            <div class="single-post-wrapper">

              <?php while ( have_posts() ) : the_post(); ?>

                <?php get_template_part( 'template-parts/content', 'single' ); ?>

                <?php the_post_navigation(); ?>

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
