wp-seo
======

## WordPress Plugin to Output Metadata/Open Graph Data in <head> element
If you use custom meta fields in your project (possibly by means of  the Advanced Custom Fields plugin), then you already have a good way of capturing custom meta-descriptions and titles for search engine optimization purposes.

This plugin will take postmeta data and mark it up appropriately and include it in the page `<head>` element, where search engines can access it.

This allows site editors to fine-tune the meta-description and title tag elements on a page by page basis, without the bloat and annoying adverts of a full-featured SEO plugin. If you manage your site properly, the end result of this simple plugin will probably be just as useful.

This plugin preferentially uses data stored as postmeta fields:

* `meta_description`
* `title_tag`

Falls back to rational defaults (i.e. post/page excerpt). Just include meta boxes to capture these metadata fields on any content type that you want to have access to the fine-tuning functionality. If the field is populated, it will be included in `<head>`. Open Graph tags are also constructed and included, using the site name and the post featured image for the og image tag.
