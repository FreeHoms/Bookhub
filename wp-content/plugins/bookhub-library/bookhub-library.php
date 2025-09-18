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

// REST API: bookhub/v1
function bookhub_register_rest_routes() {
    register_rest_route(
        'bookhub/v1',
        '/books',
        [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => 'bookhub_rest_list_books',
                'permission_callback' => '__return_true',
                'args'                => [
                    'search' => [
                        'description' => 'Search query for book title/excerpt.',
                        'type'        => 'string',
                        'required'    => false,
                        'sanitize_callback' => 'sanitize_text_field',
                    ],
                    'genre' => [
                        'description' => 'Filter by genre slug.',
                        'type'        => 'string',
                        'required'    => false,
                        'sanitize_callback' => 'sanitize_text_field',
                    ],
                    'per_page' => [
                        'description' => 'Number of books per page.',
                        'type'        => 'integer',
                        'required'    => false,
                        'default'     => 10,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => function($value) { return $value > 0 && $value <= 100; },
                    ],
                    'page' => [
                        'description' => 'Page number.',
                        'type'        => 'integer',
                        'required'    => false,
                        'default'     => 1,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => function($value) { return $value >= 1; },
                    ],
                ],
            ],
        ]
    );

    register_rest_route(
        'bookhub/v1',
        '/books/(?P<id>\\d+)',
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'bookhub_rest_get_book',
            'permission_callback' => '__return_true',
            'args'                => [
                'id' => [
                    'description' => 'Book post ID',
                    'type'        => 'integer',
                    'required'    => true,
                    'sanitize_callback' => 'absint',
                ],
            ],
        ]
    );
}
add_action('rest_api_init', 'bookhub_register_rest_routes');

function bookhub_rest_prepare_book($post) {
    $book_id = is_object($post) ? $post->ID : (int) $post;
    $genres  = wp_get_post_terms($book_id, 'genre', ['fields' => 'names']);
    $authors = wp_get_post_terms($book_id, 'author', ['fields' => 'names']);
    return [
        'id'      => $book_id,
        'title'   => get_the_title($book_id),
        'excerpt' => wp_strip_all_tags(get_the_excerpt($book_id)),
        'link'    => get_permalink($book_id),
        'year'    => get_post_meta($book_id, '_book_year', true),
        'rating'  => get_post_meta($book_id, '_book_rating', true),
        'isbn'    => get_post_meta($book_id, '_book_isbn', true),
        'genres'  => $genres,
        'authors' => $authors,
        'featured_image' => get_the_post_thumbnail_url($book_id, 'medium'),
    ];
}

function bookhub_rest_list_books(WP_REST_Request $request) {
    $params = $request->get_params();

    $tax_query = [];
    if (!empty($params['genre'])) {
        $tax_query[] = [
            'taxonomy' => 'genre',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($params['genre']),
        ];
    }
    if (count($tax_query) > 1) {
        $tax_query['relation'] = 'AND';
    }

    $query_args = [
        'post_type'      => 'book',
        'post_status'    => 'publish',
        's'              => isset($params['search']) ? sanitize_text_field($params['search']) : '',
        'posts_per_page' => isset($params['per_page']) ? absint($params['per_page']) : 10,
        'paged'          => isset($params['page']) ? absint($params['page']) : 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    if (!empty($tax_query)) {
        $query_args['tax_query'] = $tax_query;
    }

    $search_term = isset($params['search']) ? sanitize_text_field($params['search']) : '';

    if ($search_term !== '') {
        add_filter('posts_search', function ($search_sql, $wp_query) use ($search_term) {
            global $wpdb;
            if ($wp_query->get('post_type') !== 'book') {
                return $search_sql;
            }
            $like = '%' . $wpdb->esc_like($search_term) . '%';
            return $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s ", $like);
        }, 10, 2);
    }

    $wp_query = new WP_Query($query_args);
    $books = array_map('bookhub_rest_prepare_book', $wp_query->posts);

    $response = new WP_REST_Response($books);
    $response->header('X-WP-Total', (int) $wp_query->found_posts);
    $response->header('X-WP-TotalPages', (int) $wp_query->max_num_pages);
    if ($search_term !== '') {
        remove_all_filters('posts_search');
    }
    return $response;
}

function bookhub_rest_get_book(WP_REST_Request $request) {
    $book_id = absint($request['id']);
    $post = get_post($book_id);
    if (!$post || $post->post_type !== 'book' || $post->post_status !== 'publish') {
        return new WP_Error('book_not_found', 'Book not found', ['status' => 404]);
    }
    return new WP_REST_Response(bookhub_rest_prepare_book($post));
}