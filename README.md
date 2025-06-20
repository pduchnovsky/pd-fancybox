# Fancybox 6 plugin for WordPress

Nothing fancy (eh ?)

## Description

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

## Installation

1.  **Upload:** Upload the `pd-fancybox` folder to the `/wp-content/plugins/` directory.
2.  **Activate:** Activate the plugin through the 'Plugins' menu in WordPress.
3.  **Configure:** Navigate to 'Settings > Fancybox' in your WordPress admin dashboard to enable Fancybox for desired content types (Images, Galleries, PDFs, Inline Content).
4.  **Usage:**
    * For standalone images, simply link to an image file. The plugin will automatically add `data-fancybox="images"`.
    * For Gutenberg galleries, create a standard image gallery block.
    * For PDF links, link directly to a `.pdf` file. The plugin will add `data-fancybox="pdf-viewer-..."`.
    * For inline content, add the class `fancybox-inline` and a `data-fancybox` attribute to your `<a>` tag, and ensure the `href` points to the ID of the hidden content block (e.g., `<a href="#my-hidden-content" class="fancybox-inline" data-fancybox="inline-content">Open Inline</a><div id="my-hidden-content" style="display:none;">...</div>`).

## Screenshots

1.  **Fancybox settings page in WordPress admin.**
    ![screenshot](images/scr.png)

## Changelog

### 1.2.9 - 2025-06-19

* Updated Fancybox library to version 6.0.3.
* Improved `pdfb_should_enqueue_assets()` to check for more blocks that might contain relevant media, including `core/image`, `core/media-text`, `core/navigation`, and `core/navigation-link`.
* Fixed a bug where PDF links in navigation blocks were not correctly processed.
* Updated regex for `pdfb_add_fancybox_to_img_links_in_content` to better exclude existing `data-fancybox` or `download` attributes.
* Ensured `data-fancybox="false"` is applied to Gutenberg gallery images if galleries are disabled, preventing them from opening in Fancybox.

### 1.2.8

* Updated Fancybox library to version 5.0.33.
* Adjusted `inline_close_click` functionality to use `pointer-events` for better compatibility and control.
* Improved responsive CSS for toolbar and navigation elements on small screens.

### 1.2.7

* Added `data-fancybox` attribute to PDF links with a unique ID to prevent unintended grouping.
* Improved sanitization of plugin settings.

### 1.2.6

* Ensured `data-fancybox="images"` is applied consistently to standalone image links.
* Minor code improvements and bug fixes.

### 1.2.5

* Initial release with core Fancybox integration for images, galleries, PDFs, and inline content.