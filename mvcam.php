<?php

namespace mvclickandmeet_namespace;

/*
Plugin Name: Click and Meet für Wordpress
Plugin URI: https://clickandmeet.org
Description: Click and Meet für Wordpress; Lassen Sie Ihre Kunden -Vor-Ort-Termine- über die eigene Webseite buchen und verwalten Sie diese.
Author: Mindfav.com, Steve Krämer
Version: 1.2
Author URI: https://clickandmeet.org
*/

$plugin = /*__FILE__*/'mvcam';
define('MV_PLUGIN_MVCAM', /*__FILE__*/'mvcam');	//this should be replaced when we got a class structure for everything!
define('MV_PLUGIN_MVCAM_VERSION', '1.2');

//Include library.
require_once('lib/cModule.class.php');
require_once('lib/mvCore.php');
require_once('lib/mvWpModel.php');
require_once('lib/mvWpAdmin.php');
require_once('lib/mvWpAdminMenu.php');
require_once('lib/mvFilter.php');
require_once('lib/db.php');
require_once('lib/mvRenderer.php');
require_once('lib/mvMailTextBuilder.php');


//Include Data Models.
//require_once('model/mvCamCalendar.php');		//ImmoExpose
require_once('model/mvAppointment.php');
require_once('model/mvEventLocations.php');
require_once('model/mvUserUnit.php');
require_once('model/mvAppointmentStatus.php');
require_once('model/mvCalendarColors.php');
require_once('model/mvBookingformTexts.php');
require_once('model/mvMailTextSettings.php');
require_once('model/mvCmSettings.php');

//Include Admin extensions.
require_once('admin/plugin_options.php');
require_once('admin/plugin_documentation.php');

require_once('admin/mvCamCalendarAdmin.php');		//mvImmoExposeAdmin
require_once('admin/mvEventLocationsAdmin.php');
require_once('admin/mvUserUnitAdmin.php');
require_once('admin/mvAppointmentStatusAdmin.php');
require_once('admin/mvCalendarColorsAdmin.php');
require_once('admin/mvBookingformTextsAdmin.php');
require_once('admin/mvMailTextSettingsAdmin.php');
require_once('admin/mvCmSettingsAdmin.php');
require_once('admin/mvCamConfigAdmin.php');
require_once('admin/mvCamBatchDeleteAdmin.php');
require_once('admin/mvDocumentation.php');

//Include Frontend Classes
require_once('frontend/mvCalendar.php');

//Model Installation überprüfen.
$iMvCore = new mvCore();

$models = array(
		'mvAppointment',
		'mvEventLocations',
		'mvUserUnit',
		'mvAppointmentStatus',
		'mvCmSettings',
		'mvCalendarColors',
		'mvBookingformTexts',
		'mvMailTextSettings'
);
$iMvCore->setModels($models);
		
// Helper Function, to provide core for plugin.
function mv_core() {
		global $iMvCore;
		return $iMvCore;
}

//This is, because the global variables are not available, when the plugin is activated,
//because Wordpress then includes this in a function.
$test_core = mv_core();		//Das gibt NULL zurück, wenn wir uns in der Wordpress "activation" Methode befinden. Damit können wir prüfen, ob das Plugin gerade aktiviert wird. Wenn es aktiviert wird, darf der nachfolgende Code nicht ausgeführt werden.	

if(NULL !== $test_core) {
		//Installation prüfen und Verarbeitung je nach Installations-Zustand starten.
		$result = mv_core()->checkModelInstallation();
		
		if($result == false) {
				//mv_core()->registerAdminInstallation();
				$iMvCamCalendarAdmin = new mvCamCalendarAdmin();
		} else {
				
				$iMvCamCalendarAdmin = new mvCamCalendarAdmin();
				$mvEventLocationsAdmin = new mvEventLocationsAdmin();
				$mvUserUnitAdmin = new mvUserUnitAdmin();
				$mvAppointmentStatusAdmin = new mvAppointmentStatusAdmin();
				$mvCalendarColorsAdmin = new mvCalendarColorsAdmin();
				$mvBookingformTextsAdmin = new mvBookingformTextsAdmin();
				$mvCmSettingsAdmin = new mvCmSettingsAdmin();
				$mvMailTextSettingsAdmin = new mvMailTextSettingsAdmin();
				$mvCamConfigAdmin = new mvCamConfigAdmin();
				$mvCamBatchDeleteAdmin = new mvCamBatchDeleteAdmin();
				$mvDocumentation = new mvDocumentation();
				
				require_once('frontend/shortcodes.php');
				//require_once('lib/helper_functions.php');
				
				/*
				//Include Frontend extensions.
				require_once('frontend/shortcodes.php');
				require_once('lib/helper_functions.php');
				*/
				//The following actions are defined in the file lib/helper_functions.php
				
				
//				add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\mvimmo_load_plugin_css', 00 );		//Add custom CSS!
				//add_filter('query_vars', __NAMESPACE__ . '\\mvimmo_query_vars');
				//add_filter( 'template_include', __NAMESPACE__ . '\\mvimmo_template_include' );
		}
}

		
				