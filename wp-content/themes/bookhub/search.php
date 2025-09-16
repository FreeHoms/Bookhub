<?php get_header(); ?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
	<header class="mb-6">
		<h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Search results for "<?php echo esc_html( get_search_query() ); ?>"</h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class('rounded-xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition-shadow'); ?>>
					<a href="<?php the_permalink(); ?>" class="block">
						<h2 class="text-lg font-semibold line-clamp-2"><?php the_title(); ?></h2>
						<div class="prose prose-sm max-w-none mt-3 text-gray-600">
							<?php the_excerpt(); ?>
						</div>
					</a>
					<div class="mt-4 flex items-center justify-between text-sm text-gray-500">
						<span class="capitalize"><?php echo esc_html( get_post_type() ); ?></span>
						<a class="text-brand hover:text-brand-dark font-medium" href="<?php the_permalink(); ?>">View</a>
					</div>
				</article>
			<?php endwhile; ?>
		</div>

		<div class="mt-6">
			<?php the_posts_pagination(['mid_size' => 1]); ?>
		</div>
	<?php else : ?>
		<p class="text-gray-600">No results found.</p>
	<?php endif; ?>
</main>

<?php get_footer(); ?>


