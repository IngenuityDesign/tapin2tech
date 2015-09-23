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

    <div class="container">

  		<main id="main" class="site-main" role="main">

        <div class="row">
          <div class="col-md-9">

            <h2 class="spanned-title">
              <span><?php _e('News', 'visainterns'); ?></span>
            </h2>

            <div class="posts-wrapper">

          		<?php if ( have_posts() ) : ?>

                <div class="row">

          			<?php /* Start the Loop */ ?>
          			<?php while ( have_posts() ) : the_post(); ?>
                  <div class="col-md-4">
                    <div class="card">
            				<?php

            					/*
            					 * Include the Post-Format-specific template for the content.
            					 * If you want to override this in a child theme, then include a file
            					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
            					 */
            					get_template_part( 'template-parts/content', get_post_format() );
            				?>
                    </div>
                  </div>
          			<?php endwhile; ?>

                </div>

          			<?php the_posts_navigation(); ?>

              <?php else : ?>

                <?php get_template_part( 'template-parts/content', 'none' ); ?>

              <?php endif; ?>

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
