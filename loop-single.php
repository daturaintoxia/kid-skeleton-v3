<?php
/**
 * The loop that displays a single post.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-single.php.
 *
 * @package Skeleton WordPress Theme Framework
 * @subpackage skeleton
 * @author Simple Themes - www.simplethemes.com
 */
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class('single'); ?>>
	<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php
			 global $redux_demo2 ;
			 if ($redux_demo2['entry-meta-single'] == TRUE) {
							echo '<div class="entry-meta">';
							echo skeleton_posted_on();
							echo "</div><!---->"."\n";
			} ?>

	<div class="entry-content">
	<?php
		the_content();
		wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'smpl' ), 'after' => '</div>' ) );
	?>
	</div><!-- .entry-content -->
<?php
global $redux_demo2 ;
 if ($redux_demo2['author-post-single'] == TRUE) {?>
	<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
			
	<div id="entry-author-info">
		<div id="author-avatar">
			<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'skeleton_author_bio_avatar_size', 60 ) ); ?>
		</div><!-- #author-avatar -->

		<div id="author-description">
			<h2><?php printf( esc_attr__( 'About %s', 'smpl' ), get_the_author() ); ?></h2>
			<?php the_author_meta( 'description' ); ?>
			<div id="author-link">
				<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
					<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'smpl' ), get_the_author() ); ?>
				</a>
			</div><!-- #author-link	-->
		</div><!-- #author-description -->
	</div><!-- #entry-author-info -->


<?php endif; ?>
	<? } 
			 global $redux_demo2 ;
			 if ($redux_demo2['entry-utility-single'] == TRUE) {
							?>					
	<div class="entry-utility">
		<?php skeleton_posted_in(); ?>
		<?php edit_post_link( __( 'Edit', 'smpl' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-utility -->
<?php
			} ?>
</div><!-- #post-## -->
			<?php
			 global $redux_demo2 ;
			 if ($redux_demo2['nav-below-single'] == TRUE) {?>
	<div id="nav-below" class="navigation">
		<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'smpl' ) . '</span> %title' ); ?></div>
		<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'smpl' ) . '</span>' ); ?></div>
	</div><!-- #nav-below -->

							<?php
			} ?>
	<?php comments_template( '', true ); ?>

<?php endwhile; // end of the loop. ?>