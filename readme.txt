=== Delete Spam Daily ===
Contributors: brockangelo
Donate link: http://brockangelo.com/wordpress/plugins/delete-spam-daily
Tags: comments, spam, cron, delete, database
Requires at least: 2.1.0
Tested up to: 2.7.1
Stable tag: 1.0.2

Uses wp_cron to delete comments each day that are marked "spam" in the database.

== Description ==

This plugin schedules a daily event using wp_cron that deletes all comments marked "spam" in the database, then optimizes the comments table.

Brief reason why I made such a low-tech plugin:

Akismet catches all the spam, but when I was looking at the size of my backups one day, 
I noticed that there were large numbers of comment spam across several sites that were undeleted and they
made the databases fairly large. So I setup a cron job on my server that deletes the spam. 

Since I had never written a plugin, I thought this might be helpful for those who do not have 
a dedicated server, shell access to their site, or the knowledge of (or interest in ) cron to set this up.

Spam is not deleted until you start the schedule after the plugin is activated. I created buttons
to start and stop the schedule if you need that layer of control. There is also a button for deleting all spam manually.


== Installation ==


1. Upload the `delete-spam-daily` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Look under "Settings" --> "Delete Spam Daily" to activate the schedule.
1. You can start and stop the schedule from the "Delete Spam Daily" menu.

== Frequently Asked Questions ==

= What time does comment spam get deleted? =

The first time you start the schedule, spam gets deleted. 
Subsequent spam will be scheduled to delete every 24 hours after the first activation.

= If I de-activate this plugin, will it continue to delete spam daily? =

No. The cron job is cleared upon deactivation.

== Screenshots ==

1. Pretty simple.

== Changelog ==

* Version 1.0.2 - 5.28.09: Now optimizes the `wp_comments` table after spam is deleted. Updated DB call to allow for alternate wordpress table prefix. 
* Version 1.0.1 - 5.26.09: Minor update. `add_action` was added to the "Delete Spam Daily" button so it would actually delete spam once a day. :-) In addition to a "Stop Deleting Spam" button, a button was added that reschedules the cron to happen in 1 minute so you can be sure your crons are working right away. 
* Version 1.0 - 5.25.09: Initial Release. Included button to start deleting spam daily, and a button to delete spam immediately. Once the schedule was activated, it provided a button to stop the schedule. Shows current spam count and next scheduled delete.


