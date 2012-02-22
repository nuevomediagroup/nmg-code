<form method="get" class="searchform" action="<?php bloginfo('url'); ?>">
    <input type="text" class="field s" name="s" value="<?php _e('Search Videos', 'woothemes') ?>" onfocus="if (this.value == '<?php _e('Search Videos', 'woothemes') ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Search Videos', 'woothemes') ?>';}" />
    <input type="image" src="<?php bloginfo('template_url'); ?>/images/btn-search.png" class="search-submit" name="submit" value="<?php _e('Go', 'woothemes'); ?>" />
</form><!-- /#search-top -->
<div class="clear"></div>