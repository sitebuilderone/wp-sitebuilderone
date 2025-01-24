<?php
/* 
Shortcode for social media properties.

The code relies on Advanced Custom Fields (ACF) Pro with the “Options Page” enabled. Ensure the required ACF fields exist in the WordPress admin under Custom Fields > Options.

use in template:

[social_facebook_link width="3em" height="3em" fill="#4267B2"]
[social_instagram_link class="custom-class"]
[social_twitter_link width="2em" fill="#1DA1F2"]
[social_youtube_link]
[social_google_business_link width="3em" height="3em" fill="#4285F4"]
[social_pinterest_link class="custom-class"]
[social_wordpress_link width="2.5em" height="2.5em" fill="#21759B"]
[social_yelp_link fill="#D32323"]
[social_github_link width="2em" fill="#333333"]

All social media conditional shortcodes

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

*/
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

function display_social_icon_shortcode($atts, $content = null, $shortcode_name = '') {
    // Define a mapping of shortcodes to ACF fields and SVG icons
$social_icons = [
'social_facebook_link' => [
'acf_field' => 'social-facebook',
'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="%s" class="%s" width="%s" height="%s">
<path d="M400 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h137.25V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.27c-30.81 0-40.42 19.12-40.42 38.73V256h68.78l-11 71.69h-57.78V480H400a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48z"></path>
</svg>',
],
'social_instagram_link' => [
'acf_field' => 'social-instagram',
'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="%s" class="%s" width="%s" height="%s">
<path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zM8 1.442z"></path>
</svg>',
],
'social_linkedin_link' => [
'acf_field' => 'social-linkedin',
'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="%s" class="%s" width="%s" height="%s">
<path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"></path>
</svg>',
],
'social_youtube_link' => [
'acf_field' => 'social-youtube',
'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="%s" class="%s" width="%s" height="%s">
<path d="M186.8 202.1l95.2 54.1-95.2 54.1V202.1zM448 80v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48zm-42 176.3s0-59.6-7.6-88.2c-4.2-15.8-16.5-28.2-32.2-32.4C337.9 128 224 128 224 128s-113.9 0-142.2 7.7c-15.7 4.2-28 16.6-32.2 32.4-7.6 28.5-7.6 88.2-7.6 88.2s0 59.6 7.6 88.2c4.2 15.8 16.5 27.7 32.2 31.9C110.1 384 224 384 224 384s113.9 0 142.2-7.7c15.7-4.2 28-16.1 32.2-31.9 7.6-28.5 7.6-88.1 7.6-88.1z"></path>
</svg>',
],
'social_google_business_link' => [
'acf_field' => 'social-google_business',
'svg' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 24 24" fill="%s" class="%s" width="%s" height="%s">
<path d="M18.27 6C19.28 8.17 19.05 10.73 17.94 12.81C17 14.5 15.65 15.93 14.5 17.5C14 18.2 13.5 18.95 13.13 19.76C13 20.03 12.91 20.31 12.81 20.59C12.71 20.87 12.62 21.15 12.53 21.43C12.44 21.69 12.33 22 12 22H12C11.61 22 11.5 21.56 11.42 21.26C11.18 20.53 10.94 19.83 10.57 19.16C10.15 18.37 9.62 17.64 9.08 16.93L18.27 6M9.12 8.42L5.82 12.34C6.43 13.63 7.34 14.73 8.21 15.83C8.42 16.08 8.63 16.34 8.83 16.61L13 11.67L12.96 11.68C11.5 12.18 9.88 11.44 9.3 10C9.22 9.83 9.16 9.63 9.12 9.43C9.07 9.06 9.06 8.79 9.12 8.43L9.12 8.42M6.58 4.62L6.57 4.63C4.95 6.68 4.67 9.53 5.64 11.94L9.63 7.2L9.58 7.15L6.58 4.62M14.22 2.36L11 6.17L11.04 6.16C12.38 5.7 13.88 6.28 14.56 7.5C14.71 7.78 14.83 8.08 14.87 8.38C14.93 8.76 14.95 9.03 14.88 9.4L14.88 9.41L18.08 5.61C17.24 4.09 15.87 2.93 14.23 2.37L14.22 2.36M9.89 6.89L13.8 2.24L13.76 2.23C13.18 2.08 12.59 2 12 2C10.03 2 8.17 2.85 6.85 4.31L6.83 4.32L9.89 6.89Z"></path>
</svg>',
],

'social_pinterest_link' => [
'acf_field' => 'social-pinterest',
'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="%s" class="%s" width="%s" height="%s">
<path d="M448 80v352c0 26.5-21.5 48-48 48H154.4c9.8-16.4 22.4-40 27.4-59.3 3-11.5 15.3-58.4 15.3-58.4 8 15.3 31.4 28.2 56.3 28.2 74.1 0 127.4-68.1 127.4-152.7 0-81.1-66.2-141.8-151.4-141.8-106 0-162.2 71.1-162.2 148.6 0 36 19.2 80.8 49.8 95.1 4.7 2.2 7.1 1.2 8.2-3.3.8-3.4 5-20.1 6.8-27.8.6-2.5.3-4.6-1.7-7-10.1-12.3-18.3-34.9-18.3-56 0-54.2 41-106.6 110.9-106.6 60.3 0 102.6 41.1 102.6 99.9 0 66.4-33.5 112.4-77.2 112.4-24.1 0-42.1-19.9-36.4-44.4 6.9-29.2 20.3-60.7 20.3-81.8 0-53-75.5-45.7-75.5 25 0 21.7 7.3 36.5 7.3 36.5-31.4 132.8-36.1 134.5-29.6 192.6l2.2.8H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48z"></path>
</svg>',
],

'social_wordpress_link' => [
'acf_field' => 'social-wordpress',
'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="%s" class="%s" width="%s" height="%s">
<path d="M61.7 169.4l101.5 278C92.2 413 43.3 340.2 43.3 256c0-30.9 6.6-60.1 18.4-86.6zm337.9 75.9c0-26.3-9.4-44.5-17.5-58.7-10.8-17.5-20.9-32.4-20.9-49.9 0-19.6 14.8-37.8 35.7-37.8.9 0 1.8.1 2.8.2-37.9-34.7-88.3-55.9-143.7-55.9-74.3 0-139.7 38.1-177.8 95.9 5 .2 9.7.3 13.7.3 22.2 0 56.7-2.7 56.7-2.7 11.5-.7 12.8 16.2 1.4 17.5 0 0-11.5 1.3-24.3 2l77.5 230.4L249.8 247l-33.1-90.8c-11.5-.7-22.3-2-22.3-2-11.5-.7-10.1-18.2 1.3-17.5 0 0 35.1 2.7 56 2.7 22.2 0 56.7-2.7 56.7-2.7 11.5-.7 12.8 16.2 1.4 17.5 0 0-11.5 1.3-24.3 2l76.9 228.7 21.2-70.9c9-29.4 16-50.5 16-68.7zm-139.9 29.3l-63.8 185.5c19.1 5.6 39.2 8.7 60.1 8.7 24.8 0 48.5-4.3 70.6-12.1-.6-.9-1.1-1.9-1.5-2.9l-65.4-179.2zm183-120.7c.9 6.8 1.4 14 1.4 21.9 0 21.6-4 45.8-16.2 76.2l-65 187.9C426.2 403 468.7 334.5 468.7 256c0-37-9.4-71.8-26-102.1zM504 256c0 136.8-111.3 248-248 248C119.2 504 8 392.7 8 256 8 119.2 119.2 8 256 8c136.7 0 248 111.2 248 248zm-11.4 0c0-130.5-106.2-236.6-236.6-236.6C125.5 19.4 19.4 125.5 19.4 256S125.6 492.6 256 492.6c130.5 0 236.6-106.1 236.6-236.6z"></path>
</svg>',
],

'social_yelp_link' => [
'acf_field' => 'social-yelp',
'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="%s" class="%s" width="%s" height="%s">
<path d="M42.9 240.32l99.62 48.61c19.2 9.4 16.2 37.51-4.5 42.71L30.5 358.45a22.79 22.79 0 0 1-28.21-19.6 197.16 197.16 0 0 1 9-85.32 22.8 22.8 0 0 1 31.61-13.21zm44 239.25a199.45 199.45 0 0 0 79.42 32.11A22.78 22.78 0 0 0 192.94 490l3.9-110.82c.7-21.3-25.5-31.91-39.81-16.1l-74.21 82.4a22.82 22.82 0 0 0 4.09 34.09zm145.34-109.92l58.81 94a22.93 22.93 0 0 0 34 5.5 198.36 198.36 0 0 0 52.71-67.61A23 23 0 0 0 364.17 370l-105.42-34.26c-20.31-6.5-37.81 15.8-26.51 33.91zm148.33-132.23a197.44 197.44 0 0 0-50.41-69.31 22.85 22.85 0 0 0-34 4.4l-62 91.92c-11.9 17.7 4.7 40.61 25.2 34.71L366 268.63a23 23 0 0 0 14.61-31.21zM62.11 30.18a22.86 22.86 0 0 0-9.9 32l104.12 180.44c11.7 20.2 42.61 11.9 42.61-11.4V22.88a22.67 22.67 0 0 0-24.5-22.8 320.37 320.37 0 0 0-112.33 30.1z"></path>
</svg>',
],

'social_github_link' => [
'acf_field' => 'social-github',
'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="%s" class="%s" width="%s" height="%s">
<path d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zM277.3 415.7c-8.4 1.5-11.5-3.7-11.5-8 0-5.4.2-33 .2-55.3 0-15.6-5.2-25.5-11.3-30.7 37-4.1 76-9.2 76-73.1 0-18.2-6.5-27.3-17.1-39 1.7-4.3 7.4-22-1.7-45-13.9-4.3-45.7 17.9-45.7 17.9-13.2-3.7-27.5-5.6-41.6-5.6-14.1 0-28.4 1.9-41.6 5.6 0 0-31.8-22.2-45.7-17.9-9.1 22.9-3.5 40.6-1.7 45-10.6 11.7-15.6 20.8-15.6 39 0 63.6 37.3 69 74.3 73.1-4.8 4.3-9.1 11.7-10.6 22.3-9.5 4.3-33.8 11.7-48.3-13.9-9.1-15.8-25.5-17.1-25.5-17.1-16.2-.2-1.1 10.2-1.1 10.2 10.8 5 18.4 24.2 18.4 24.2 9.7 29.7 56.1 19.7 56.1 19.7 0 13.9.2 36.5.2 40.6 0 4.3-3 9.5-11.5 8-66-22.1-112.2-84.9-112.2-158.3 0-91.8 70.2-161.5 162-161.5S388 165.6 388 257.4c.1 73.4-44.7 136.3-110.7 158.3z"></path>
</svg>',
],

'social_twitter_x_link' => [
'acf_field' => 'socal-twitter-x',
'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="%s" class="%s" width="%s" height="%s">
<path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"></path>
</svg>',
],

'social_bing_link' => [
    'acf_field' => 'social-bing',
    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="%s" class="%s" width="%s" height="%s">
    <path d="M9.46 0v256.2c0 4.34 1.45 8.58 4.11 12.05L152.9 451.38l.16-.3L328.68 512 9.46 0zm191.9 317.2l152.88 102.3-.03.02L240.86 402 201.36 352.1zM502.5 196.4L342.57 90.38c-7.5-4.9-17.34 1.13-15.38 9.87L400.47 294.1c.36 1.1.44 2.28.22 3.42-.2 1.14-.68 2.18-1.38 3.07l-49.52 60.52 134.5-99.42c6.9-5.2 8.4-15.1 3-22.2z"></path>
    </svg>',
],

'social_tiktok_link' => [
    'acf_field' => 'social-tiktok',
    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="%s" class="%s" width="%s" height="%s">
    <path d="M448 209.9c-8.19 3.62-16.9 6.07-26.02 7.13v228.6c0 41.18-33.43 74.62-74.61 74.62H74.61C33.43 520.2 0 486.7 0 445.5V74.61C0 33.43 33.43 0 74.61 0h272.8v94.72c0 50.12 40.61 90.74 90.73 90.74H448zm-247.2 43.4c-38.36 0-70.92 29.8-70.92 67.86 0 38.06 32.56 67.86 70.92 67.86 38.36 0 70.91-29.8 70.91-67.86V151.5h63.83c-2.05 47.73-44.24 85.1-92.65 85.1h-42.1v152.7c0 4.64-3.76 8.4-8.4 8.4H200.8c-4.64 0-8.4-3.76-8.4-8.4V281.1h-42.1c-48.41 0-90.6-37.37-92.65-85.1h63.83v113.8c0 4.64-3.76 8.4-8.4 8.4h-42.1c-48.41 0-90.6-37.37-92.65-85.1h63.83v113.8z"></path>
    </svg>',
],

'social_snapchat_link' => [
    'acf_field' => 'social-snapchat',
    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="%s" class="%s" width="%s" height="%s">
    <path d="M314.5 320.4c-.1-.2-.2-.3-.3-.5-9.7-21.4 23.3-39 27.2-40.9 66.6-32.3 106.6-80.6 106.6-126.4 0-118.8-103.3-153.4-204.3-153.4s-204.3 34.6-204.3 153.4c0 45.8 40.1 94.2 106.6 126.4 4.3 2.1 36.4 19.6 27.2 40.9 0 0-.2.3-.3.5-21.4 33.7-46.4 62.6-57.7 82.6-7.3 12.7 12.2 25.5 22.2 25.5h221c10 0 29.5-12.8 22.2-25.5-11.3-20-36.3-48.9-57.7-82.6z"></path>
    </svg>',
],

'social_reddit_link' => [
    'acf_field' => 'social-reddit',
    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="%s" class="%s" width="%s" height="%s">
    <path d="M440.2 179.9c-13.8 0-25 11.2-25 25s11.2 25 25 25 25-11.2 25-25-11.2-25-25-25zm-82.1 74.5c-33.5 0-62.9 19.3-76.2 47.2-6.7 13.8-6.5 31.3.3 44.8 13.6 28.7 48.1 43.5 85.9 36.2 5.5-1 8.3-6.6 6-11.7-9.6-21.8-35.2-37-64.4-37-13.4 0-25.9 2.6-37.3 7.2-14.1 5.9-22.4 16.4-22.4 28.6 0 13.7 10.5 25.4 25.6 27.5-20.7 4.4-44.7 3.7-68.4-3.5-33.8-10.3-60-33.4-73.1-61.7-2.4-4.9-8.4-6.6-12.8-3.4-6.3 4.8-12.5 9.6-19 14.1-1.8 1.3-2.9 3.4-3.2 5.6-.2 2.2.4 4.4 1.6 6.2 21.3 32 56.3 50.8 96.1 50.8 38.8 0 74.6-17.6 99.2-46.3 17-20.3 26.6-44.3 26.6-68.4 0-23.8-9.2-45.6-25.8-62.6-13.1-13.5-30.3-20.9-48.8-20.9zm-214.1 43c-13.8 0-25 11.2-25 25s11.2 25 25 25 25-11.2 25-25-11.2-25-25-25zm45.8-56.3c6.3-12.5 10.5-26.5 12.4-41.6l8.1-56.2 38.5 15.7c13.5 5.5 28.7 5.5 42.3 0l38.5-15.7 8.1 56.2c1.9 15.1 6.1 29.1 12.4 41.6h20.7c6.6 0 11.8 5.3 11.8 11.8v107.7c0 22-12.4 41.4-32.3 53.1l-.2.1c-7.4 4.1-17.2-.3-19.2-8.4l-17.7-66.8c-5.7-21.5-23.3-36.8-43.6-36.8h-83.8c-20.3 0-37.9 15.3-43.6 36.8l-17.7 66.8c-2 8.1-11.8 12.5-19.2 8.4l-.2-.1c-19.9-11.7-32.3-31.1-32.3-53.1V179.9c0-6.6 5.3-11.8 11.8-11.8h20.7z"></path>
    </svg>',
],

'social_tripadvisor_link' => [
    'acf_field' => 'social-tripadvisor',
    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="%s" class="%s" width="%s" height="%s">
    <path d="M179.2 206.7c0 35.2-28.5 63.8-63.8 63.8-35.2 0-63.8-28.5-63.8-63.8 0-35.2 28.5-63.8 63.8-63.8s63.8 28.5 63.8 63.8zM263.7 224c0-14.8 10.9-27.6 27.6-27.6 14.8 0 27.6 10.9 27.6 27.6 0 17.1-10.9 27.6-27.6 27.6-17.1 0-27.6-10.5-27.6-27.6z"></path>
    </svg>',
],

'social_whatsapp_link' => [
    'acf_field' => 'social-whatsapp',
    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="%s" class="%s" width="%s" height="%s">
    <path d="M380.9 97.3C339.8 56.2 285.6 32 224.2 32c-126.1 0-224 97.8-224 224 0 39.6 10.1 76.6 27.7 109.2L0 480l115.5-27.2C150 501.9 186 512 224.1 512c125.7 0 224-96.3 224-223.9.1-61.7-23.9-116-67.2-158.8z"></path>
    </svg>',
],

'social_bbb_link' => [
    'acf_field' => 'social-bbb',
    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="%s" class="%s" width="%s" height="%s">
    <path d="M256 0c80.5 0 150.3 44.2 186.4 107.5L352 190.6H162.1L69.6 107.5C105.7 44.2 175.5 0 256 0zm0 128c-80.5 0-150.3 44.2-186.4 107.5l91.6 83.2h189.8l91.6-83.2C406.3 172.2 336.5 128 256 128zm0 224c-80.5 0-150.3 44.2-186.4 107.5l91.6 83.2H350.8L442.4 459.5C406.3 396.2 336.5 352 256 352z"></path>
    </svg>',
],
];

    // Check if the current shortcode matches a defined social icon
    if (!isset($social_icons[$shortcode_name])) {
        return '';
    }

    // Get ACF value
    $acf_field = $social_icons[$shortcode_name]['acf_field'];
    $url = get_field($acf_field, 'option');
    if (!$url) return '';

    // Parse attributes
    $atts = shortcode_atts([
        'width' => '2.1em',
        'height' => '2.1em',
        'fill' => 'currentColor',
        'class' => 'text-dark',
    ], $atts, $shortcode_name);

    $svg = sprintf($social_icons[$shortcode_name]['svg'], $atts['fill'], $atts['class'], $atts['width'], $atts['height']);
    return '<a class="text-decoration-none" 
    target="_blank"
    rel="noopener noreferrer"
    itemprop="sameAs"
    href="' . esc_url($url) . '">' . $svg . '</a>';
}

