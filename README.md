# Feast in the Middle East WordPress Theme

A custom, coded WordPress theme focused on catering enquiries and menu discovery.

## Install

1. Upload `feast-middle-east.zip` in WordPress under Appearance → Themes → Add New → Upload Theme.
2. Activate **Feast in the Middle East**.
3. Go to Settings → General and confirm the Administration Email Address. Catering enquiries are sent there.
4. Test the enquiry form. If messages do not arrive, configure SMTP with Hostinger's email details.

## Visual editing (no paid builder)

This is a native WordPress block theme. Open **Appearance → Editor** to edit the global header, footer, colours, fonts, page width and template spacing. Open **Pages → Home** (or any dedicated page) to edit the actual nested blocks.

The hero slides, headings, paragraphs, photos, buttons, catering cards, menu lists, process steps, story, gallery and enquiry copy are normal Gutenberg blocks. They can be selected, moved, duplicated, deleted and styled independently. The editor stacks the hero slides so they are easy to work on; the public website rotates them as a slideshow.

The enquiry form remains a functional Shortcode block so its secure submission handling stays intact. Its labels and messages are edited under **Feast CMS → Website Copy**. GitHub deployment still controls the theme code, while WordPress stores visual edits in the database.

## Client CMS

After activation, WordPress contains a **Feast CMS** menu with:

- **Hero Offers** — slideshow headline, supporting text, buttons, links, note, background image, overlay and per-slide colours.
- **Catering Packages** — package name, price, audience, inclusions, badge, destination link and featured styling.
- **Menu Dishes** — dish name, description, price, dietary label, category, image and showcase option.
- **Gallery Images** — upload and order homepage gallery images.
- **Business Settings** — phone, address, Instagram URL and catering enquiry email.
- **Website Copy** — brand text, navigation CTA, homepage headings, category names, trust points, story, process steps, FAQ heading, enquiry form labels, footer and mobile CTA.
- **Design & Branding** — global colours, card radius and hero height.
- **SEO & Local Business** — homepage search title and description, social image, structured address, service area, cuisine, hours, profiles and search-verification codes.
- **Catering FAQs** — questions and answers shown on the catering page.

Use **Appearance → Editor → Design → Navigation** for menus, and select the logo directly in the Header template part.

Every page and post also has optional SEO title, meta description, social image, canonical URL and no-index controls. The theme outputs canonical tags, Open Graph/Twitter metadata and JSON-LD for the restaurant, menu, catering service, breadcrumbs and visible FAQs. If Yoast, Rank Math or All in One SEO is installed later, the native frontend SEO output disables itself to prevent duplicate metadata.

The theme also creates native editable layouts for **Catering**, **Menu**, **Our Story**, **Gallery**, and **Contact**. Every section on those pages is built from the same core Group, Cover, Image, Heading, Paragraph, List, Button, Columns, Gallery and Details blocks.

Use the WordPress **Featured Image** box to upload images. Use **Page Attributes → Order** to control display order. Published items appear on the website; drafts remain hidden. If a section has no published CMS items, the theme's original content is used as a fallback.
