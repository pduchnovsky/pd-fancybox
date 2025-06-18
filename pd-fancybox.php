<?php
/**
 * Plugin Name: Fancybox
 * Plugin URI: https://github.com/pduchnovsky/pd-fancybox
 * Description: Integrates Fancybox for images, galleries, PDFs and inline content
 * Version: 1.2.9
 * Author: pd
 * Author URI: https://duchnovsky.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: pd-fancybox
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Define the option key for storing plugin settings in the WordPress database.
define( 'PDFB_OPTION_KEY', 'pdfb_enabled_types' );

/**
 * Load plugin textdomain for internationalization.
 *
 * @since 1.0.0
 */
add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'pd-fancybox', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
});

/**
 * Helper function to check if a specific Fancybox feature is enabled.
 *
 * @since 1.0.0
 *
 * @param string $type The feature to check (e.g., 'images', 'galleries', 'pdfs', 'inline', 'inline_close_click').
 * @return bool True if the feature is enabled, false otherwise.
 */
function pdfb_is_enabled( $type ) {
    // Define default settings for all features.
    $default_options = [
        'images'             => 0, // Images (standalone)
        'galleries'          => 0, // Galleries (Gutenberg blocks)
        'pdfs'               => 0, // PDF Links
        'inline'             => 0, // Inline Content (data-fancybox links)
        'inline_close_click' => 0  // Allow closing inline content by clicking on it
    ];
    // Retrieve stored options, merging with defaults to ensure all keys exist.
    $options = get_option( PDFB_OPTION_KEY, $default_options );
    // Return true if the specific type is enabled (value is 1), false otherwise.
    return ! empty( $options[ $type ] ?? 0 );
}

/**
 * Sanitize and validate plugin settings before saving to the database.
 *
 * Ensures that all settings are boolean (0 or 1).
 *
 * @since 1.0.0
 *
 * @param array $input The unsanitized settings array from the form.
 * @return array The sanitized settings array.
 */
