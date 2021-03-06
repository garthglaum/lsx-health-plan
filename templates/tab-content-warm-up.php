<?php
/**
 * Template used to display post content on single pages.
 *
 * @package lsx-health-plan
 */
?>

<?php
$warm_up = get_post_meta( get_the_ID(), 'plan_warmup', true );
if ( false !== $warm_up && '' !== $warm_up ) {

	$warmup_query = new WP_Query(
		array(
			'post__in'  => $warm_up,
			'post_type' => 'page',
		)
	);

	if ( $warmup_query->have_posts() ) {
		while ( $warmup_query->have_posts() ) {
			$warmup_query->the_post();
			lsx_entry_before();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php lsx_entry_top(); ?>
				<div class="entry-content">
					<?php
						the_content();
						wp_link_pages( array(
							'before'      => '<div class="lsx-postnav-wrapper"><div class="lsx-postnav">',
							'after'       => '</div></div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
						) );
					?>
				</div><!-- .entry-content -->

				<?php lsx_entry_bottom(); ?>

			</article><!-- #post-## -->

			<?php
			lsx_entry_after();
		}
		wp_reset_postdata();
		wp_reset_query();
	}
}
