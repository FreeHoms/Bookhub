<?php get_header(); ?>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article <?php post_class('bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm'); ?>>
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail('large', ['class' => 'w-full h-96 object-cover']); ?>
			<?php endif; ?>
			<div class="p-6">
				<h1 class="text-2xl sm:text-3xl font-semibold tracking-tight"><?php the_title(); ?></h1>
				<div class="mt-4 prose max-w-none">
					<?php the_content(); ?>
				</div>
				<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-600">
					<div><span class="font-medium">Year:</span> <?php echo esc_html( get_post_meta(get_the_ID(), '_book_year', true) ); ?></div>
					<div><span class="font-medium">Rating:</span> <?php echo esc_html( get_post_meta(get_the_ID(), '_book_rating', true) ); ?>/5</div>
					<div><span class="font-medium">ISBN:</span> <?php echo esc_html( get_post_meta(get_the_ID(), '_book_isbn', true) ); ?></div>
					<div><span class="font-medium">Genre:</span> <?php echo wp_kses_post( get_the_term_list(get_the_ID(), 'genre', '', ', ') ); ?></div>
					<div><span class="font-medium">Author:</span> <?php echo wp_kses_post( get_the_term_list(get_the_ID(), 'author', '', ', ') ); ?></div>
				</div>
			</div>
		</article>

		<?php
		// Related books by shared genres
		$genres = wp_get_post_terms(get_the_ID(), 'genre', ['fields' => 'ids']);
		if ( $genres ) {
			$related = new WP_Query([
				'post_type' => 'book',
				'posts_per_page' => 3,
				'post__not_in' => [ get_the_ID() ],
				'tax_query' => [[
					'taxonomy' => 'genre',
					'field' => 'term_id',
					'terms' => $genres,
				]],
			]);
			if ( $related->have_posts() ) : ?>
				<section class="mt-10">
					<h2 class="text-xl font-semibold">Related Books</h2>
					<div class="mt-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
						<?php while ( $related->have_posts() ) : $related->the_post(); ?>
							<article class="rounded-xl border border-gray-200 bg-white overflow-hidden">
								<a class="block" href="<?php the_permalink(); ?>">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
									<?php else: ?>
										<div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">No Cover</div>
									<?php endif; ?>
									<div class="p-4">
										<h3 class="font-medium line-clamp-2"><?php the_title(); ?></h3>
									</div>
								</a>
							</article>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</section>
			<?php endif; ?>
		<?php } ?>

	<?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>


