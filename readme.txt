=== Fancybox 6 plugin for WordPress ===
Contributors: pd
Tags: fancybox, lightbox, image, gallery, pdf, inline, wordpress
Requires at least: 5.0
Tested up to: 6.8.1
Requires PHP: 8.0
Stable tag: 1.2.9
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Integrates Fancybox 6 for various content types including standalone images, Gutenberg galleries, PDF files, and inline content.

== Description ==

This plugin seamlessly integrates the Fancybox 6 library into your WordPress website, providing a modern and responsive lightbox solution for different media types.

**Features include:**

* **Standalone Images:** Automatically applies Fancybox to individual image links within your content.
* **Gutenberg Galleries:** Enhances default Gutenberg image galleries with Fancybox functionality for a consistent user experience.
* **PDF Files:** Opens PDF links in a Fancybox iframe, offering a clean and integrated viewing experience without leaving the page.
* **Inline Content:** Supports opening hidden inline content (e.g., div elements) within a Fancybox modal, perfect for custom pop-ups or hidden information.
* **Configurable Settings:** Provides an intuitive settings page in the WordPress admin area to enable or disable Fancybox for each content type.
* **Customization:** Offers options for inline content behavior, such as allowing closure by clicking anywhere on the modal backdrop.
* **Performance:** Assets are enqueued conditionally, only when relevant content or blocks are detected, optimizing site performance.
* **Conflict Prevention:** Disables default WordPress lightbox scripts to prevent conflicts.

This plugin ensures a consistent and enhanced media viewing experience across your WordPress site.

== Installation ==

1.  **Upload:** Upload the `pd-fancybox` folder to the `/wp-content/plugins/` directory.
2.  **Activate:** Activate the plugin through the 'Plugins' menu in WordPress.
3.  **Configure:** Navigate to 'Settings > Fancybox' in your WordPress admin dashboard to enable Fancybox for desired content types (Images, Galleries, PDFs, Inline Content).
4.  **Usage:**
    * For standalone images, simply link to an image file. The plugin will automatically add `data-fancybox="images"`.
    * For Gutenberg galleries, create a standard image gallery block.
    * For PDF links, link directly to a `.pdf` file. The plugin will add `data-fancybox="pdf-viewer-..."`.
    * For inline content, add the class `fancybox-inline` and a `data-fancybox` attribute to your `<a>` tag, and ensure the `href` points to the ID of the hidden content block (e.g., `<a href="#my-hidden-content" class="fancybox-inline" data-fancybox="inline-content">Open Inline</a><div id="my-hidden-content" style="display:none;">...</div>`).

== Frequently Asked Questions ==

= How do I enable Fancybox for specific content types? =
After activating the plugin, go to `Settings > Fancybox` in your WordPress admin dashboard. You will see checkboxes to enable or disable Fancybox for Images, Galleries, PDF Links, and Inline Content.

= Why are my images not opening in Fancybox? =
* Ensure that "Images (standalone, not in galleries)" is enabled in the Fancybox settings.
* Check if your image links are direct links to image files (e.g., `.jpg`, `.png`, etc.).
* Verify that your image links do not already have a `data-fancybox="false"` or `download` attribute, which would prevent the plugin from modifying them.

= How does Fancybox handle Gutenberg galleries? =
When "Galleries (Gutenberg blocks)" is enabled in settings, the plugin automatically detects and applies Fancybox to images within Gutenberg gallery blocks, grouping them into a slideshow.

= Can I use Fancybox for PDF files? =
Yes, if "PDF Links" is enabled in the settings, any link ending with `.pdf` will automatically open in a Fancybox iframe.

= What is "Inline Content" and how do I use it? =
Inline content refers to HTML content that is hidden on the page and displayed in a Fancybox modal. To use it, create a link with the class `fancybox-inline` and a `data-fancybox` attribute. The `href` of the link should point to the ID of the hidden HTML element you want to display (e.g., `<a href="#my-hidden-div" class="fancybox-inline" data-fancybox="my-custom-inline">Open Content</a><div id="my-hidden-div" style="display:none;">This is my hidden content.</div>`).

= How can I allow users to close inline content by clicking outside the modal? =
In the Fancybox settings, there's an option under "Inline Content Behavior" to "Allow closing inline content by clicking on it". This adds a custom class for CSS-based click-to-close behavior.

= Will this plugin conflict with other lightbox plugins? =
The plugin attempts to prevent conflicts by deregistering and dequeuing common WordPress lightbox scripts (`wp-lightbox` and `wp-block-library-view`). However, if you have other third-party lightbox plugins, it's recommended to disable them to ensure Fancybox functions correctly.

== Screenshots ==

1.  Fancybox settings page in WordPress admin.

== Changelog ==

= 1.2.9 - 2025-06-19 =
* Updated Fancybox library to version 6.0.3.
* Improved `pdfb_should_enqueue_assets()` to check for more blocks that might contain relevant media, including `core/image`, `core/media-text`, `core/navigation`, and `core/navigation-link`.
* Fixed a bug where PDF links in navigation blocks were not correctly processed.
* Updated regex for `pdfb_add_fancybox_to_img_links_in_content` to better exclude existing `data-fancybox` or `download` attributes.
* Ensured `data-fancybox="false"` is applied to Gutenberg gallery images if galleries are disabled, preventing them from opening in Fancybox.

= 1.2.8 =
* Updated Fancybox library to version 5.0.33.
* Adjusted `inline_close_click` functionality to use `pointer-events` for better compatibility and control.
* Improved responsive CSS for toolbar and navigation elements on small screens.

= 1.2.7 =
* Added `data-fancybox` attribute to PDF links with a unique ID to prevent unintended grouping.
* Improved sanitization of plugin settings.

= 1.2.6 =
* Ensured `data-fancybox="images"` is applied consistently to standalone image links.
* Minor code improvements and bug fixes.

= 1.2.5 =
* Initial release with core Fancybox integration for images, galleries, PDFs, and inline content.
