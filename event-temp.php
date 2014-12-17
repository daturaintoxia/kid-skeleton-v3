<?php
/*
Template Name: Event Template
*/
?>
<?php get_header(); ?>
	<div id="content" class="eleven columns">
		<h3 class="news"><?php _e('Nyheter','smpl'); ?></h3>
		<?php
		$temp = $wp_query;
		$wp_query = NULL;
		$wp_query = new WP_Query();
		 $wp_query->query('cat=1&paged='.$paged); $tb_counter = 1;
		while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
		<div <?php if(function_exists('post_class')) : ?><?php post_class(); ?><?php else : ?>class="post post-<?php the_ID(); ?>"<?php endif; ?>>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s','smpl'),the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h2>
			<div class="entry-meta">
				<?php skeleton_posted_on(); ?>
			</div><!-- .entry-meta -->
			<?php the_post_thumbnail('medium'); ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
			
			<div class="entry">
				
			</div><div class="clear"></div>
		</div><div class="clear"></div>
		
		<?php $tb_counter++; endwhile; ?>
		<div class="nav-interior clearfix">
			<div class="prev"><?php next_posts_link(__('&laquo; Older Entries','smpl')); ?></div>
			<div class="next"><?php previous_posts_link(__('Newer Entries &raquo;','smpl')); ?></div>
		</div><div class="clear"></div>

		<?php $wp_query = NULL; $wp_query = $temp;?>
		
	</div>
		<?php get_sidebar('page'); ?>
<?php get_footer(); ?>

