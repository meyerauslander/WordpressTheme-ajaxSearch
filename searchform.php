<!-- search -->
<?php $place_holder=__("Enter a search query.",MAUS_TEXT_DOMAIN); ?>

<form class="search" method="get" action="<?php echo home_url(); ?>" role="search">
    <input class="search-input" type="search" name="s" placeholder="<?php _e( $place_holder, MAUS_TEXT_DOMAIN ); ?>">
    <button class="search-submit" type="submit" role="button"><?php _e( 'Search', MAUS_TEXT_DOMAIN ); ?></button>
</form>
<input type="text" hidden name="home_url" value="<?php echo home_url(); ?>" />

<!--ajax search result-->
<div id="maus_search_div"><div id="maus_search_container"></div></div>

<script type="text/javascript">
   
</script>

<!--ajax search result-->

<!-- /search -->
