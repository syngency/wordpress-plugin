=== Syngency ===
Contributors: syngency
Tags: syngency
Requires at least: 4.6
Tested up to: 5.2
Stable tag: 4.3
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display Syngency divisions, models, and galleries on your WordPress website.

== Description ==

Syngency's WordPress plugin enables you to display your divisions, models, and galleries from Syngency on your WordPress website.
Any changes made in Syngency are reflected on your WordPress site instantly, and you have complete control of the way the division and model portfolio templates that are displayed on your site.

Important: Use of this plugin is in accordance with the [Syngency Terms of Service](https://syngency.com).

== Installation ==

1. Install and activate the `Syngency` plugin from the **Plugins** menu in WordPress
1. Under **Settings > API** in Syngency, check the box labelled _Enable API Access_, click _Save_, and then copy the _API Key_.
1. Paste the API Key to the **Settings > Syngency** section in WordPress. You'll also need to enter your Syngency domain (in the format `yourdomain.syngency.com`)

<img src="https://downloads.intercomcdn.com/i/o/92490016/cd21b7d875239b124b28c393/image.png">

== Options ==

#### Measurements ####
Select which measurement fields are listed on the model portfolio template.

#### Gallery Image Size #####
Select which image size (Small/Medium/Large) will be used to display gallery images.

#### Gallery Images Link To ####
Select which image size (Small/Medium/Large) will be linked to from gallery images.

#### Templates ####

The **Division** and **Model** templates use a combination of HTML, CSS, [Liquid](https://www.shopify.com/partners/shopify-cheat-sheet), and Javascript (optional) to control the look and functionality of the pages rendered by the plugin. Any changes made to these templates will be reflected on your site.

== Usage ==

[Create a page](https://wordpress.org/support/article/pages/#creating-pages) for your first Syngency division, and place the following shortcode, along with a reference to the URL of the Syngency division you wish to display on that page:

`[syngency division="fashion-models"]`

Additionally, the `office` attribute, and division gender filter (men/women/boys/girls/non-binary) can be added to filter the division results:

`[syngency office="chicago" division="fashion-models/women"]`

The `office` attribute must be the name of the associated office subdomain you have added under **Settings > Domains** in Syngency.