<?php

namespace mvclickandmeet_namespace;

if(!defined('ABSPATH')) { exit; }

class mvEventLocationsAdmin extends mvWpAdmin {
		//////////////////////////////////////////////////////////////////////////////
		// Admin Funktionalität initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function initAdminFunctions() {
				$this->addAdminSubMenu('mvcam', 'Läden', 'Läden', 'manage_options', 'mvcam_event_locations');
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Wenn die Seite im Admin aufgerufen wird, Werte initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function adminPageRender() {
				$this->setModel('mvclickandmeet_namespace\\mvEventLocations');
				$this->initAdminEditorFields();
				$this->initAdminDeleteDialogFields();
				$this->initAdminListingDefinition();
				$this->addTextVariables();
				
				parent::adminPageRender();
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Text-Variablen festlegen.
		//////////////////////////////////////////////////////////////////////////////
		public function addTextVariables() {
				$this->setTextVariable('listing_title', 'Läden');
				$this->setTextVariable('editor_title', 'Läden');
				$this->setTextVariable('editor_title_new', 'Neuen Eintrag erstellen');
				$this->setTextVariable('editor_title_edit', 'Eintrag bearbeiten');
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Listing Definition für den Admin erstellen.
		//////////////////////////////////////////////////////////////////////////////
		public function initAdminListingDefinition() {
				//Felder definieren, die angezeigt werden sollen.
				$fields_to_display = array(
						array(
								'label' => 'ID',
								'type' => 'text',
								'field_name' => 'id',
								'width' => '2%'
						),
						array(
								'label' => 'Name',
								'type' => 'text',
								'field_name' => 'title',
								'width' => '40%'
						),
						array(
								'label' => 'Aktion',
								'type' => 'actions',
								'action' => array('edit', 'delete'),
								'field_name' => 'actions'
						)
				);
				
				$this->listing_definition = $fields_to_display;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Editor-Felder initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function initAdminEditorFields() {
				//Status Felder
				$booking_info_by_mail_values = array(
						array(
								'id' => '0',
								'label' => 'Nein'
						),
						array(
								'id' => '1',
								'label' => 'Ja'
						)
				);
				
				//Menü Definition
				$menu_definition = array(
						array(
								'id' => 'section1',
								'title' => 'Allgemeine Einstellungen',
								'fields' => array(
										array(
												'name' => 'id',
												'label' => 'ID',
												'type' => 'id'
										),
										array(
												'name' => 'title',
												'label' => 'Name',
												'type' => 'text'
										),
										
										array(
												'name' => 'description',
												'label' => 'Beschreibung',
												'type' => 'textarea'
										),
										
										array(
												'name' => 'email_address',
												'label' => 'E-Mail Adresse',
												'type' => 'text'
										),
										
										array(
												'name' => 'booking_info_by_mail',
												'label' => 'Buchungs-Mail senden',
												'type' => 'dropdown',
												'values' => $booking_info_by_mail_values,
												'id_field' => 'id',
												'title_field' => 'label'
										),
										
										array(
												'name' => 'custom_email_html',
												'label' => 'Eigener E-Mail Text HTML (Optional)',
												'type' => 'textarea'
										),
										
										array(
												'name' => 'custom_email_plain',
												'label' => 'Eigener E-Mail Text PLAIN (Optional)',
												'type' => 'textarea'
										),
								)
						)
				);
				
				$this->menu_definition = $menu_definition;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Editor-Felder initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function initAdminDeleteDialogFields() {
				//Menü Definition
				$delete_dialog_definition = array(
						array(
								'id' => 'section1',
								'title' => 'Allgemein',
								'fields' => array(
										array(
												'name' => 'id',
												'label' => 'ID',
												'type' => 'id'
										),
										array(
												'name' => 'title',
												'label' => 'Titel',
												'type' => 'text'
										)
								)
						)
				);
				
				$this->delete_dialog_definition = $delete_dialog_definition;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Einen Eintrag in der Datenbank erstellen.
		//////////////////////////////////////////////////////////////////////////////
		public function createEntryInDatabase() {
				$mvEventLocations = new mvEventLocations();
				$id = $mvEventLocations->create();
				
				$slug = $_GET['page'];
				header('Location: ' . admin_url('admin.php?page=' . $slug . '&action=edit&id=' . (int)$id));
				die;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Einen Eintrag in der Datenbank aktualisieren.
		//////////////////////////////////////////////////////////////////////////////
		function updateEntryInDatabase() {
				$id = (int)mvCore::getGetVar('id');
				
				$mvEventLocations = new mvEventLocations();
				$mvEventLocations->update($id);
				
				$slug = $_GET['page'];
				header('Location: ' . admin_url('admin.php?page=' . $slug . '&action=edit&id=' . (int)$id));
				die;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Einen Eintrag aus der Datenbank löschen.
		//////////////////////////////////////////////////////////////////////////////
		public function deleteConfirm() {
				$id = (int)mvCore::getGetVar('id');
				
				$mvEventLocations = new mvEventLocations();
				$mvEventLocations->delete($id);
				
				$slug = $_GET['page'];
				header('Location: ' . admin_url('admin.php?page=' . $slug));
				die;
		}
}
