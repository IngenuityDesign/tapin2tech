<?php
/**
 * The template for displaying archive pages.
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

            <?php the_archive_title( '<h2 class="spanned-title page-title"><span>', '</span></h1>' ); ?>
            <?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>

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
