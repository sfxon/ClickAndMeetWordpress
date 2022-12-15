<?php

namespace mvclickandmeet_namespace;

if(!defined('ABSPATH')) { exit; }

class mvDocumentation extends mvWpAdmin {
		//////////////////////////////////////////////////////////////////////////////
		// Admin Funktionalität initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function initAdminFunctions() {
				$this->addAdminSubMenu('mvcam', 'Dokumentation', 'Dokumentation', 'manage_options', 'mvcam_documentation');
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Wenn die Seite im Admin aufgerufen wird, Werte initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function adminPageRender() {
				parent::adminPageRender();
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Wurde überschrieben, um eigene Art von Liste anzuzeigen.
		// Außerdem: Listing erweitern um Ajax Funktionalität für Switcher.
		//////////////////////////////////////////////////////////////////////////////
		public function renderList() {
				$renderer = mv_core()->get('mvRenderer');
				$renderer->render('admin/templates/site/adminMvDocumentation/list.php');
		}
}
	
	