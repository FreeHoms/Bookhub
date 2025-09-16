<?php get_header(); ?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
	<header class="mb-6">
		<h1 class="text-2xl sm:text-3xl font-semibold tracking-tight"><?php single_term_title(); ?></h1>
		<?php if ( term_description() ) : ?>
			<p class="mt-2 text-gray-600"><?php echo term_description(); ?></p>
		<?php endif; ?>
	</header>

	<section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
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
					</div>
				</a>
			</article>
		<?php endwhile; endif; ?>
	</section>

	<div class="mt-6">
		<?php the_posts_pagination(['mid_size' => 1]); ?>
	</div>
</main>

<?php get_footer(); ?>


