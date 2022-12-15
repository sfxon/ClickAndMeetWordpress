<?php

namespace mvclickandmeet_namespace;

if(!defined('ABSPATH')) { exit; }

/**
 * Add plugin action links.
 *
 * Add a link to the settings page on the plugins.php page.
 *
 * @since 1.0.0
 *
 * @param  array  $links List of existing plugin action links.
 * @return array         List of modified plugin action links.
 */
function mv_clickandmeet_plugin_action_links( $links ) {
	$links = array_merge( array(
		'<a href="' . esc_url( admin_url( '/admin.php?page=mvcam_documentation' ) ) . '">' . __( 'Dokumentation/Anleitung', 'mvclickandmeet' ) . '</a>'
	), $links );

	return $links;

}
add_action( 'plugin_action_links_mvcam/mvcam.php', 'mvclickandmeet_namespace\mv_clickandmeet_plugin_action_links', 10, 10 );
