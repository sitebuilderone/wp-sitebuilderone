# wp-sitebuilderone

All-in-One plugin for local business websites that are WordPress powered, using Bootstrap CSS via the [LiveCanvas](https://www.sitebuilderone.com/go/livecanvas) and Picostrap theme.

## How it works

Using Advanced Custom Fields (ACF) options, areas of the website, such as header, footer and schema are pre-built using WordPress shortcodes.

## Disclaimer: Beta

⚠️ **This plugin is currently in BETA.**  
It is intended for development and testing purposes only and is **not recommended for production use**. Features and functionality may change, and there could be unresolved bugs or issues. Use at your own discretion.

## Requirements

1. [LiveCanvas](https://www.sitebuilderone.com/go/livecanvas) plugin
2. [Picostrap](https://picostrap.com/) theme + blank child theme
3. ACF Pro (Options)
3. Plugin: [Git-updater](https://github.com/afragen/git-updater)

## Setup

1. Install [Git-updater](https://github.com/afragen/git-updater)
2. Point to https://github.com/sitebuilderone/wp-sitebuilderone

![Git updater settings](https://raw.githubusercontent.com/sitebuilderone/wp-sitebuilderone/refs/heads/main/assets/images/gitupdater.png)

## Testing 

User [Shortcodes Finder](https://wordpress.org/plugins/shortcodes-finder/) plugin to view all shortcodes.


# Social Media Shortcodes


Sample .html code for footer or wherever you want to place this

Fill colors are based on brand colors.

```html
<!-- shortcode example -->
 <tangible class="live-refresh">
[social_facebook_link fill="#4267B2"] <!-- Facebook -->
[social_wordpress_link fill="#21759B"] <!-- WordPress -->
[social_youtube_link fill="#FF0000"] <!-- YouTube -->
[social_instagram_link fill="#E4405F"] <!-- Instagram -->
[social_twitter_link fill="#1DA1F2"] <!-- Twitter (now X) -->
[social_google_business_link fill="#4285F4"] <!-- Google  -->
[social_pinterest_link fill="#E60023"] <!-- Pinterest -->
[social_yelp_link fill="#D32323"] <!-- Yelp -->
[social_github_link fill="#333333"] <!-- GitHub -->
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

