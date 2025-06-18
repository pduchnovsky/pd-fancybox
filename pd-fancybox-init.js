document.addEventListener("DOMContentLoaded", function () {
  // Ensure pdfbSettings global object is available and enabled.
  if (typeof pdfbSettings === "undefined" || !pdfbSettings.enabled) {
    console.warn(
      "Fancybox: Settings not loaded or disabled. Fancybox might not function as expected."
    );
    return;
  }

  // Retrieve enabled settings from localized data.
  const enabledImages = pdfbSettings.enabled.images;
  const enabledGalleries = pdfbSettings.enabled.galleries;
  const enabledPdfs = pdfbSettings.enabled.pdfs;
  const enabledInline = pdfbSettings.enabled.inline;
  const enabledInlineCloseClick = pdfbSettings.enabled.inline_close_click;

  // --- 1. Images (standalone) ---
  // Binds Fancybox to all links with data-fancybox='images' attribute.
  if (enabledImages) {
    Fancybox.bind("[data-fancybox='images']", {
      // Configuration for image display.
      Thumbs: {
        type: "classic", // Classic thumbnail display.
      },
      Toolbar: {
        // Customize toolbar buttons for image lightbox.
        display: {
          left: ["infobar"], // Show current image index/total.
          middle: [
            "zoomIn",
            "zoomOut",
            "toggle1to1", // Zoom controls.
            "rotateCCW",
            "rotateCW",
            "flipX",
            "flipY", // Image manipulation.
          ],
          right: ["slideshow", "thumbs", "close"], // Slideshow, thumbnails toggle, close button.
        },
      },
      caption: function (fancybox, slide) {
        // Generate caption from data-caption or image alt attribute.
        const triggerEl = slide.trigger; // The <a> tag that triggered Fancybox.
        const imgEl = triggerEl ? triggerEl.querySelector("img") : null; // Find the <img> tag within the <a>.
        return (
          triggerEl.dataset.caption || // Use data-caption attribute if available.
          (imgEl ? imgEl.getAttribute("alt") : "") // Fallback to img alt attribute.
        );
      },
    });
  }

  // --- 2. Image Galleries (Gutenberg Blocks) ---
  // Binds Fancybox to image links within Gutenberg gallery blocks.
  if (enabledGalleries) {
    Fancybox.bind(".wp-block-gallery a[data-fancybox^='gallery-']", {
      groupAttr: "data-fancybox", // Fancybox uses 'data-fancybox' for grouping.
      caption: function (fancybox, slide) {
        // Generate caption from image alt attribute.
        const img = slide.trigger.querySelector("img");
        return img ? img.getAttribute("alt") : "";
      },
      Toolbar: {
        display: {
          left: ["infobar"],
          middle: [
            "zoomIn",
            "zoomOut",
            "toggle1to1",
            "rotateCCW",
            "rotateCW",
            "flipX",
            "flipY",
          ],
          right: ["slideshow", "thumbs", "close"],
        },
      },
      Thumbs: {
        type: "classic",
      },
    });
  }

  // --- 3. PDF Files ---
  // Binds Fancybox to links with data-fancybox^='pdf-viewer-' attribute for PDFs.
  if (enabledPdfs) {
    Fancybox.bind("[data-fancybox^='pdf-viewer-']", {
      type: "iframe", // Open PDF in an iframe.
      iframe: {
        css: {
          width: "80%", // Set iframe width.
          height: "80%", // Set iframe height.
        },
      },
      Toolbar: {
        // Simple toolbar for PDFs.
        display: {
          left: ["infobar"],
          middle: [], // No middle buttons.
          right: ["close"], // Only a close button.
        },
      },
    });
  }

  // --- 4. Inline Content ---
  // Binds Fancybox to links with class 'fancybox-inline' and data-fancybox attribute for inline content.
  if (enabledInline) {
    Fancybox.bind(".fancybox-inline", {
      // Default type is "inline" for content defined on the page.
      Toolbar: {
        display: {
          left: [], // No left buttons.
          middle: [], // No middle buttons.
          right: ["close"], // Only a close button.
        },
      },
      // Add a custom class if 'inline_close_click' is enabled for CSS-based click-to-close behavior.
      mainClass: enabledInlineCloseClick ? "fancybox-inline-close-click" : "",
    });
  }
});
