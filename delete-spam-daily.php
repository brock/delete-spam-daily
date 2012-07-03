<?php
/*
Plugin Name: Delete Spam Daily
Plugin URI: http://brockangelo.com/wordpress/plugins/delete-spam-daily/
Description: Uses wp_cron to delete comments each day that are marked "spam" in the database. 
Version: 1.0.2
Author: Brock Angelo
Author URI: http://brockangelo.com

Copyright 2009  Brock Angelo  (email : email@brockangelo.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

# uncomment the next line if you want it to delete spam upon activation.
# register_activation_hook(__FILE__, 'delete_spam_daily'); 

add_action('delete_spam_daily', 'delete_spam_now');

function dsd_start_schedule() {
	wp_schedule_event(time(), 'daily', 'delete_spam_daily');
}

function delete_spam_now() {
	global $wpdb;
	$wpdb->query("delete from $wpdb->comments where comment_approved='spam';");
	$wpdb->query("OPTIMIZE TABLE $wpdb->comments;");
}

function get_spam_count() {
	global $wpdb;
	$dsd_spam_count = $wpdb->get_var("SELECT COUNT(*) from $wpdb->comments where comment_approved='spam';");
	
	echo $dsd_spam_count;
}

function reschedule_delete_spam() {
	wp_reschedule_event( (time()+60), 'daily', 'delete_spam_daily'); 
}

add_action('admin_menu', 'dsd_menu');

function dsd_menu() {
  add_options_page('Delete Spam Daily Options', 'Delete Spam Daily', 8, __FILE__, 'dsd_options');
}

function dsd_options() {
	$valid_nonce = wp_verify_nonce($_REQUEST['_wpnonce'],'delete_spam_daily');
	if ( $valid_nonce ) {
		if(isset($_REQUEST['delete_spam_now_button'])) {
			delete_spam_now();
		}
		if(isset($_REQUEST['delete_spam_daily_button'])) {
			dsd_start_schedule();
		}
		if(isset($_REQUEST['stop_deleting_spam_button'])) {
			dsd_stop_schedule();
		}
		if(isset($_REQUEST['reschedule_delete_spam_button'])) {
			reschedule_delete_spam();
		}
	}
  
	if ( !empty($_POST ) ) : ?>
	<div id="message" class="updated fade">
	<strong>Settings updated</strong>
	</div>
	<?php endif; ?>

	<div class="wrap">
	<h2>Delete Spam Daily Options</h2>
	
	<p><?php if (wp_next_scheduled('delete_spam_daily') == NULL)
		{	
			echo "The schedule has not been started."; 
		}
		else 
		{ ?>
			Next Spam Delete: <?php echo date("l, F j, Y @ h:i a",(wp_next_scheduled('delete_spam_daily'))); ?></p>
		<?php 
		} ?>
	<p>Current Spam Count: <?php get_spam_count(); ?></p><br /><br />
	
	<?php 
	echo '<form name="delete_spam_now_button" action="" method="post">';
	if ( function_exists('wp_nonce_field') )
	wp_nonce_field('delete_spam_daily');

	echo '<input type="hidden" name="delete_spam_now_button" value="update" />';
	echo '<div><input id="delete_spam_now_button" type="submit" value="Delete spam now &raquo;" /></div>';
	echo "</form>\n<br />";
	
	if (wp_next_scheduled('delete_spam_daily') == NULL)
	{
		echo '<form name="delete_spam_daily_button" action="" method="post">';
		if ( function_exists('wp_nonce_field') )
		wp_nonce_field('delete_spam_daily');
	
		echo '<input type="hidden" name="delete_spam_daily_button" value="update" />';
		echo '<div><input id="delete_spam_daily_button" type="submit" value="Delete spam daily &raquo;" /></div>';
		echo "</form>\n";
	} 
	else 
	{
		echo '<form name="stop_deleting_spam_button" action="" method="post">';
		if ( function_exists('wp_nonce_field') )
		wp_nonce_field('delete_spam_daily');
	
		echo '<input type="hidden" name="stop_deleting_spam_button" value="update" />';
		echo '<div><input id="stop_deleting_spam_button" type="submit" value="Stop Deleting Spam &raquo;" /></div>';
		echo "</form>\n";
	
	
		echo '<form name="reschedule_delete_spam_button" action="" method="post">';
		if ( function_exists('wp_nonce_field') )
		wp_nonce_field('delete_spam_daily');

		echo '<input type="hidden" name="reschedule_delete_spam_button" value="update" />';
		echo '<div><input id="reschedule_delete_spam_button" type="submit" value="Reschedule to start in 1 minute &raquo;" /> <i>Helpful for testing cron</i></div>';
		echo "</form>\n<br />"; 
		
	}
	?>
	<br />
	<br />
	Deactivating this plugin will stop the schedule.  <br />
	</div>

	<?php
	}

register_deactivation_hook(__FILE__, 'dsd_stop_schedule');

function dsd_stop_schedule() {
	wp_clear_scheduled_hook('delete_spam_daily');
}

?>