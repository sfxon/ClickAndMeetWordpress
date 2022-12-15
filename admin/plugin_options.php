<?php
/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */
 
/**
 * custom option and settings
 */
if(!defined('ABSPATH')) { exit; }
 

function mvcam_general_settings_init() {
		// register a new setting for "general" page
		register_setting( 'mvcam_general', 'mvcam_general_options' );
		
		// register a new section "Mail Settings" in the "mvcam_general" page
		add_settings_section(
			 'mvcam_general_section_mail_settings',
			 __( 'Box: Plugin Einstellungen', 'mvcam_general' ),
			 'mvcam_general_section_mail_settings_callback',
			 'mvcam_general'
		);
		
		$prefix = 'mvcam_general';
		
		// Abschnitt "Allgemeine Einstellungen" ---------------------------------------
		// content
		$field_title = 'E-Mail Variante:';
		$field_name = '_email_html_or_plain';
		
		add_settings_field(
				$prefix . '_' . $field_name, 
				__( $field_title, $prefix ),
				$prefix . '_mail_dropdown_field_callback',
				$prefix,
				$prefix . '_section_mail_settings',
				array(
						'label_for' => $prefix . '_' . $field_name,
						'class' => $prefix . '_row',
						$prefix . '_custom_data' => 'custom',
				)
		);
		
		// Eingabefeld: Eigenes Dropdown
		$field_title = 'Formular-Dropdown Werte (Trennen mit |):';
		$field_name = '_form_dropdown_values';
		
		add_settings_field(
				$prefix . '_' . $field_name, 
				__( $field_title, $prefix ),
				$prefix . '_field_callback',
				$prefix,
				$prefix . '_section_mail_settings',
				array(
						'label_for' => $prefix . '_' . $field_name,
						'class' => $prefix . '_row',
						$prefix . '_custom_data' => 'custom',
				)
		);
		
		/*
		// content
		$field_title = 'Seite für Listing-Ansicht:';
		$field_name = '_site_for_listing';
		
		add_settings_field(
				$prefix . '_' . $field_name, 
				__( $field_title, $prefix ),
				$prefix . '_post_dropdown_field_callback',
				$prefix,
				$prefix . '_section_post_settings',
				array(
						'label_for' => $prefix . '_' . $field_name,
						'class' => $prefix . '_row',
						$prefix . '_custom_data' => 'custom',
				)
		);
		*/
}
 
/**
 * register our General Settings to the admin_init action hook
 */
add_action( 'admin_init', 'mvcam_general_settings_init' );
 
/**
 * custom option and settings:
 * callback functions
 */

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function mvcam_general_section_mail_settings_callback( $args ) {
	 ?>
			 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'E-Mail Grundeinstellungen.', 'mvcam_general' ); ?></p>
	 <?php
}

// Callback für Input-Field
function mvcam_general_field_callback( $args ) {
	 // get the value of the setting we've registered with register_setting()
	 $options = get_option( 'mvcam_general_options' );
	 
	 // output the field
	 ?>
	 <input
	 		type="text"
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			data-custom="<?php echo esc_attr( $args['mvcam_general_custom_data'] ); ?>"
			name="mvcam_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
			value="<?php if(isset($options[$args['label_for']])) { echo htmlspecialchars($options[ $args['label_for'] ]); } ?>"
			style="width: 100%;"
		/>
	 
	 <?php
	 /*
	 <p class="description">
	 		<?php esc_html_e( 'Meta-Tag für den Author.', 'trauringhaus_general' ); ?>
	 </p>
	 */
}

// Callback für Input-Field
function mvcam_general_field_test_callback( $args ) {
	 echo 'test';
	 
	 /*
	 
	 // get the value of the setting we've registered with register_setting()
	 $options = get_option( 'mvcam_general_options' );
	 
	 // output the field
	 ?>
	 <input
	 		type="text"
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			data-custom="<?php echo esc_attr( $args['mvcam_general_custom_data'] ); ?>"
			name="mvcam_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
			value="<?php if(isset($options[$args['label_for']])) { echo htmlspecialchars($options[ $args['label_for'] ]); } ?>"
			style="width: 100%;"
		/>
	 
	 <?php
	 /*
	 <p class="description">
	 		<?php esc_html_e( 'Meta-Tag für den Author.', 'trauringhaus_general' ); ?>
	 </p>
	 */
}

// Callback für Mail-Dropdown-Field
function mvcam_general_mail_dropdown_field_callback( $args ) {
		// get the value of the setting we've registered with register_setting()
		$options = get_option( 'mvcam_general_options' );
		$current_value = 0;
		
		if(isset($options[$args['label_for']])) {
				$current_value = $options[$args['label_for']];
		}
		
		?>
		
		<select name="mvcam_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]" id="<?php echo esc_attr( $args['label_for'] ); ?>">
				<option value="plain"<?php if($current_value == 'plain') { echo ' selected="selected"'; } ?>>Plain-Text</option>
				<option value="html"<?php if($current_value == 'html') { echo ' selected="selected"'; } ?>>HTML-Text</option>
		</select>
		
		<?php
}

