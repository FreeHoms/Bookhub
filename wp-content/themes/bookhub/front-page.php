<?php get_header(); ?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
	<section class="rounded-2xl bg-gradient-to-r from-brand to-brand-dark text-white p-8 sm:p-12 shadow-sm">
		<div class="max-w-2xl">
			<h1 class="text-3xl sm:text-4xl font-semibold tracking-tight">Discover your next favorite book</h1>
			<p class="mt-3 text-white/90">Explore curated picks, browse by genre and author, and track what you love.</p>
			<div class="mt-6 flex flex-col sm:flex-row gap-3">
				<a href="<?php echo esc_url( get_post_type_archive_link('book') ); ?>" class="inline-flex items-center justify-center rounded-md bg-white text-brand font-medium px-5 py-3 hover:bg-white/90">Browse Library</a>
				<a href="<?php echo esc_url( home_url('/?s=&post_type=book') ); ?>" class="inline-flex items-center justify-center rounded-md border border-white/40 px-5 py-3 hover:bg-white/10">Search Books</a>
			</div>
		</div>
	</section>

	<?php
	// Featured books: top rated
	$featured = new WP_Query([
		'post_type' => 'book',
		'posts_per_page' => 6,
		'meta_key' => '_book_rating',
		'orderby' => 'meta_value_num',
		'order' => 'DESC',
	]);
	?>

	<?php if ( $featured->have_posts() ) : ?>
		<section class="mt-10">
			<h2 class="text-xl sm:text-2xl font-semibold">Top Rated Books</h2>
			<div class="mt-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
				<?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
					<article <?php post_class('rounded-xl border border-gray-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow'); ?>>
						<a href="<?php the_permalink(); ?>" class="block">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail('medium', ['class' => 'w-full h-60 object-cover']); ?>
							<?php else: ?>
								<div class="w-full h-60 bg-gray-100 flex items-center justify-center text-gray-400">No Cover</div>
							<?php endif; ?>
							<div class="p-5">
								<h3 class="text-lg font-semibold line-clamp-2"><?php the_title(); ?></h3>
								<p class="mt-2 text-sm text-gray-600 line-clamp-3"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
								<div class="mt-4 text-sm text-gray-500">Rating: <?php echo esc_html( get_post_meta(get_the_ID(), '_book_rating', true) ); ?>/5</div>
							</div>
						</a>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$latest = new WP_Query([
		'post_type' => 'book',
		'posts_per_page' => 6,
		'orderby' => 'date',
		'order' => 'DESC',
	]);
	?>
	<?php if ( $latest->have_posts() ) : ?>
		<section class="mt-10">
			<h2 class="text-xl sm:text-2xl font-semibold">Latest Arrivals</h2>
			<div class="mt-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
				<?php while ( $latest->have_posts() ) : $latest->the_post(); ?>
					<article class="rounded-xl border border-gray-200 bg-white overflow-hidden">
						<a class="block" href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail('medium', ['class' => 'w-full h-60 object-cover']); ?>
							<?php else: ?>
								<div class="w-full h-60 bg-gray-100 flex items-center justify-center text-gray-400">No Cover</div>
							<?php endif; ?>
							<div class="p-5">
								<h3 class="text-lg font-medium line-clamp-2"><?php the_title(); ?></h3>
								<div class="mt-2 text-sm text-gray-500">Year: <?php echo esc_html( get_post_meta(get_the_ID(), '_book_year', true) ); ?></div>
							</div>
						</a>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php get_footer(); ?>


