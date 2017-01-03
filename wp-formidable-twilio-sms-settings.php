<?php 
defined('ABSPATH') or die("so sorry...");

function wp_formidable_twilio_sms_settings_page(){
	?>
	    <div class="wrap">
	    <form method="post" action="options.php">
	        <?php
	            settings_fields("section");
	            do_settings_sections("theme-options");      
	            submit_button(); 
	        ?>          
	    </form>
		</div>
	<?php
}



function wp_formidable_twilio_sms_sender_id() {
	?>
    	<input type="text" name="wp_formidable_twilio_sms_sender_id" id="wp_formidable_twilio_sms_sender_id" value="<?php echo get_option('wp_formidable_twilio_sms_sender_id'); ?>" />
    <?php
}

function wp_formidable_twilio_sms_password() {
	?>
    	<input type="text" name="wp_formidable_twilio_sms_password" id="wp_formidable_twilio_sms_password" value="<?php echo get_option('wp_formidable_twilio_sms_password'); ?>" />
    <?php
}



function wp_formidable_twilio_sms_mobile() {
	?>
    	<input type="text" name="wp_formidable_twilio_sms_mobile" id="wp_formidable_twilio_sms_mobile" value="<?php echo get_option('wp_formidable_twilio_sms_mobile'); ?>" />
    <?php
}

function wp_formidable_twilio_sms_mobile_cc() {
	?>
    	<input type="text" name="wp_formidable_twilio_sms_mobile_cc" id="wp_formidable_twilio_sms_mobile_cc" value="<?php echo get_option('wp_formidable_twilio_sms_mobile_cc'); ?>" />
    <?php
}

function wp_formidable_twilio_sms_field_id() {
	?>
    	<input type="text" name="wp_formidable_twilio_sms_field_id" id="wp_formidable_twilio_sms_field_id" value="<?php echo get_option('wp_formidable_twilio_sms_field_id'); ?>" />
    	<p>(The Formidable field ID where a user can enter their mobile phone number for which they would like to receive a text on.)</p>
    <?php
}

function wp_formidable_twilio_sms_msg() {
	?>
    	<input type="text" name="wp_formidable_twilio_sms_msg" id="wp_formidable_twilio_sms_msg" value="<?php echo get_option('wp_formidable_twilio_sms_msg'); ?>" size="80" maxlength="134"/>
    	<p>(The text message sent to the person submitting the form.)</p>
    <?php
}

function wp_formidable_twilio_sms_msg_owner() {
	?>
    	<input type="text" name="wp_formidable_twilio_sms_msg_owner" id="wp_formidable_twilio_sms_msg_owner" value="<?php echo get_option('wp_formidable_twilio_sms_msg_owner'); ?>" size="80" maxlength="134"/>
    	<p>(The text message sent to you. If empty no text will be sent to you.)</p>
    <?php
}


function wp_formidable_twilio_sms_debug() {
	?>
    	<input type="checkbox" name="wp_formidable_twilio_sms_debug" id="wp_formidable_twilio_sms_debug" <?php checked( 1, get_option('wp_formidable_twilio_sms_debug'), true); ?> value="1"/>
    <?php
}

function wp_formidable_twilio_sms_error_log_path() {
	?>
    	<input type="text" name="wp_formidable_twilio_sms_error_log_path" id="wp_formidable_twilio_sms_error_log_path" value="<?php echo get_option('wp_formidable_twilio_sms_error_log_path'); ?>" size="50" maxlength="140"/>
    <?php
}



function wp_formidable_twilio_sms_api_panel_fields() {

	add_settings_section("section", "Formidable Twilio SMS Settings", null, "theme-options");
	
    add_settings_field("wp_formidable_twilio_sms_sender_id", "Twilio Account ID", "wp_formidable_twilio_sms_sender_id", "theme-options", "section");
    	
    add_settings_field("wp_formidable_twilio_sms_password", "Twilio Auth Token", "wp_formidable_twilio_sms_password", "theme-options", "section");
    

    
    add_settings_field("wp_formidable_twilio_sms_mobile", "Twilio SMS Mobile Number", "wp_formidable_twilio_sms_mobile", "theme-options", "section");
    
    add_settings_field("wp_formidable_twilio_sms_mobile_cc", "Your Mobile Number to send a notification", "wp_formidable_twilio_sms_mobile_cc", "theme-options", "section");
    
    add_settings_field("wp_formidable_twilio_sms_field_id", "Formidable Field ID ", "wp_formidable_twilio_sms_field_id", "theme-options", "section");    
    
    add_settings_field("wp_formidable_twilio_sms_msg", "User message/text content", "wp_formidable_twilio_sms_msg", "theme-options", "section");

    add_settings_field("wp_formidable_twilio_sms_msg_owner", "Owner message/text content", "wp_formidable_twilio_sms_msg_owner", "theme-options", "section");
       
    add_settings_field("wp_formidable_twilio_sms_debug", "Enable debug logging", "wp_formidable_twilio_sms_debug", "theme-options", "section");  
 

        
    add_settings_field("wp_formidable_twilio_sms_error_log_path", "Error Log path", "wp_formidable_twilio_sms_error_log_path", "theme-options", "section");    
      
    

    register_setting("section", "wp_formidable_twilio_sms_sender_id");
    register_setting("section", "wp_formidable_twilio_sms_password");
    register_setting("section", "wp_formidable_twilio_sms_mobile");
    register_setting("section", "wp_formidable_twilio_sms_mobile_cc");
    register_setting("section", "wp_formidable_twilio_sms_field_id");
    register_setting("section", "wp_formidable_twilio_sms_msg");
    register_setting("section", "wp_formidable_twilio_sms_msg_owner");
    register_setting("section", "wp_formidable_twilio_sms_debug");
    register_setting("section", "wp_formidable_twilio_sms_error_log_path");
}

add_action("admin_init", "wp_formidable_twilio_sms_api_panel_fields");

// Only if we need a main menu
// function add_theme_menu_item() {
// 	add_menu_page("SMS API Settings", "Twilio Formidable SMS Settings", "manage_options", "sms-settings", "wp_formidable_twilio_sms_settings_page", null, 99);
// }

function add_theme_menu_item() {
  add_submenu_page('options-general.php', 'WP Formidable Twilio SMS', 'WP Formidable Twilio SMS', 'manage_options', 'sms-settings', 'wp_formidable_twilio_sms_settings_page');
}

add_action("admin_menu", "add_theme_menu_item");

?>