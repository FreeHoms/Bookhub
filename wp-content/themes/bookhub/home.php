<?php
// Fallback for blog posts index if user hasn't set a static homepage
get_header(); ?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
	<section class="rounded-2xl bg-white border border-gray-200 p-8 shadow-sm">
		<h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Latest Posts</h1>
		<?php if ( have_posts() ) : ?>
			<div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
				<?php while ( have_posts() ) : the_post(); ?>
					<article <?php post_class('rounded-xl border border-gray-200 bg-white p-6'); ?>>
						<a href="<?php the_permalink(); ?>" class="block">
							<h2 class="text-lg font-semibold line-clamp-2"><?php the_title(); ?></h2>
							<div class="prose prose-sm max-w-none mt-3 text-gray-600"><?php the_excerpt(); ?></div>
						</a>
						<div class="mt-4 text-sm text-gray-500"><?php echo esc_html( get_the_date() ); ?></div>
					</article>
				<?php endwhile; ?>
			</div>
			<div class="mt-6"><?php the_posts_pagination(['mid_size' => 1]); ?></div>
		<?php else : ?>
			<p class="text-gray-600">No posts found.</p>
		<?php endif; ?>
	</section>
</main>

<?php get_footer(); ?>


