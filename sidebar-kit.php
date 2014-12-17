<?php
/**
 * Kit widget areas.
 *
 * @package WordPress
 * @subpackage skeleton
 * @since skeleton 0.1
 */

// count the active widgets to determine column sizes
$columnwidgets = is_active_sidebar('top-widget-area') + is_active_sidebar('first-column-widget-area') + is_active_sidebar('second-column-widget-area') + is_active_sidebar('third-column-widget-area');
// default
$pagergrid = "one_third";
// if only one
if ($columnwidgets == "1") {
$pagergrid = "ftw";
// if two, split in half
} elseif ($columnwidgets == "2") {
$pagergrid = "one_half";
// if three, divide in thirds
} elseif ($columnwidgets == "3") {
$pagergrid = "one_third";
// if four, split in fourths
}

?>

<?php if ($columnwidgets) : ?>
<div class="kit-wid-wrap fullspan">
<?php if (is_active_sidebar('top-widget-area')) : ?>
<div class="fullspan kit-top-widget">
	<?php dynamic_sidebar('top-widget-area'); ?>
</div>
<?php endif;?>

<?php if (is_active_sidebar('first-column-widget-area')) : ?>
<div class="<?php echo $pagergrid;?> alphan omega kit-widget">
	<?php dynamic_sidebar('first-column-widget-area'); ?>
</div>
<?php endif;?>

<?php if (is_active_sidebar('second-column-widget-area')) : $last = ($columnwidgets == '2' ? ' last' : false);?>
<div class="<?php echo $pagergrid.$last;?> alphan omega kit-widget-center">
	  <?php dynamic_sidebar('second-column-widget-area'); ?>
</div>
<?php endif;?>

<?php if (is_active_sidebar('third-column-widget-area')) : $last = ($columnwidgets == '3' ? ' last' : false);?>
<div class="<?php echo $pagergrid.$last;?> alphan omega right">
	  <?php dynamic_sidebar('third-column-widget-area'); ?>
</div>
<?php endif;?>
</div>

<?php endif;?>