<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package gpi
 */

if ( ! function_exists( 'the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_posts_navigation() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation posts-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'gpi' ); ?></h2>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( esc_html__( 'Older posts', 'gpi' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( esc_html__( 'Newer posts', 'gpi' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'gpi' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
				next_post_link( '<div class="nav-next">%link</div>', '%title' );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'gpi_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function gpi_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);


	echo '<span class="posted-on">' . $time_string . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'gpi_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function gpi_entry_footer() {

	if ( 'post' == get_post_type() ) {
    printf('<a href="%s" rel="bookmark">', esc_url( get_permalink() ));
    gpi_posted_on();
    print('<span class="pull-right"><i class="fa fa-caret-right"></i></span>');
    print('</a>');
	}
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( esc_html__( 'Category: %s', 'gpi' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( esc_html__( 'Tag: %s', 'gpi' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( esc_html__( 'Author: %s', 'gpi' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( esc_html__( 'Year: %s', 'gpi' ), get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'gpi' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( esc_html__( 'Month: %s', 'gpi' ), get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'gpi' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( esc_html__( 'Day: %s', 'gpi' ), get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'gpi' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = esc_html_x( 'Asides', 'post format archive title', 'gpi' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = esc_html_x( 'Galleries', 'post format archive title', 'gpi' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = esc_html_x( 'Images', 'post format archive title', 'gpi' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = esc_html_x( 'Videos', 'post format archive title', 'gpi' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = esc_html_x( 'Quotes', 'post format archive title', 'gpi' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = esc_html_x( 'Links', 'post format archive title', 'gpi' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = esc_html_x( 'Statuses', 'post format archive title', 'gpi' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = esc_html_x( 'Audio', 'post format archive title', 'gpi' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = esc_html_x( 'Chats', 'post format archive title', 'gpi' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( esc_html__( 'Archives: %s', 'gpi' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( esc_html__( '%1$s: %2$s', 'gpi' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = esc_html__( 'Archives', 'gpi' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;  // WPCS: XSS OK.
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;  // WPCS: XSS OK.
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function gpi_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'gpi_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'gpi_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so gpi_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so gpi_categorized_blog should return false.
		return false;
	}
}

function gpi_nav_menu( $overrides = null ) {

    $args = array(
        'theme_location'  => 'primary',
        'menu'            => '',
        'container'       => false,
        'menu_class'      => 'nav navbar-nav navbar-right',
        'menu_id'		  => "main-menu",
        'walker'          => new GPI_Menu_Walker(),
        'echo'            => true
    );
    if ($overrides !== null) {
    	foreach( $overrides as $k=>$v ) {
    		$args[$k] = $v;
    	}
    }
    $defaults = array_merge($args, array('fallback_cb' => array('Bootstrap_Menu_Walker', 'fallback')));
    wp_nav_menu( $defaults );
}

function gpi_list_comments( $overrides = null ) {
	$args = array(
        'walker'      => new GPI_Comments_Walker(),
        'style'       => 'ol',
        'short_ping'  => true,
        'avatar_size' => 64
    );

    if ($overrides !== null) {
    	foreach( $overrides as $k=>$v ) {
    		$args[$k] = $v;
    	}
    }

	wp_list_comments( $args );
}

function gpi_comments_form( $overrides = null ) {
  $args = array(
    'id_form'           => 'commentform',
    'id_submit'         => 'submit',
    'title_reply'       => __( 'Leave a Reply' ),
    'title_reply_to'    => __( 'Leave a Reply to %s' ),
    'cancel_reply_link' => __( 'Cancel Reply' ),
    'label_submit'      => __( 'Post Comment' ),
    'comment_field' =>
      '<div class="form-group">
          <label for="comment" class="col-sm-3 control-label">' . _x( 'Comment', 'noun' ) . '</label>' .
          '<div class="col-sm-9">
              <textarea id="comment" name="comment" rows="8" class="form-control" aria-required="true"></textarea>
          </div>' .
       '</div>',


    'must_log_in' => '<p class="must-log-in">' .
      sprintf(
          __( 'You must be <a href="%s">logged in</a> to post a comment.' ),
          wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
      ) . '</p>',
    'logged_in_as' => '<div class="form-group form-group-submit">
      <div class="col-sm-offset-3 col-sm-9"><p class="logged-in-as">' .
        sprintf(
            __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ),
            admin_url( 'profile.php' ),
            $user_identity,
            wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
        ) . '</p></div></div>',
    'comment_notes_before' => '<p class="comment-notes">' .
      __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) .
      '</p>',
    'comment_notes_after' =>
      '<div class="form-group form-group-submit">
        <div class="col-sm-offset-3 col-sm-9" style="text-align:right;padding-top:15px;">
          <button type="submit" class="btn btn-default">'.__('Post Comment').'</button>
        </div>
       </div>',
    'fields' => apply_filters( 'comment_form_default_fields', array(
      'author' =>
          '<div class="form-group">
             <label for="author" class="col-sm-3 control-label">' . __( 'Name', 'domainreference' ) .
              '</label>
              <div class="col-sm-9">
                  <input id="author" name="author" class="form-control" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
          '" ' . $aria_req . ' />
              </div>
           </div>',
      'email' =>
          '<div class="form-group">
                 <label for="email" class="col-sm-3 control-label">' . __( 'Email', 'domainreference' ) .
          '</label>
          <div class="col-sm-9">
              <input id="email" name="email" class="form-control" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) .
          '" ' . $aria_req . ' />
              </div>
           </div>',
      'url' =>
          '<div class="form-group">
                 <label for="url" class="col-sm-3 control-label">' . __( 'Website', 'domainreference' ) .
          '</label>
          <div class="col-sm-9">
              <input id="url" name="url" class="form-control" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
          '" ' . $aria_req . ' />
              </div>
           </div>',
      'submit' => ''
      )
    ),
  );

  if ($overrides !== null) {
    foreach( $overrides as $k=>$v ) {
      $args[$k] = $v;
    }
  }

  comment_form($args);

}

function gpi_image_path() {

  return sprintf('%s/images/%s', get_stylesheet_directory_uri(), implode('/', func_get_args()));

}

function gpi_logo() {
  printf('<img src="%s" alt="%s">', gpi_image_path('logo.png'), get_bloginfo('name'));
}

function gpi_account_nav() {
  global $current_user;

  print('<ul class="navbar-right nav navbar-nav" id="accounts-nav">');
  print('<li>');
  if (is_user_logged_in()):
    if (current_user_can('manage_plugins')):
      print('<a href="' . get_admin_url() . '">');
    else:
      print('<a disabled href="#">');
    endif;
      printf( __( 'Hi, %s!', 'gpi'), $current_user->display_name );
  else:
    print('<a href="' . wp_login_url() . '">');
    _e('Login', 'gpi');
    print('</a>');

  endif;
    print('</a>');
  print('</li>');
  print('</ul>');
}

/**
 * Flush out the transients used in gpi_categorized_blog.
 */
function gpi_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'gpi_categories' );
}

function gpi_tab_panel_content( $title, $content ) {
  ?>
  <div class="row">
    <div class="col-md-6">
      <h3><?php echo $title; ?></h3>
    </div>
    <div class="col-md-6">
      <?php echo $content; ?>
    </div>
  </div> <!-- /.row -->
  <?php
}

function gpi_card_thumbnail($attr = array()) {
  if (!array_key_exists('class', $attr))
    $attr['class'] = 'attachment-post-card-desktop img-responsive';
  the_post_thumbnail( 'post-card-desktop', $attr );
}

function gpi_content_tab_panel() {
  ?>
  <!-- Nav tabs -->
  <div class="tab-panel-container" data-slide="10000">
    <ul class="nav nav-tabs nav-justified" role="tablist">
      <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">
        <span>Who is GPI?</span>
      </a></li>
      <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
        <span>News</span>
      </a></li>
      <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">
        <span>Priorities &amp; Metrics</span>
      </a></li>
      <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">
        <span>Products</span>
      </a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active fadeIn animated" id="home">

        <?php gpi_tab_panel_content(
          'Who<br>is GPI?',
          '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas iaculis, sem et pharetra sagittis, mi ante imperdiet diam, id luctus velit nulla vel ipsum. Praesent mattis nec nibh feugiat imperdiet. Cras aliquet nec felis eget tristique. Etiam eget laoreet ante, non congue erat. Quisque at condimentum sem. Nulla malesuada eget est vitae aliquet. Morbi dignissim conses sapien vitae dictum. hendrerit a sapien congue cursus. Suspendisse ornare iaculis mollis.</p>'
        ); ?>

      </div>
      <div role="tabpanel" class="tab-pane fadeIn animated" id="profile">

        <?php gpi_tab_panel_content(
          'News',
          '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas iaculis, sem et pharetra sagittis, mi ante imperdiet diam, id luctus velit nulla vel ipsum. Praesent mattis nec nibh feugiat imperdiet. Cras aliquet nec felis eget tristique. Etiam eget laoreet ante, non congue erat. Quisque at condimentum sem. Nulla malesuada eget est vitae aliquet. Morbi dignissim conses sapien vitae dictum. hendrerit a sapien congue cursus. Suspendisse ornare iaculis mollis.</p>'
        ); ?>

      </div>
      <div role="tabpanel" class="tab-pane fadeIn animated" id="messages">
        <?php gpi_tab_panel_content(
          'Priorities &amp; Metrics',
          '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas iaculis, sem et pharetra sagittis, mi ante imperdiet diam, id luctus velit nulla vel ipsum. Praesent mattis nec nibh feugiat imperdiet. Cras aliquet nec felis eget tristique. Etiam eget laoreet ante, non congue erat. Quisque at condimentum sem. Nulla malesuada eget est vitae aliquet. Morbi dignissim conses sapien vitae dictum. hendrerit a sapien congue cursus. Suspendisse ornare iaculis mollis.</p>'
        ); ?>
      </div>
      <div role="tabpanel" class="tab-pane fadeIn animated" id="settings">

        <?php gpi_tab_panel_content(
          'Products',
          '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas iaculis, sem et pharetra sagittis, mi ante imperdiet diam, id luctus velit nulla vel ipsum. Praesent mattis nec nibh feugiat imperdiet. Cras aliquet nec felis eget tristique. Etiam eget laoreet ante, non congue erat. Quisque at condimentum sem. Nulla malesuada eget est vitae aliquet. Morbi dignissim conses sapien vitae dictum. hendrerit a sapien congue cursus. Suspendisse ornare iaculis mollis.</p>'
        ); ?>

      </div>
    </div>
  </div>
  <?php
}

add_action( 'edit_category', 'gpi_category_transient_flusher' );
add_action( 'save_post',     'gpi_category_transient_flusher' );
