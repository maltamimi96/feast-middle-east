# Feast in the Middle East WordPress Theme

The repository also includes the embedded Feast Core integration under
`feast-core/`. With Elementor Free active, it adds the Feast Hero Slider,
CMS Collection and Catering Enquiry widgets plus editable global header and
footer locations. No second Hostinger repository is required.

A custom, coded WordPress theme focused on catering enquiries and menu discovery.

## Install

1. Upload `feast-middle-east.zip` in WordPress under Appearance → Themes → Add New → Upload Theme.
2. Activate **Feast in the Middle East**.
3. Go to Settings → General and confirm the Administration Email Address. Catering enquiries are sent there.
4. Test the enquiry form. If messages do not arrive, configure SMTP with Hostinger's email details.

## Visual editing (no paid builder)

Open **Pages → Home** to edit the homepage. Its hero slides, headings, paragraphs, photos, buttons, catering cards, menu lists, process steps, story, gallery and enquiry copy are normal Gutenberg blocks, so they can be selected, moved, duplicated, deleted and styled independently. The editor stacks the hero slides so they are easy to work on; the public website rotates them as a slideshow.

Catering, Menu, Our Story, Gallery and Contact work differently. Each has its own PHP template that builds the page from the Feast CMS, so their packages, dishes, photos and FAQs stay live — add a dish in **Feast CMS → Menu Dishes** and it appears on the menu page with its photo, price and dietary label. On those pages the editor content field is the short intro paragraph only; everything else is edited from the Feast CMS menus below.

The header, footer, colours, fonts and spacing are theme code driven by **Feast CMS → Website Copy** and **Design & Branding**, not by Appearance → Editor. The enquiry form remains a functional Shortcode block so its secure submission handling stays intact. GitHub deployment controls the theme code, while WordPress stores page edits in the database.

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

Use **Appearance → Menus** to manage the Primary and Footer menus, and **Appearance → Customise → Site Identity** to set the logo.

Every page and post also has optional SEO title, meta description, social image, canonical URL and no-index controls. The theme outputs canonical tags, Open Graph/Twitter metadata and JSON-LD for the restaurant, menu, catering service, breadcrumbs and visible FAQs. If Yoast, Rank Math or All in One SEO is installed later, the native frontend SEO output disables itself to prevent duplicate metadata.

Use the WordPress **Featured Image** box to upload images. Use **Page Attributes → Order** to control display order. Published items appear on the website; drafts remain hidden. If a section has no published CMS items, the theme's original content is used as a fallback.
