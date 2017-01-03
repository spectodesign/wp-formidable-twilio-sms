<?php 

defined('ABSPATH') or die("so sorry...");
/*
 * Plugin Name: WP Formidable Twilio SMS
 * Description: Send SMS notifications when somebody submits your Formidable form. This plugin is specifically for Formidable Forms and Twilio.
 * Version: 1.0.0
 * Author: spectodesign
 * Author URI: https://profiles.wordpress.org/spectodesign
 * License: GPL2
*/


	
include( plugin_dir_path( __FILE__ ) . 'wp-formidable-twilio-sms-settings.php'); 
 
 	// is debug logging enabled?
//  	$wp_formidable_twilio_sms_debug = get_option('wp_formidable_twilio_sms_debug', false);
//  	if( ! $wp_formidable_twilio_sms_debug  && $wp_formidable_twilio_sms_debug === '1'){
//  		$wp_formidable_twilio_sms_debug = true;
//  	}
// 
//  	$error_log_path = get_option('wp_formidable_twilio_sms_error_log_path', '');
//  	send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'log path: '.$error_log_path, $wp_formidable_twilio_sms_error_log_path);
//  		

	
function send_sms_via_twilio( $entry_id, $form_id ){

	// debug enabld?
 	$wp_formidable_twilio_sms_debug = get_option('wp_formidable_twilio_sms_debug', false);
 	if( ! $wp_formidable_twilio_sms_debug  && $wp_formidable_twilio_sms_debug === '1'){
 		$wp_formidable_twilio_sms_debug = true;
 	}
	// custom log path?
 	$error_log_path = get_option('wp_formidable_twilio_sms_error_log_path', '');
 	send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'log path: '.$error_log_path, $wp_formidable_twilio_sms_error_log_path);
 	
 	// START PLUGIN MAIN
	send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, '---------------- START send_sms_via_twilio', $wp_formidable_twilio_sms_error_log_path);
	
    // check if we have a value from our option field specifying the Formidable field id
    $wp_formidable_twilio_sms_field_id  = get_option('wp_formidable_twilio_sms_field_id');
    if( $wp_formidable_twilio_sms_field_id ){
    	$field_id = intval($wp_formidable_twilio_sms_field_id);
    } else { 
    	send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'no field id specified - stopping plugin execution ', $wp_formidable_twilio_sms_error_log_path);
    	return;
    }
    
	// check if the user has filled in the optional Formidable field id
	// which is specifying the mobile phone number we want to send the SMS to
	$sms_mobile_number = $_POST['item_meta'][$field_id];
	if( ! isset($sms_mobile_number) ){
		return;
	} else {
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'sms_mobile_number: '.$sms_mobile_number, $wp_formidable_twilio_sms_error_log_path);
		// only leave numbers
		$sms_mobile_number = preg_replace("/[^0-9]/", '', $sms_mobile_number);
		// remove a possible leading 1 
		if (strlen($sms_mobile_number) == 11) $sms_mobile_number = preg_replace("/^1/", '',$sms_mobile_number);
		// if we have 10 digits left, it's probably valid.
		if (strlen($sms_mobile_number) == 10) {
			send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'sms_mobile_number: '.$sms_mobile_number, $wp_formidable_twilio_sms_error_log_path);
		} else {
			send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'not valid US phone number --> sms_mobile_number: '.$sms_mobile_number, $wp_formidable_twilio_sms_error_log_path);
			return;
		}  
		
	}
  
  	// get our other options
	$sms_sender_id = get_option('wp_formidable_twilio_sms_sender_id', '');
	if( empty($sms_sender_id) ){
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'No Twilio Account ID specified: ', $wp_formidable_twilio_sms_error_log_path);
		return;
	} else {
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'Using Twilio Account ID: '.$sms_sender_id, $wp_formidable_twilio_sms_error_log_path);
	}
	
	
	$sms_password = get_option('wp_formidable_twilio_sms_password');
	if( empty($sms_password) ){
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'No Twilio Auth Token specified: ', $wp_formidable_twilio_sms_error_log_path);
		return;
	} else {
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'Using Twilio Auth Token: '.$sms_password, $wp_formidable_twilio_sms_error_log_path);
	}
	
	
	$sms_mobile = get_option('wp_formidable_twilio_sms_mobile');
	if( empty($sms_mobile) ){
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'No Twilio SMS Mobile number specified: ', $wp_formidable_twilio_sms_error_log_path);
		return;
	} else {
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'Using Twilio Mobile Number: '.$sms_mobile, $wp_formidable_twilio_sms_error_log_path);
	}	
	
	
	$sms_mobile_cc = get_option('wp_formidable_twilio_sms_mobile_cc');
	if( empty($sms_mobile_cc) ){
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'No User Mobile Number specified: ', $wp_formidable_twilio_sms_error_log_path);
	} else {
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'Using User Mobile Number: '.$sms_mobile_cc, $wp_formidable_twilio_sms_error_log_path);
	}
		
	// user text
	$sms_msg = get_option('wp_formidable_twilio_sms_msg');
	if( empty($sms_msg) ){
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'No text message content specified: ', $wp_formidable_twilio_sms_error_log_path);
		return;
	} else {
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'Using text message content: '.$sms_msg, $wp_formidable_twilio_sms_error_log_path);
	}
	
	// owner text
	$sms_msg_owner = get_option('wp_formidable_twilio_sms_msg_owner', '');
	if( empty($sms_msg_owner) ){
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'No Owner message content specified: ', $wp_formidable_twilio_sms_error_log_path);
	} else {
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'Using Owner message content: '.$sms_msg_owner, $wp_formidable_twilio_sms_error_log_path);
	}
	
	
	// assign Twilio credentials
	$id = $sms_sender_id; //"ACCOUNT ID";
	$token = $sms_password; //"AUTH TOKEN";
	$url = "https://api.twilio.com/2010-04-01/Accounts/$id/SMS/Messages";
	
	// assign sender, receiver and text message
	$from = $sms_mobile; // twilio's account phone number
	$to = $sms_mobile_number; // customer's mobile number from Formidable form field
	$body = $sms_msg;
	$data = array (
		'From' => $from,
		'To' => $to,
		'Body' => $body,
	);
	$post = http_build_query($data);
	$x = curl_init($url );
	curl_setopt($x, CURLOPT_POST, true);
	curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
	curl_setopt($x, CURLOPT_POSTFIELDS, $post);
	$y = curl_exec($x);
	curl_close($x);
	
	send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'Using POST content: '.$post, $wp_formidable_twilio_sms_error_log_path);
	send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'CURL response: '.$y, $wp_formidable_twilio_sms_error_log_path);
	
	
	// sending owner a text as well?
	if( ! empty($sms_msg_owner)  && ! empty($sms_mobile_cc)){
		// assign sender, receiver and text message
		$from = $sms_mobile; // twilio's account phone number
		$to = $sms_mobile_cc; // owner's mobile number from options page
		$body = $sms_msg_owner;
		$data = array (
			'From' => $from,
			'To' => $to,
			'Body' => $body,
		);
		$post = http_build_query($data);
		$x = curl_init($url );
		curl_setopt($x, CURLOPT_POST, true);
		curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
		curl_setopt($x, CURLOPT_POSTFIELDS, $post);
		$y = curl_exec($x);
		curl_close($x);
	
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'Using POST content: '.$post, $wp_formidable_twilio_sms_error_log_path);
		send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, 'CURL response: '.$y, $wp_formidable_twilio_sms_error_log_path);	
	
	}
	
	// end sending messages
	send_sms_via_twilio_logger($wp_formidable_twilio_sms_debug, '---------------- END send_sms_via_twilio', $wp_formidable_twilio_sms_error_log_path);

	
} // end send_sms_via_twilio

add_action("frm_after_create_entry", "send_sms_via_twilio", 30, 2);


// support functions
function send_sms_via_twilio_logger( $debug = false, $message = '', $error_log_path = '' ){
	
	if( $debug ){
		if( empty($error_log_path) ){
			error_log($message);
		} else {
			error_log($message, 0, $error_log_path);
		}
	}
	
}
?>