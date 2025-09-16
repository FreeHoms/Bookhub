<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class('min-h-screen flex flex-col bg-gray-50 text-gray-800'); ?>>
<header class="border-b border-gray-200 bg-white/80 backdrop-blur sticky top-0 z-40">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="flex items-center justify-between h-16">
			<a href="<?php echo esc_url( home_url('/') ); ?>" class="flex items-center gap-3">
				<span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-brand text-white" aria-hidden="true">
					<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
						<path d="M15.75 19.5L8.25 12l7.5-7.5" />
					</svg>
				</span>
				<span class="text-lg font-semibold tracking-tight"><?php bloginfo('name'); ?></span>
			</a>
			<nav class="hidden md:flex">
				<?php
					wp_nav_menu([
						'theme_location' => 'primary',
						'container' => false,
						'menu_class' => 'flex items-center gap-6',
						'fallback_cb' => false,
					]);
				?>
			</nav>
			<div class="flex items-center gap-3">
				<form role="search" method="get" class="hidden sm:block" action="<?php echo esc_url( home_url('/') ); ?>">
					<label class="sr-only" for="site-search">Search</label>
					<input id="site-search" type="search" name="s" class="h-9 w-56 rounded-md border border-gray-300 px-3 text-sm focus:border-brand focus:ring-1 focus:ring-brand" placeholder="Search..." value="<?php echo get_search_query(); ?>" />
				</form>
				<button type="button" class="md:hidden inline-flex items-center justify-center h-9 w-9 rounded-md border border-gray-300" data-toggle="mobile-menu" aria-label="Toggle menu">
					<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
				</button>
			</div>
		</div>
	</div>
	<div class="md:hidden" id="mobile-menu" hidden>
		<div class="px-4 pb-4 border-t border-gray-200 bg-white">
			<form role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>" class="py-3">
				<label class="sr-only" for="site-search-mobile">Search</label>
				<input id="site-search-mobile" type="search" name="s" class="h-10 w-full rounded-md border border-gray-300 px-3 text-sm focus:border-brand focus:ring-1 focus:ring-brand" placeholder="Search..." value="<?php echo get_search_query(); ?>" />
			</form>
			<?php
				wp_nav_menu([
					'theme_location' => 'primary',
					'container' => false,
					'menu_class' => 'flex flex-col gap-2 py-2',
					'fallback_cb' => false,
				]);
			?>
		</div>
	</div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function () {
	const toggle = document.querySelector('[data-toggle="mobile-menu"]');
	const menu = document.getElementById('mobile-menu');
	if (!toggle || !menu) return;
	toggle.addEventListener('click', function () {
		const isHidden = menu.hasAttribute('hidden');
		if (isHidden) {
			menu.removeAttribute('hidden');
		} else {
			menu.setAttribute('hidden', '');
		}
	});
});
</script>
