<?php get_header(); ?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
	<section class="mb-8">
		<h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Welcome to <?php bloginfo('name'); ?></h1>
		<p class="mt-2 text-gray-600">Discover and manage your favorite books.</p>
	</section>

	<section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class('rounded-xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition-shadow'); ?>>
					<a href="<?php the_permalink(); ?>" class="block">
						<h2 class="text-lg font-semibold line-clamp-2"><?php the_title(); ?></h2>
						<div class="prose prose-sm max-w-none mt-3 text-gray-600">
							<?php the_excerpt(); ?>
						</div>
					</a>
					<div class="mt-4 flex items-center justify-between text-sm text-gray-500">
						<span><?php echo esc_html( get_the_date() ); ?></span>
						<a class="text-brand hover:text-brand-dark font-medium" href="<?php the_permalink(); ?>">Read more</a>
					</div>
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
			<p class="text-gray-600">No posts found.</p>
		<?php endif; ?>
	</section>
</main>

<?php get_footer(); ?>