////////////////////////////////////////////////////////////////////////
// Callback für Image Field
////////////////////////////////////////////////////////////////////////
function mvcam_general_image_field_callback( $args ) {
		// get the value of the setting we've registered with register_setting()
		$options = get_option( 'mvcam_general_options' );
	 
		// output the field
		?>
		<div class='image-preview-wrapper'>
				<img id="image-preview-<?php echo esc_attr( $args['label_for'] ); ?>" height='200' style='max-height: 200px; width: auto;' src="<?php if(isset($options[$args['label_for']])) { echo htmlspecialchars($options[ $args['label_for'] ]); } ?>" />
		</div>
		<input id="upload_image_button-<?php echo esc_attr( $args['label_for'] ); ?>" type="button" class="button" value="<?php _e( 'Bild auswählen' ); ?>" />
	 
		<input
				type="hidden"
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				data-custom="<?php echo esc_attr( $args['mvcam_general_custom_data'] ); ?>"
				name="mvcam_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
				value="<?php if(isset($options[$args['label_for']])) { echo htmlspecialchars($options[ $args['label_for'] ]); } ?>"
		/>
		
		
		<!-- Add the javascript for the upload field! -->
		
		<script type='text/javascript'>
				jQuery(document).ready( function($) {
						var mediaUploader;
						
						$('#upload_image_button-<?php echo esc_attr( $args['label_for'] ); ?>').on('click',function(e) {
							e.preventDefault();
							if( mediaUploader ){
								mediaUploader.open();
								return;
							}
							
							mediaUploader = wp.media.frames.file_frame = wp.media({
								title: 'Wähle eine Bild',
								button: {
									text: 'Bild wählen'
								},
								multiple: false
							});
							
							mediaUploader.on('select', function(){
								attachment = mediaUploader.state().get('selection').first().toJSON();
								$('#<?php echo esc_attr( $args['label_for'] ); ?>').val(attachment.url);
								$('#image-preview-<?php echo esc_attr( $args['label_for'] ); ?>').attr('src', attachment.url);
							});
							
							mediaUploader.open();
						});
						
					});
		</script>
		<?php
}

 ////////////////////////////////////////////////////////////////////////
// Callback für HTML Textarea Field
////////////////////////////////////////////////////////////////////////
function mvcam_general_html_textarea_field_callback( $args ) {
		// get the value of the setting we've registered with register_setting()
		$options = get_option( 'mvcam_general_options' );
		
		wp_editor( $options[ $args['label_for'] ] , 'my_option', array(
				'wpautop'       => true,
				'media_buttons' => false,
				'textarea_name' => 'mvcam_general_options[' . esc_attr( $args['label_for'] ) . ']',
				'editor_class'  => 'mv_mvcam_general_wp_editor',
				'textarea_rows' => 10
		) );
}


/**
 * top level menu
 */
function mvcam_general_options_page() {
		// add top level menu page		
		add_submenu_page( 
			'options-general.php', 
			'Click and Meet', 
			'Click and Meet',
			'manage_options', 
			'mvcam_general', 
			'mvcam_general_options_page_html'
		);
}
 
/**
 * register our trauringhaus_general_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'mvcam_general_options_page' );
 
/**
 * top level menu:
 * callback functions
 */
function mvcam_general_options_page_html() {
	 // check user capabilities
	 if ( ! current_user_can( 'manage_options' ) ) {
			return;
	 }
 
	 // add error/update messages
	 
	 // check if the user have submitted the settings
	 // wordpress will add the "settings-updated" $_GET parameter to the url
	 if ( isset( $_GET['settings-updated'] ) ) {
			 // add settings saved message with the class of "updated"
			 //add_settings_error( 'mvimmo_general_messages', 'mvimmo_general_message', __( 'Settings Saved', 'mvimmo_general' ), 'updated' );
	 }
 
	 // show error/update messages
	 settings_errors( 'mvcam_general_messages' );
	 ?>
	 <div class="wrap">
	 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	 <form action="options.php" method="post">
	 <?php
	 // output security fields for the registered setting
	 settings_fields( 'mvcam_general' );
	 // output setting sections and their fields
	 // (sections are registered for, each field is registered to a specific section)
	 do_settings_sections( 'mvcam_general' );
	 // output save settings button
	 submit_button( 'Save Settings' );
	 ?>
	 </form>
	 </div>
	 <?php
}
