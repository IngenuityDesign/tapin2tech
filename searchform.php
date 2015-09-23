<form role="search" method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <div>
    <label class="sr-only" for="s"><?php _x( 'Search for:', 'visainterns' ); ?></label>

    <div class="input-group">
      <span class="input-group-addon" id="search-icon">
        <button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>">
          <i class="fa fa-search"></i>
        </button>
      </span>
      <input type="text" class="form-control" placeholder="<?php _e('Search capability, product name, keyword...', 'visainterns'); ?>" value="<?php echo get_search_query(); ?>" name="s" id="s" aria-describedby="search-icon" />
    </div>

  </div>
</form>
