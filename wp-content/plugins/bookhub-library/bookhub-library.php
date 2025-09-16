<?php
/*
Plugin Name: BookHub Library
Plugin URI: http://example.com
Description: A plugin to manage books in WordPress.
Version: 1.0
Author: Your Name
Author URI: http://example.com
License: GPL2
Text Domain: bookhub-library
*/

// Security check
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


function bookhub_register_books_cpt() {
    $labels = [
        'name' => 'Books',
        'singular_name' => 'Book',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Book',
        'edit_item' => 'Edit Book',
        'new_item' => 'New Book',
        'view_item' => 'View Book',
        'search_items' => 'Search Books',
        'not_found' => 'No books found',
        'not_found_in_trash' => 'No books found in Trash',
        'all_items' => 'All Books',
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'menu_icon' => 'dashicons-book', // Icon in admin menu
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'books'],
        'show_in_rest' => true, // Enables Gutenberg + REST API
    ];

    register_post_type('book', $args);
}
add_action('init', 'bookhub_register_books_cpt');

function bookhub_register_taxonomies() {
    // Genre taxonomy (like categories)
    register_taxonomy('genre', 'book', [
        'label' => 'Genres',
        'hierarchical' => true, // behaves like categories
        'show_in_rest' => true,  // enables Gutenberg
        'rewrite' => ['slug' => 'genre'],
    ]);

    // Author taxonomy (like tags)
    register_taxonomy('author', 'book', [
        'label' => 'Authors',
        'hierarchical' => false, // behaves like tags
        'show_in_rest' => true,   // enables Gutenberg
        'rewrite' => ['slug' => 'author'],
    ]);
}
add_action('init', 'bookhub_register_taxonomies');

// Add meta box for Book Details
function bookhub_add_book_metabox() {
    add_meta_box(
        'book_details',            // ID
        'Book Details',            // Title
        'bookhub_render_book_metabox', // Callback function
        'book',                    // Post type
        'side',                    // Context
        'default'                  // Priority
    );
}
add_action('add_meta_boxes', 'bookhub_add_book_metabox');

// Render the meta box fields
function bookhub_render_book_metabox($post) {
    $year   = get_post_meta($post->ID, '_book_year', true);
    $rating = get_post_meta($post->ID, '_book_rating', true);
    $isbn   = get_post_meta($post->ID, '_book_isbn', true);
    ?>
    <label>Year:<br>
        <input type="number" name="book_year" value="<?php echo esc_attr($year); ?>" />
    </label>
    <br><br>
    <label>Rating:<br>
        <input type="number" name="book_rating" min="1" max="5" value="<?php echo esc_attr($rating); ?>" />
    </label>
    <br><br>
    <label>ISBN:<br>
        <input type="text" name="book_isbn" value="<?php echo esc_attr($isbn); ?>" />
    </label>
    <?php
}

// Save the meta box data
function bookhub_save_book_metabox($post_id) {
    if (array_key_exists('book_year', $_POST)) {
        update_post_meta($post_id, '_book_year', sanitize_text_field($_POST['book_year']));
    }
    if (array_key_exists('book_rating', $_POST)) {
        update_post_meta($post_id, '_book_rating', sanitize_text_field($_POST['book_rating']));
    }
    if (array_key_exists('book_isbn', $_POST)) {
        update_post_meta($post_id, '_book_isbn', sanitize_text_field($_POST['book_isbn']));
    }
}
add_action('save_post', 'bookhub_save_book_metabox');


function bookhub_register_book_highlight_block() {
    register_block_type(__DIR__ . '/blocks/book-highlight', [
        'render_callback' => 'bookhub_render_book_highlight_block',
    ]);
}
add_action('init', 'bookhub_register_book_highlight_block');

function bookhub_render_book_highlight_block($attributes) {
    if (empty($attributes['selectedBook'])) {
        return '';
    }

    $book_id = $attributes['selectedBook'];
    $title = get_the_title($book_id);
    $excerpt = get_the_excerpt($book_id);
    $year = get_post_meta($book_id, '_book_year', true);
    $rating = get_post_meta($book_id, '_book_rating', true);

    return "<div class='book-highlight'>
                <h3>{$title}</h3>
                <p>{$excerpt}</p>
                <p>Year: {$year} | Rating: {$rating}/5</p>
            </div>";
}
