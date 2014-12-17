<?php
/**
 * 2011 Searchform
 */
?>
	<form method="get" id="searchform" action="<?php bloginfo('url'); ?>">
			<input type="text" class="field" name="s" id="s"  placeholder="<?php _e('') ?>" />
			<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php _e('Search'); ?>" />
	</form>