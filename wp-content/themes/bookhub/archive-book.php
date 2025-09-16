<?php get_header(); ?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
	<header class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
		<div>
			<h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Book Library</h1>
			<p class="mt-2 text-gray-600">Browse by genre and author, or search.</p>
		</div>
		<form method="get" action="<?php echo esc_url( home_url('/') ); ?>" class="w-full sm:w-auto">
			<input type="search" name="s" value="<?php echo get_search_query(); ?>" class="h-10 w-full sm:w-80 rounded-md border border-gray-300 px-3 text-sm focus:border-brand focus:ring-1 focus:ring-brand" placeholder="Search books..." />
			<input type="hidden" name="post_type" value="book" />
		</form>
	</header>

	<div class="mb-8 flex flex-wrap items-center gap-3">
		<div class="text-sm text-gray-600">Genres:</div>
		<div class="flex flex-wrap gap-2">
			<?php
				$genres = get_terms(['taxonomy' => 'genre', 'hide_empty' => true]);
				if (!is_wp_error($genres)) {
					foreach ($genres as $genre) {
						echo '<a class="inline-flex items-center rounded-full border border-gray-300 px-3 py-1 text-sm hover:border-brand hover:text-brand" href="' . esc_url( get_term_link($genre) ) . '">' . esc_html($genre->name) . '</a>';
					}
				}
			?>
		</div>
	</div>

	<section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class('rounded-xl border border-gray-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow'); ?>>
					<a href="<?php the_permalink(); ?>" class="block">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail('medium', ['class' => 'w-full h-60 object-cover']); ?>
						<?php else: ?>
							<div class="w-full h-60 bg-gray-100 flex items-center justify-center text-gray-400">No Cover</div>
						<?php endif; ?>
						<div class="p-5">
							<h2 class="text-lg font-semibold line-clamp-2"><?php the_title(); ?></h2>
							<p class="mt-2 text-sm text-gray-600 line-clamp-3"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
							<div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-gray-500">
								<span>Year: <?php echo esc_html( get_post_meta(get_the_ID(), '_book_year', true) ); ?></span>
								<span>Rating: <?php echo esc_html( get_post_meta(get_the_ID(), '_book_rating', true) ); ?>/5</span>
							</div>
						</div>
					</a>
				</article>
			<?php endwhile; ?>

			<div class="sm:col-span-2 lg:col-span-3 mt-6">
				<?php the_posts_pagination([
					'prev_text' => __('Previous', 'bookhub'),
					'next_text' => __('Next', 'bookhub'),
					'mid_size' => 1,
				]); ?>
			</div>
		<?php else : ?>
			<p class="text-gray-600">No books found.</p>
		<?php endif; ?>
	</section>
</main>

<?php get_footer(); ?>
