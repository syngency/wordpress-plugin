## The Syngency plugin for WordPress ##

Display your Syngency divisions, models, and galleries, on your WordPress website.

### Installation ###

1. Download the [latest version](https://github.com/syngency/wordpress-plugin/archive/master.zip) of the plugin.
1. Unzip the download, and upload the `syngency` folder to the `/wp-content/plugins/` directory of your server.
1. Activate the plugin through the **Plugins** menu in WordPress.
1. Under **Settings > API** in Syngency, check the box labelled _Enable API Access_, click _Save_, and then copy the _API Key_.
1. Paste the API Key to the **Settings > Syngency** section in WordPress. You'll also need to enter your Syngency domain (in the format `yourdomain.syngency.com`)

<img src="https://downloads.intercomcdn.com/i/o/92490016/cd21b7d875239b124b28c393/image.png">

### Options ###

#### Measurements ####
Select which measurement fields are listed on the model portfolio template.

#### Gallery Image Size #####
Select which image size (Small/Medium/Large) will be used to display gallery images.

#### Gallery Images Link To ####
Select which image size (Small/Medium/Large) will be linked to from gallery images.

### Usage ###

[Create a page](https://wordpress.org/support/article/pages/#creating-pages) for your first Syngency division, and place the following shortcode, along with a reference to the URL of the Syngency division you wish to display on that page:

`[syngency url="fashion-models"]`

Additionally, `office_id` and `gender` can be added to filter the division results like this:

`[syngency-division url="fashion-models" office-id="138" gender="female"]`

#### Customising the templates ####

To customise how the pages are presented using the shortcodes above, you can either:

- Override the `.syngency-*` classes in your theme's master stylesheet to show/hide pre-defined elements, like the alphabetical or gender filters.
- Edit the pre-defined templates in `/public/templates/` to make major layout changes.