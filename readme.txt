=== Faqora FAQ Schema Blocks ===
Contributors: yevhenhud
Tags: faq, schema, json-ld, seo, shortcode
Requires at least: 5.8
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Create reusable FAQ blocks with shortcodes and automatically output FAQPage JSON-LD schema markup.

== Description ==

Faqora FAQ Schema Blocks is a lightweight WordPress plugin for creating reusable FAQ sections and displaying them anywhere with a shortcode.

The plugin helps site owners, SEO specialists, content managers, agencies, and WordPress developers publish clear question-and-answer blocks while automatically adding FAQPage JSON-LD structured data.

Basic shortcode:

`[faq_schema_block id="123"]`

Optional title:

`[faq_schema_block id="123" title="Frequently Asked Questions"]`

Disable schema for one block:

`[faq_schema_block id="123" schema="false"]`

Enable accordion mode for one block:

`[faq_schema_block id="123" accordion="true"]`

= Features =

* Custom admin section for FAQ blocks
* Unlimited questions and answers per block
* Shortcode output for posts, pages, widgets, and custom post types
* Automatic FAQPage JSON-LD schema markup
* Optional native details/summary accordion mode
* Lightweight default CSS that can be disabled
* Translation-ready text domain
* No external API calls
* No tracking
* No hidden frontend links

= Who is it for? =

* SEO specialists
* WordPress agencies
* Local business websites
* Service pages
* E-commerce category pages
* Blogs and content-heavy websites
* AI SEO / AEO / GEO content workflows

= About =

Developed by ProfiCRM-UA, a Ukrainian WordPress, CRM and SEO automation team.

The plugin was inspired by practical SEO workflows used for service pages, local business websites, e-commerce categories, blogs, and content-heavy WordPress projects.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/` or install the ZIP file via the WordPress admin.
2. Activate the plugin.
3. Go to FAQ Schema → Add New.
4. Add questions and answers.
5. Copy the generated shortcode and paste it into any page, post, widget, or custom post type.

== Frequently Asked Questions ==

= Does this plugin add FAQPage schema automatically? =

Yes. When a FAQ block is displayed with the shortcode, the plugin outputs JSON-LD FAQPage schema in the page footer.

= Can I disable schema for a specific block? =

Yes. Use `schema="false"` in the shortcode.

= Can I disable the default CSS? =

Yes. Go to FAQ Schema → Settings and disable default plugin styles.

= Does it work with Elementor, Gutenberg, or classic editor? =

Yes. The plugin uses a shortcode, so it works anywhere WordPress shortcodes are supported.

= Does it call external APIs? =

No. The plugin stores FAQ content locally in WordPress and does not call external APIs.

= Does it add hidden links? =

No. The plugin does not add hidden frontend links or branding links.

== Screenshots ==

1. FAQ block editor
2. Shortcode column in FAQ blocks list
3. Frontend FAQ output
4. Plugin settings

== Changelog ==

= 1.0.2 =
* Removed manual translation loading for WordPress.org compatibility.
* Improved sanitization for submitted FAQ items.
* Updated readme headers and limited tags for WordPress.org Plugin Check.

= 1.0.1 =
* Added automatic frontend H2 output from the FAQ block title when no custom shortcode title is provided.
* Updated readme description and removed project-specific references.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.2 =
Plugin Check compatibility improvements.

= 1.0.1 =
Adds automatic FAQ block title output on the frontend.

= 1.0.0 =
Initial release.
