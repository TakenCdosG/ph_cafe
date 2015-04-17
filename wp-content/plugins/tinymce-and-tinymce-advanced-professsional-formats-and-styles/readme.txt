=== TinyMCE and TinyMCE Advanced Professsional Formats and Styles ===
Contributors: blackbam
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VNS6BVGVH69P6
Tags: tinymce, advanced, visual, editor, style, custom
Requires at least: 3.0
Tested up to: 3.6
Stable tag: 1.1.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Improve, style and customize TinyMCE / TinyMCE Advanced using custom editor stylesheets and a dynamically configurable styles dropdown.

== Description ==

Clients tend to get crazy if they have to edit HTML code on their own or if little things are not working as expected. Make your editing experience as simple and good as possible: 

Customize TinyMCE / TinyMCE Advanced (or other TinyMCE variants) to fit your needs.

Basically, this Plugin does two things for you:

A. This Plugin installs two files editor-style.css and editor-style-shared.css into your Theme folder (so you can still do updates of the Plugin), which can be edited in the backend of your site

- editor-style.css is used for styling your TinyMCE Editor
- editor-style-shared.css is used for styles to be used in your theme AND in your TinyMCE editor (so you do not have to copy)
	
Note: To use this feature, you have to write your own CSS code into this files, which is the only professional way to do it.

B. The main feature of this Plugin is to offer a GUI to create custom style formats for TinyMCE dynamically

- Easy and very fast to add/change/delete
- You do not have to change one single line of source code

Current Languages: 

- en_US
- de_DE
- es_ES (Andrew Kurtis, webhostinghub.com)
- sr_RS (Ognjen Djuraskovic, firstsiteguide.com)

Please report any bugs you may encounter and support this Plugin if you like it.

Plugin URL: <a href="http://www.blackbam.at/blackbams-blog/2013/06/03/wordpress-plugin-tinymce-and-tinymce-advanced-professsional-formats-and-styles/">http://www.blackbam.at/blackbams-blog/2013/06/03/wordpress-plugin-tinymce-and-tinymce-advanced-professsional-formats-and-styles/</a>

== Installation ==

1. Upload the Plugin to your wp-content/plugins/ folder
2. Activate the Plugin
3. Go to Settings -> TinyMCE Prof. Styles
4. Follow the instructions on the screen - write your CSS and create your custom formats

Important: Some Settings of TinyMCE or certain TinyMCE Plugins require you to do some manual settings for the Plugin to work. The Plugin WILL work, if you act correctly. Check FAQ for more.


== Frequently Asked Questions ==

= I cannot edit editor-style.css and editor-style-shared.css. What is wrong? =

Probably the Plugin was not able to create these files, as there was some prolems with your server settings. Please create these files inside the directory of your Theme manually and make sure the read/write access is set to 777.

= I have edited the files editor-style.css and editor-style-shared.css, but my visual editor is not styled. What is the problem? =

1. You should empty the cache of your Web Browser, this is often the reason for the styles to be applied with some delay.
2. Check this with simple styles like body { background-color:black; } to see if it basically works.
3. Maybe there are some functions inside of your Theme / other Plugin, which overwrite the settings of this Plugin. Please check this out as it seems to work in most cases.

= The file editor-style.css is not working in the frontend of my website, but it is applied to the backend editor. Why? =

Make sure that your Theme calls the function wp_head(); inside the header of your template.

= I have created styles, but I do not see the styles dropdown. Is something broken? =

- If you are using TinyMCE Basic Plugin, be sure that the second row of your TinyMCE is visible. You can usually show / hide this row by clicking a button in the right-top row of Tinymce Standard.
- If you are using TinyMCE Advanced (or similar), make sure to add the Dropdown called "Styles" on the "Tinymce Advanced" Settings Page. I try to add the styles dropdown automatically inside of this Plugin, but it will not work in every case, as it may be overwritten.

= I have created some custom formats/styles, I can see the dropdown, but the formats/styles which I have created on the settings Page just do not work. What is wrong? =

You have to be careful, when creating custom styles/formats if you are doing it for the first time. If you make a row with a HTML blockquote element and you choose "Inline" from the radio buttons, this style will NOT work, as blockquote is not an HTML inline element.

Try something easy like:
- Name: My red text
- Type: inline
- Type value: span
- CSS style: color / #f00

Check if this style is working. If it works, think about other style. They will only work if you use correct semantics.



== Upgrade notice ==

= 1.1.0 =
IMPORTANT: Go to the settings page of the Plugin and set the location of where to find your stylesheets!

== Screenshots ==

1. /assets/screenshot-1.png
2. /assets/screenshot-2.png

== Changelog ==

= 1.1.1 =
- Added language files Spanish (es) by Andrew Kurtis (http://webhostinghub.com/)

= 1.1.0 =
- Added the possibility to define custom paths for editor styles (better compatibility)
- Fixed some broken links
- Added some information for better usability
- Fixed Bug with creating files

= 1.0.0 =
* Initial release.