function pdfb_sanitize_settings( $input ) {
    $output = [];
    // Iterate through all expected setting keys and sanitize them.
    foreach ( ['images', 'galleries', 'pdfs', 'inline', 'inline_close_click'] as $key ) {
        $output[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0; // Convert to 0 or 1.
    }
    return $output;
}

/**
 * Register plugin settings with WordPress.
 *
 * This hook is fired during WordPress admin initialization.
 *
 * @since 1.0.0
 */
add_action( 'admin_init', function() {
    register_setting( 'pdfb_settings_group', PDFB_OPTION_KEY, [
        'type'              => 'array',             // The option type is an array.
        'sanitize_callback' => 'pdfb_sanitize_settings', // Callback function for sanitization.
        'default'           => ['images' => 0, 'galleries' => 0, 'pdfs' => 0, 'inline' => 0, 'inline_close_click' => 0], // Default values.
    ]);
});

/**
 * Add the plugin's settings page to the WordPress admin menu under 'Settings'.
 *
 * This hook is fired after the basic admin panel setup.
 *
 * @since 1.0.0
 */
add_action( 'admin_menu', function() {
    add_options_page(
        esc_html__( 'Fancybox Settings', 'pd-fancybox' ), // Page title
        esc_html__( 'Fancybox', 'pd-fancybox' ),          // Menu title
        'manage_options',                                  // Required capability to access
        'pd-fancybox-settings',                            // Unique menu slug
        'pdfb_settings_page_html'                          // Callback function to render the page content
    );
});

/**
 * Renders the HTML content for the plugin's settings page.
 *
 * @since 1.0.0
 */
function pdfb_settings_page_html() {
    // Check if the current user has the 'manage_options' capability.
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Define default options to be used if no options are saved yet.
    $default_options = ['images' => 0, 'galleries' => 0, 'pdfs' => 0, 'inline' => 0, 'inline_close_click' => 0];
    // Retrieve the current options from the database.
    $options = get_option( PDFB_OPTION_KEY, $default_options );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Fancybox Settings', 'pd-fancybox' ); ?></h1>
        <p><?php esc_html_e( 'Use these options to enable or disable Fancybox for different content types.', 'pd-fancybox' ); ?></p>
        <form method="post" action="options.php">
            <?php
            // Output security fields for the registered setting.
            settings_fields( 'pdfb_settings_group' );
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Enable For:', 'pd-fancybox' ); ?></th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="checkbox" name="<?php echo esc_attr( PDFB_OPTION_KEY ); ?>[images]" value="1" <?php checked( $options['images'] ?? 0, 1 ); ?>>
                                <?php esc_html_e( 'Images (standalone, not in galleries)', 'pd-fancybox' ); ?>
                            </label><br>
                            <label>
                                <input type="checkbox" name="<?php echo esc_attr( PDFB_OPTION_KEY ); ?>[galleries]" value="1" <?php checked( $options['galleries'] ?? 0, 1 ); ?>>
                                <?php esc_html_e( 'Galleries (Gutenberg blocks)', 'pd-fancybox' ); ?>
                            </label><br>
                            <label>
                                <input type="checkbox" name="<?php echo esc_attr( PDFB_OPTION_KEY ); ?>[pdfs]" value="1" <?php checked( $options['pdfs'] ?? 0, 1 ); ?>>
                                <?php esc_html_e( 'PDF Links', 'pd-fancybox' ); ?>
                            </label><br>
                            <label>
                                <input type="checkbox" name="<?php echo esc_attr( PDFB_OPTION_KEY ); ?>[inline]" value="1" <?php checked( $options['inline'] ?? 0, 1 ); ?>>
                                <?php esc_html_e( 'Inline Content (data-fancybox links)', 'pd-fancybox' ); ?>
                            </label><br>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Inline Content Behavior:', 'pd-fancybox' ); ?></th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="checkbox" name="<?php echo esc_attr( PDFB_OPTION_KEY ); ?>[inline_close_click]" value="1" <?php checked( $options['inline_close_click'] ?? 0, 1 ); ?>>
                                <?php esc_html_e( 'Allow closing inline content by clicking on it', 'pd-fancybox' ); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <?php submit_button(); // Output the standard WordPress submit button. ?>
        </form>
    </div>
    <?php
}

/**
 * Determines if Fancybox assets should be enqueued based on post content and block usage.
 *
 * Assets are enqueued only if at least one enabled content type (image, gallery, PDF, inline)
 * is detected in the post content or if a relevant block is used.
 *
 * @since 1.0.0
 *
 * @return bool True if assets should be enqueued, false otherwise.
 */
function pdfb_should_enqueue_assets() {
    // Check for presence of relevant Gutenberg blocks or data-fancybox attribute in content.
    return apply_filters( 'pdfb_enqueue_assets',
        has_block( 'core/gallery' ) ||
        has_block( 'core/image' ) ||
        has_block( 'core/media-text' ) || // For media & text block that might contain images/links
        has_block( 'core/navigation' ) || // For navigation block that might contain links
        has_block( 'core/navigation-link' ) || // For individual navigation links
        get_post_gallery() || // Checks for classic galleries
        strpos( get_the_content(), 'data-fancybox' ) !== false // Checks for inline or other manual fancybox links
    );
}

/**
 * Enqueue Fancybox CSS and JavaScript assets.
 *
 * Assets are enqueued conditionally based on `pdfb_should_enqueue_assets()` and plugin settings.
 *
 * @since 1.0.0
 */
add_action( 'wp_enqueue_scripts', 'pdfb_enqueue_fancybox_assets' );
function pdfb_enqueue_fancybox_assets() {
    // Only enqueue if necessary based on content and if any Fancybox type is enabled.
    if ( ! pdfb_should_enqueue_assets() ||
         ( ! pdfb_is_enabled('images') &&
           ! pdfb_is_enabled('galleries') &&
           ! pdfb_is_enabled('pdfs') &&
           ! pdfb_is_enabled('inline') ) ) {
        return;
    }

    $plugin_url     = plugin_dir_url(__FILE__);
    $plugin_data    = get_plugin_data( __FILE__ );
    $plugin_version = $plugin_data['Version']; // Use plugin version for cache busting

    // Enqueue Fancybox CSS.
    wp_enqueue_style( 'fancybox-css', $plugin_url . 'fancybox/fancybox.css', [], '5.0.33' );
    // Enqueue Fancybox JavaScript.
    wp_enqueue_script( 'fancybox-js', $plugin_url . 'fancybox/fancybox.umd.min.js', [], '5.0.33', true );

    // Custom CSS for Fancybox.
    $custom_css = "
        .fancybox__slide.has-html {
            padding: 31px 0px 0px 0px; /* Adjust padding for inline content */
        }
    ";

    // Add CSS rules for inline content 'close on click' behavior if enabled.
    if ( pdfb_is_enabled('inline_close_click') ) {
        $custom_css .= "
            .fancybox__slide.has-html .fancybox-content.f-html {
                pointer-events: none; /* Disable pointer events on content to allow click-through */
            }
            .fancybox__slide.has-html .fancybox-content.f-html a,
            .fancybox__slide.has-html .fancybox-content.f-html button,
            .fancybox__slide.has-html .fancybox-content.f-html input,
            .fancybox__slide.has-html .fancybox-content.f-html textarea,
            .fancybox__slide.has-html .fancybox-content.f-html .scrollable-element {
                pointer-events: auto; /* Re-enable pointer events for interactive elements */
            }
            .fancybox__slide.has-html .f-button[data-fancybox-close] {
                 pointer-events: auto; /* Ensure close button is clickable */
            }
            .fancybox__slide.has-html {
                cursor: pointer; /* Show pointer cursor over the whole slide */
            }
            .fancybox__slide.has-html .fancybox__viewport.is-draggable {
                cursor: pointer !important; /* Force pointer cursor for draggable viewport */
            }
        ";
    }

    // Add responsive adjustments for toolbar and navigation.
    $custom_css .= "
        @media (max-width: 768px) {
            .f-carousel__toolbar__column.is-left .f-carousel__counter {
                display: none !important; /* Hide counter on small screens */
            }
            .f-carousel__toolbar__column.is-right .f-button[data-panzoom-action=\"toggleFull\"],
            .f-carousel__toolbar__column.is-right .f-button[data-autoplay-action=\"toggle\"],
            .f-carousel__toolbar__column.is-right .f-button[data-fullscreen-action=\"toggle\"],
            .f-carousel__toolbar__column.is-right .f-button[data-thumbs-action=\"toggle\"] {
                display: none !important; /* Hide various toolbar buttons on small screens */
            }
            .f-carousel__toolbar__column.is-middle .f-button {
                display: none !important; /* Hide middle toolbar buttons on small screens */
            }
            .f-button.is-arrow {
                display: none !important; /* Hide navigation arrows on small screens */
            }
            .f-thumbs {
                display: none !important; /* Hide thumbnails on small screens */
            }
        }
    ";
    wp_add_inline_style( 'fancybox-css', $custom_css );

    // Enqueue custom Fancybox initialization script.
    wp_enqueue_script( 'mfb-fancybox-init', $plugin_url . 'pd-fancybox-init.js', [ 'fancybox-js' ], $plugin_version, true );

    // Localize script with plugin settings for JavaScript access.
    wp_localize_script( 'mfb-fancybox-init', 'pdfbSettings', [
        'enabled' => [
            'images'             => pdfb_is_enabled('images'),
            'galleries'          => pdfb_is_enabled('galleries'),
            'pdfs'               => pdfb_is_enabled('pdfs'),
            'inline'             => pdfb_is_enabled('inline'),
            'inline_close_click' => pdfb_is_enabled('inline_close_click'),
        ]
    ]);
}

/**
 * Disable default WordPress lightbox scripts to prevent conflicts with Fancybox.
 *
 * This hook is fired on both `wp_enqueue_scripts` and `wp_footer` to ensure scripts are removed.
 *
 * @since 1.0.0
 */
add_action( 'wp_enqueue_scripts', 'pdfb_disable_wp_lightbox', 99 );
add_action( 'wp_footer', 'pdfb_disable_wp_lightbox', 99 );

function pdfb_disable_wp_lightbox() {
    // Deregister and dequeue 'wp-lightbox' if it exists (older WordPress versions).
    if ( wp_script_is( 'wp-lightbox', 'registered' ) ) {
        wp_deregister_script( 'wp-lightbox' );
        wp_dequeue_script( 'wp-lightbox' );
    }
    // Deregister and dequeue 'wp-block-library-view' (Gutenberg's default lightbox for images) if it exists.
    if ( wp_script_is( 'wp-block-library-view', 'registered' ) ) {
        wp_deregister_script( 'wp-block-library-view' );
        wp_dequeue_script( 'wp-block-library-view' );
    }
}

/**
 * Adds data-fancybox attributes to PDF links in post content and widget text.
 *
 * Ensures PDFs open in Fancybox if the feature is enabled.
 * Excludes links that already have data-fancybox or a download attribute.
 *
 * @since 1.0.0
 *
 * @param string $content The post or widget content.
 * @return string The modified content with Fancybox attributes added to PDF links.
 */
add_filter( 'the_content', 'pdfb_add_fancybox_to_pdf_links_in_content', 10 );
add_filter( 'widget_text_content', 'pdfb_add_fancybox_to_pdf_links_in_content', 10 );

function pdfb_add_fancybox_to_pdf_links_in_content( $content ) {
    if ( ! pdfb_is_enabled('pdfs') ) {
        return $content;
    }

    // Pattern to find <a> tags with href ending in .pdf.
    $pattern = '/<a(.*?)href=[\'"]([^\'"]+\.pdf)[\'"](.*?)>(.*?)<\/a>/is';

    return preg_replace_callback( $pattern, function( $matches ) {
        $full_link    = $matches[0];   // Entire matched <a> tag.
        $attributes   = $matches[1] . $matches[3]; // Attributes before and after href.
        $pdf_url      = $matches[2];   // Extracted PDF URL.
        $link_content = $matches[4];   // Content within the <a> tag.

        // Add data-fancybox if it's not already present and not a download link.
        if ( strpos( $attributes, 'data-fancybox=' ) === false && strpos($attributes, 'download') === false ) {
            // Assign a unique group for each PDF link to prevent grouping unless intended.
            $new_link = '<a' . $attributes . ' href="' . esc_url( $pdf_url ) . '" data-fancybox="pdf-viewer-' . uniqid() . '">' . $link_content . '</a>';
            return $new_link;
        }
        return $full_link; // Return original link if already has data-fancybox or download.
    }, $content );
}

/**
 * Adds data-fancybox attributes to standalone image links in post content.
 *
 * This function processes image links that are not part of a Gutenberg gallery,
 * as galleries are handled by `pdfb_add_fancybox_to_gutenberg_blocks`.
 *
 * @since 1.0.0
 *
 * @param string $content The post content.
 * @return string The modified content with Fancybox attributes added to image links.
 */
add_filter( 'the_content', 'pdfb_add_fancybox_to_img_links_in_content', 10 );

function pdfb_add_fancybox_to_img_links_in_content( $content ) {
    if ( ! pdfb_is_enabled('images') ) {
        return $content;
    }

    // Pattern to find <a> tags with href ending in common image extensions.
    // Excludes links that already have data-fancybox or download attributes.
    $pattern = '/<a\s+(?!.*?data-fancybox=[\'"])(?!.*?download[\'"])([^>]*?)href=[\'"]([^\'"]+\.(?:jpg|jpeg|png|gif|webp|avif))[\'"]([^>]*?)>(.*?)<\/a>/is';

    return preg_replace_callback( $pattern, function( $matches ) {
        $attributes_before_href = $matches[1]; // Attributes before href.
        $image_url              = $matches[2]; // Extracted image URL.
        $attributes_after_href  = $matches[3]; // Attributes after href.
        $link_content           = $matches[4]; // Content within the <a> tag.

        // Add data-fancybox="images" to group all standalone images.
        $new_link = '<a' . $attributes_before_href . ' href="' . esc_url( $image_url ) . '" data-fancybox="images" ' . $attributes_after_href . '>' . $link_content . '</a>';
        return $new_link;
    }, $content );
}

/**
 * Adds Fancybox attributes to Gutenberg Gallery and Navigation Block links.
 *
 * This filter runs with a high priority to process blocks before other content filters.
 *
 * @since 1.0.0
 *
 * @param string $block_content The HTML content of the block.
 * @param array $block The block's properties.
 * @return string The modified block content.
 */
add_filter( 'render_block', 'pdfb_add_fancybox_to_gutenberg_blocks', 9, 2 );

function pdfb_add_fancybox_to_gutenberg_blocks( $block_content, $block ) {
    if ( empty( $block_content ) ) {
        return $block_content;
    }

    // Handle Gutenberg Gallery Block.
    if ( 'core/gallery' === $block['blockName'] ) {
        // Pattern to find image links within a gallery block.
        $pattern = '/<a(.*?)href=[\'"]([^\'"]+\.(?:jpg|jpeg|png|gif|webp|avif))[\'"](.*?)>(.*?)<\/a>/is';

        if ( pdfb_is_enabled('galleries') ) {
            // Assign a unique gallery ID for Fancybox grouping.
            $gallery_id = 'gallery-' . uniqid();
            return preg_replace_callback( $pattern, function( $matches ) use ( $gallery_id ) {
                $full_link    = $matches[0];
                $attributes   = $matches[1] . $matches[3];
                $image_url    = $matches[2];
                $link_content = $matches[4];

                // Add data-fancybox with the unique gallery ID if not already present.
                if ( strpos( $attributes, 'data-fancybox=' ) === false ) {
                    return '<a' . $attributes . ' href="' . esc_url( $image_url ) . '" data-fancybox="' . esc_attr($gallery_id) . '">' . $link_content . '</a>';
                }
                return $full_link; // Return original if data-fancybox exists.
            }, $block_content );
        } else {
            // If galleries are not enabled, ensure Fancybox is disabled for these links.
            return preg_replace_callback( $pattern, function( $matches ) {
                $full_link    = $matches[0];
                $attributes   = $matches[1] . $matches[3];
                $image_url    = $matches[2];
                $link_content = $matches[4];

                // Remove any data-fancybox attributes and set data-fancybox="false".
                $attributes = preg_replace('/data-fancybox=[\'"][^\'"]*[\'"]/i', '', $attributes);
                return '<a' . $attributes . ' href="' . esc_url( $image_url ) . '" data-fancybox="false">' . $link_content . '</a>';
            }, $block_content );
        }
    }

    // Handle PDF links within Navigation blocks (e.g., in menus).
    if ( in_array( $block['blockName'], [ 'core/navigation', 'core/navigation-link' ], true ) && pdfb_is_enabled('pdfs') ) {
        // Pattern to find PDF links in navigation elements.
        $pattern = '/<a(.*?)href=[\'"]([^\'"]+\.pdf)[\'"](.*?)>(.*?)<\/a>/is';

        return preg_replace_callback( $pattern, function( $matches ) {
            $full_link    = $matches[0];
            $attributes   = $matches[1] . $matches[3];
            $pdf_url      = $matches[2];
            $link_content = $matches[4];

            // Add data-fancybox if not already present and not a download link.
            if ( strpos( $attributes, 'data-fancybox=' ) === false && strpos($attributes, 'download') === false ) {
                // Assign a unique ID for each PDF link.
                return '<a' . $attributes . ' href="' . esc_url( $pdf_url ) . '" data-fancybox="pdf-viewer-' . uniqid() . '">' . $link_content . '</a>';
            }
            return $full_link; // Return original if already has data-fancybox or download.
        }, $block_content );
    }

    // Return original block content if no changes were made.
    return $block_content;
}

/**
 * Uninstall hook to delete plugin options.
 * This function runs only when the plugin is uninstalled.
 *
 * @since 1.0.0
 */
register_uninstall_hook( __FILE__, 'pdfb_uninstall' );
function pdfb_uninstall() {
    delete_option( PDFB_OPTION_KEY );
}