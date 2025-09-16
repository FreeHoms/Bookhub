<?php
// Theme setup: title tag and menus
add_action('after_setup_theme', function () {
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(600, 800, true);
	register_nav_menus([
		'primary' => __('Primary Menu', 'bookhub'),
	]);
});

// Enqueue Tailwind via CDN and theme stylesheet
add_action('wp_enqueue_scripts', function () {
	// Tailwind CDN (generates styles at runtime). Quick way to modernize UI.
	wp_enqueue_script('tailwind-cdn', 'https://cdn.tailwindcss.com', [], null, false);

	// Optional: Tailwind config to extend theme (colors/fonts) inline
	$tailwindConfig = 'tailwind.config = { theme: { extend: { colors: { brand: { DEFAULT: "#1d4ed8", dark: "#1e40af" } } } } }';
	wp_add_inline_script('tailwind-cdn', $tailwindConfig, 'after');

	// Theme stylesheet for custom tweaks if needed
	wp_enqueue_style('bookhub-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
});

// Widgets / Sidebar
add_action('widgets_init', function () {
	register_sidebar([
		'name'          => __('Primary Sidebar', 'bookhub'),
		'id'            => 'sidebar-1',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	]);
});


