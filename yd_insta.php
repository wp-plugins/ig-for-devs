<?php
/*
Plugin Name: Instagram for Devs
Plugin URI: http://y-designs.com/
Description: A simple wordpress plugin that makes a gallery from an Instagram feed.
Version: 0.1
Author: Ryuhei Yokokawa
Author URI: http://y-designs.com
License: GPLv2 or later
*/

//them composer packages
include_once 'vendor/autoload.php';
use MetzWeb\Instagram\Instagram as YDInstagram;//make sure we don't collide

//I've put all the admin stuff in the other file.
require_once(dirname(__FILE__) . '/admin.php');


function yd_insta_front( $atts = false ) {

	//Reads the shortcodes and loads up default options.
	$attr = shortcode_atts(array(
				'insta_client_id' => get_option('insta_client_id'),
				'insta_client_secret' => get_option('insta_client_secret'),
				'insta_callback' => get_option('insta_callback'),
				'insta_user' => get_option('insta_default_user'),
				'insta_limit' => get_option('insta_default_limit',10),
				'insta_cache' => get_option('insta_cache')
			), $atts);

	//Data Handler
	$unique = md5( 'yd_'.$attr['insta_user'] );//sets a unique name against our instagram cache.
	$objects = get_transient('yi_'.$unique);//see if we have one stored already in the cache.

	if( !$attr['insta_cache'] || !$objects) {

		//instantiate the client.
		$instagram = new YDInstagram(array(
			'apiKey' 	=> $attr['insta_client_id'],
			'apiSecret' => $attr['insta_client_secret'],
			'apiCallback' => $attr['insta_callback']
		));

		$attr['user_id'] = yd_insta_searchUser($instagram, $attr['insta_user']);

		if(!$attr['user_id']) {
			return "We couldn't find that user.";//Gotta figure out this one.
		}

		$objects = yd_insta_get($instagram,$attr);
		$objects = json_encode($objects);//back to array;
		if( $attr['insta_cache'] ) {//if the system is set to do a cache, set it.
			set_transient( 'yi_'.$unique, $objects, 600 );//600 seconds = 10 minutes
		} else {
			delete_transient( 'yi_'.$unique );
		}
	}

	$objects = json_decode($objects,true);//back to array;


	//View handler
	if(is_array($objects) && count($objects)) {
		
		//Grab current theme location
		$theme_location = get_stylesheet_directory();
		$plugin_location = plugin_dir_path( __FILE__ );

		//figure out if the user has created his/her custom view.
		if( file_exists($theme_location.'/insta_view/view.php') ) {
			$view = $theme_location.'/insta_view/view.php';
		} else {
			$view = $plugin_location.'insta_view/view.php';
		}

		//render this stuff.
		$insta_view = '';
		foreach($objects as $i => $pic) {
			require($view);
		}

		return;
	} else {
		return 'No Instagram Pictures posted';
	}

}

function yd_insta_searchUser($instagram, $username) {
	$users = $instagram->searchUser($username, 30);

	foreach($users->data as $user) {
		if($user->username == $username) {
			return $user->id;
		}
	}
	return false;
}

function yd_insta_get($instagram, $attr) {
	$media = $instagram->getUserMedia($attr['user_id'],$attr['insta_limit']);//34962820 is ryuhei
	//Run the items in the views..
	return $media->data;
}

add_shortcode( 'insta_dev', 'yd_insta_front' );