=== HWCOE Syllabus Upload ===
Contributors: Allison Logan

Allows admin to display a dynamic table of entries using the Syllabus Upload custom_post_type.

== Description ==

The HWCOE Syllabus Upload plugin has been created specifically for HWCOE child theme websites. This plugin allows admin to display a dynamic table of entries using the Syllabus Upload custom_post_type, Gravity Forms  using the Syllabi Uploads form, the Gravity Forms + Custom Post Types plugin and Advanced Custom Fields (ACF) using the Syllabus Upload Modules field group. 

The specified custom_post_type, Gravity Form and ACF Field Group must be used for this plugin to work. 

== Required Plugins ==

Advanced Custom Fields
Gravity Forms
Gravity Forms + Custom Post Types

== Installation ==

1. Ensure all required plugins are installed and activated. 
2. Upload the plugin to the plugins folder. 
3. Activate the plugin from the Plugins dashboard.
4. Go to Import/Export under the Gravity Forms Plugin.
5. Import the "gravityforms-syllabi-upload.json" file.
6. Go to ACF > Tools and import the "group_5cd17365c47bc.json" file.
7. Set up the reCAPTCHA for your form:
     - Go to the reCAPTCHA link (https://www.google.com/recaptcha/admin/create) to register your site. You may need to create a gmail account for this. Use something generic that can stay with the department and not something personal.
     - Select reCAPTCHA v2 and the first "I'm not a robot" option
     - Add your website address under domain
     - Accept the terms and submit. This should provide you with two keys you will need for the form.	
     - Back in Wordpress, go to Form > Settings. Scroll down to reCAPTCHA and put in your keys.
8. Set up the email notifications. 
     - Go to Forms > Syllabi Uploads > Settings > Notifications
     - Turn on the Admin Notification and click to edit
     - Add in whatever email you would like to receive notifications of syllabi submissions in the "Send To Email" field
9. Paste the form shortcode on a page on your website to display the form.
10. Paste the plugin shortcode -- [syllabi-table] -- on the page you would like to display the table. 
     ***Page must have "Syllabi Upload" as the title.***
11. As syllabi entries are submitted, they will be pending approval in the Course Syllabi tab and will display in the table once you have published the entry.



== Changelog ==

= v1.4 (2019-11-14) =
   * Add the ability to sort columns within the admin panel edit screen
   * Updates the search output to include the custom columns

= v1.3 (2019-10-05) =
   * Modifies name of form to match prospective page title
   * Adds input mask for Course Number field
   * Adds years to Semester field
   * Removes description for the Syllabus field
   * Makes value of Name field always output as Title Case
   * Makes value of Course Number field always output as uppercase
   * Renames the files uploaded to be course number plus semester and year
	
= v1.2 (2019-05-30) =
   * Update form settings to previous version

= v1.1 (2019-05-21) =
   * Enqueue assets
   * Update form settings
       * Delete form entries automatically after 10 days ("Course Syllabi" posts created by the form are not affected)
       * Enable form admin notification with link to edit/approve the Custom Post Type entry
       * Update form confirmation message to include hard refresh (allows easier multiple uploads)

= v1.0 (2019-05-16) =
   * [NEW] Initial release
