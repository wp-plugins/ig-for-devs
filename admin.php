<?php
//Instaham plugin backend Admin.


// create custom plugin settings menu
add_action('admin_menu', 'ydinsta_create_menu');

function ydinsta_create_menu() {

	//create new top-level menu
	add_menu_page('Instagram Settings', 'Instagram Settings', 'administrator', __FILE__, 'ydinsta_settings_page','dashicons-admin-generic');

	//call register settings function
	add_action( 'admin_init', 'register_ydinsta_settings' );
}

function register_ydinsta_settings() {
	//register our settings
	register_setting( 'ydinsta-settings-group', 'insta_client_id' );
	register_setting( 'ydinsta-settings-group', 'insta_client_secret' );
	register_setting( 'ydinsta-settings-group', 'insta_callback' );
	register_setting( 'ydinsta-settings-group', 'insta_default_user' );
	register_setting( 'ydinsta-settings-group', 'insta_default_limit' );
	register_setting( 'ydinsta-settings-group', 'insta_cache' );
}

function ydinsta_settings_page() { //yup, classc WP stuff. ?>

	<div class="wrap">
	<h2>Instagram Plugin</h2>
	<p>By <a href="http://y-designs.com">Y-Designs, Inc | A Seattle Web Design Company</a></p>

	<p>To use this plugin, just place the shortcode below anywhere in your content. <pre>[insta_dev]</pre> </p>

	<form method="post" action="options.php">
	    <?php settings_fields( 'ydinsta-settings-group' ); ?>
	    <?php do_settings_sections( 'ydinsta-settings-group' ); ?>
	    <table class="form-table">
	        <tr valign="top">
	        	<th scope="row">Client ID</th>
		        <td>
		        	<input type="text" name="insta_client_id" value="<?php echo esc_attr( get_option('insta_client_id') ); ?>" />
		        </td>
	        </tr>
	         
	        <tr valign="top">
	        	<th scope="row">Client Secret</th>
		        <td>
		        	<input type="text" name="insta_client_secret" value="<?php echo esc_attr( get_option('insta_client_secret') ); ?>" />
		        </td>
	        </tr>

	        <tr valign="top">
	        	<th scope="row">Callback URL</th>
		        <td>
		        	<input type="text" name="insta_callback" value="<?php echo esc_attr( get_option('insta_callback') ); ?>" />
		        </td>
	        </tr>

	        <tr valign="top">
	        	<th scope="row">Default User</th>
		        <td>
		        	<input type="text" name="insta_default_user" value="<?php echo esc_attr( get_option('insta_default_user') ); ?>" />
		        </td>
	        </tr>

	        <tr valign="top">
	        	<th scope="row">Default Limit</th>
		        <td>
		        	<input type="text" name="insta_default_limit" value="<?php echo esc_attr( get_option('insta_default_limit') ); ?>" />
		        </td>
	        </tr>

	        <tr valign="top">
		        <th scope="row">Cache for 10 min?</th>
		        <td>
		        	<input type="checkbox" name="insta_cache" <?php echo esc_attr( get_option('insta_cache') ) ? 'checked' : ''  ; ?> />
		        </td>
	        </tr>
	    </table>
	    
	    <?php submit_button(); ?>

	</form>
	</div>

	<?php

}//end of ydinsta_settings_page
