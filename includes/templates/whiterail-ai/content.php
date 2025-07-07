<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php get_template_part('sections/inside-hero') ?>

	<header class="entry-header">
		<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ), true ) && patriot_towing_categorized_blog() ) : ?>
			<div class="entry-meta">
				<span class="cat-links"><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'patriot_towing' ) ); ?></span>
			</div>
		<?php endif; ?>

		<div class="entry-meta">
			<?php
			if ( 'post' === get_post_type() ) {
				patriot_towing_posted_on();
			}

			if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
				?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'twentyfourteen' ), __( '1 Comment', 'patriot_towing' ), __( '% Comments', 'patriot_towing' ) ); ?></span>
				<?php
				endif;

				edit_post_link( __( 'Edit', 'patriot_towing' ), '<span class="edit-link">', '</span>' );
			?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php
			the_content(
				sprintf(
					/* translators: %s: Post title. Only visible to screen readers. */
					__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'twentyfourteen' ),
					the_title( '<span class="screen-reader-text">', '</span>', false )
				)
			);

			wp_link_pages(
				array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'patriot_towing' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
				)
			);
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
		
</article><!-- #post-<?php the_ID(); ?> -->