// Register Shortcodes
add_shortcode('social_facebook_link', 'display_social_icon_shortcode');
add_shortcode('social_instagram_link', 'display_social_icon_shortcode');
add_shortcode('social_linkedin_link', 'display_social_icon_shortcode');
add_shortcode('social_youtube_link', 'display_social_icon_shortcode');
add_shortcode('social_twitter_x_link', 'display_social_icon_shortcode');
add_shortcode('social_google_business_link', 'display_social_icon_shortcode');
add_shortcode('social_pinterest_link', 'display_social_icon_shortcode');
add_shortcode('social_wordpress_link', 'display_social_icon_shortcode');
add_shortcode('social_yelp_link', 'display_social_icon_shortcode');
add_shortcode('social_github_link', 'display_social_icon_shortcode');
add_shortcode('social_bing_link', 'display_social_icon_shortcode');
add_shortcode('social_tiktok_link', 'display_social_icon_shortcode');
add_shortcode('social_snapchat_link', 'display_social_icon_shortcode');
add_shortcode('social_reddit_link', 'display_social_icon_shortcode');
add_shortcode('social_tripadvisor_link', 'display_social_icon_shortcode');
add_shortcode('social_whatsapp_link', 'display_social_icon_shortcode');
add_shortcode('social_bbb_link', 'display_social_icon_shortcode');

?>