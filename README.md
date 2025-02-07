# Local business website (beta)

All-in-One plugin for local business websites that are WordPress powered, using [Bootstrap](https://getbootstrap.com/) with the [LiveCanvas](https://www.sitebuilderone.com/go/livecanvas) plugin and [Picostrap](https://picostrap.com/) theme.

This plugin standardizes the management of local business data & [Services](SERVICES.md) within WordPress by integrating with Advanced Custom Fields (ACF) and syncing JSON data to GitHub for version control.

## Key Features

- **Business Information Management**: Allows businesses to input and update essential details such as name, address, and contact information.
- **Services:** Custom Post Type for [Services](SERVICES.md): Each business can define its unique set of services within a dedicated custom post type.
- **Marketing:** Call to action, testimonials, Google reviews
- SEO Optimization: Every service includes fields for SEO elements (title, meta descriptions, keywords) to enhance visibility.
- Schema Integration: Automatically generates structured data (schema.org) for each service page to improve search engine discoverability and compliance with modern SEO practices.
- Dynamic Schema Output: Ensures schema data is output on each service page for search engines to crawl effectively.
- Social media properties database (Facebook, LinkedIn, etc)
- Checklist of tasks for website owners/editors
- Shortcodes for integration
- Conditional shortcodes for social properties

## Objectives
- Streamline the process of adding and maintaining local business services with a standardized structure.
- Enhance search engine rankings and visibility by leveraging schema and SEO elements.
- Provide an extensible foundation for businesses to grow their digital presence using modern WordPress development practices.
- Provide guidance in managing the website with tasks & checklists

## Technical Highlights:
- Multiple editors can add or edit business information in WordPress
- Built using ACF for flexible and user-friendly field management.
- JSON sync to GitHub ensures robust version control and team collaboration.
- JSON to manage tasks/checklists
- Compatible with WordPress themes utilizing the Bootstrap framework, ensuring responsive and accessible design.


### Business information includes:

- Business details: address, contact, location, etc.
- Social media properties: Facebook, Instagram, LinkedIn, etc.
- Integration codes: Google Analytics, Tag Manager, tracking scripts, etc.
- Project support details: Project management tools, contact support.
- [Services](SERVICES.md)


## How it works

Using Advanced Custom Fields (ACF) options, areas of the website, such as header, footer and schema are pre-built using WordPress shortcodes.

### Local JSON
Global options are served/saved via .JSON files via the 'acf-json' folder. This feature saves field groups, post types, taxonomies, and option pages as JSON files.

More information about [ACF Local JSON](https://www.advancedcustomfields.com/resources/local-json/)

## Beta

⚠️ **This plugin is currently in BETA.**  
It is intended for development and testing purposes only and is **not recommended for production use**. 

Features and functionality may change, and there could be unresolved bugs or issues. Use at your own discretion.

## Requirements

### Plugins
- [Advanced Custom Fields (ACF) Pro](https://www.advancedcustomfields.com/) - Custom fields & options
- [LiveCanvas](https://www.sitebuilderone.com/go/livecanvas) - Bootstrap HTML page builder for WordPress
- [Git-updater](https://github.com/afragen/git-updater) - for retrieving from GitHub repository

### Theme
- [Picostrap](https://picostrap.com/) Bootstrap based theme + blank child theme

### Optional

- [LocalWP](https://localwp.com/) for local WordPress development & testing


## Setup

1. Install [Git-updater](https://github.com/afragen/git-updater)
2. Point to https://github.com/sitebuilderone/wp-sitebuilderone
3. Use the 'main' branch.

![Git updater settings](https://raw.githubusercontent.com/sitebuilderone/wp-sitebuilderone/refs/heads/main/assets/images/gitupdater.png)


# Examples: Social Media Shortcodes


Sample .html code for footer or wherever you want to place this. 

These are conditional shortcodes that render SVG icon with brand colors.

```html
<!-- shortcode example -->
 <tangible class="live-refresh">
[social_facebook_link fill="#4267B2"] <!-- Facebook -->
[social_linkedin_link fill="#0A66C2"] <!-- LinkedIn -->
[social_instagram_link fill="#E4405F"] <!-- Instagram -->
[social_google_business_link fill="#4285F4"] <!-- Google Business -->
[social_youtube_link fill="#FF0000"] <!-- YouTube -->
[social_twitter_x_link fill="#1DA1F2"] <!-- Twitter-X -->
[social_pinterest_link fill="#E60023"] <!-- Pinterest -->
[social_wordpress_link fill="#21759B"] <!-- WordPress -->
[social_yelp_link fill="#D32323"] <!-- Yelp -->
[social_github_link fill="#333333"] <!-- GitHub -->
[social_bing_link fill="#008373"] <!-- Bing -->
[social_tiktok_link fill="#010101"] <!-- TikTok -->
[social_snapchat_link fill="#FFFC00"] <!-- Snapchat -->
[social_reddit_link fill="#FF4500"] <!-- Reddit -->
[social_tripadvisor_link fill="#00AF87"] <!-- TripAdvisor -->
[social_whatsapp_link fill="#25D366"] <!-- WhatsApp -->
[social_bbb_link fill="#00457C"] <!-- Better Business Bureau (BBB) -->
</tangible>
```

Sample html output

```html
<a class="text-decoration-none lc-rendered-shortcode-wrap lc-rendered-tangible" target="_blank" rel="noopener noreferrer" itemprop="sameAs" href="https://www.facebook.com/sitebuilderone/">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="#4267B2" class="text-dark" width="2.1em" height="2.1em">
    <path d="M400 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h137.25V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.27c-30.81 0-40.42 19.12-40.42 38.73V256h68.78l-11 71.69h-57.78V480H400a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48z"></path>
</svg>
</a>
```



## Changelog

For details on changes in this project, see the [Changelog](CHANGELOG.md).

