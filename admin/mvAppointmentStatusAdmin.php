<?php

namespace mvclickandmeet_namespace;

if(!defined('ABSPATH')) { exit; }

class mvAppointmentStatusAdmin extends mvWpAdmin {
		//////////////////////////////////////////////////////////////////////////////
		// Admin Funktionalität initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function initAdminFunctions() {
				$this->addAdminSubMenu('mvcam', 'Termin-Status', 'Termin-Status', 'manage_options', 'mvcam_appointment_status');
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Wenn die Seite im Admin aufgerufen wird, Werte initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function adminPageRender() {
				$this->setModel('mvclickandmeet_namespace\\mvAppointmentStatus');
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
				$this->setTextVariable('listing_title', 'Termin-Status');
				$this->setTextVariable('editor_title', 'Termin-Status');
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
						)
						
						/*,
						
						
						array(
								'label' => 'Aktion',
								'type' => 'actions',
								'action' => array('edit', 'delete'),
								'field_name' => 'actions'
						)
						*/
				);
				
				$this->listing_definition = $fields_to_display;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Editor-Felder initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function initAdminEditorFields() {
				//Status Felder
				$internal_required_values = array(
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
												'type' => 'text'
										),
										
										array(
												'name' => 'internal_required',
												'label' => 'Intern benötigt',
												'type' => 'dropdown',
												'values' => $internal_required_values,
												'id_field' => 'id',
												'title_field' => 'label'
										)
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
				$mvAppointmentStatus = new mvAppointmentStatus();
				$id = $mvAppointmentStatus->create();
				
				$slug = $_GET['page'];
				header('Location: ' . admin_url('admin.php?page=' . $slug . '&action=edit&id=' . (int)$id));
				die;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Einen Eintrag in der Datenbank aktualisieren.
		//////////////////////////////////////////////////////////////////////////////
		function updateEntryInDatabase() {
				$id = (int)mvCore::getGetVar('id');
				
				$mvAppointmentStatus = new mvAppointmentStatus();
				$mvAppointmentStatus->load($id);
				$internal_required = $mvAppointmentStatus->getValueForField('internal_required');
				
				if($internal_required == 1) {
						$slug = $_GET['page'];
						header('Location: ' . admin_url('admin.php?page=' . $slug));
						die;
				}
				
				$mvAppointmentStatus->update($id);
				
				$slug = $_GET['page'];
				header('Location: ' . admin_url('admin.php?page=' . $slug . '&action=edit&id=' . (int)$id));
				die;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Einen Eintrag aus der Datenbank löschen.
		//////////////////////////////////////////////////////////////////////////////
		public function deleteConfirm() {
				$id = (int)mvCore::getGetVar('id');
				
				$mvAppointmentStatus = new mvAppointmentStatus();
				$mvAppointmentStatus->load($id);
				$internal_required = $mvAppointmentStatus->getValueForField('internal_required');
				
				if($internal_required == 1) {
						$slug = $_GET['page'];
						header('Location: ' . admin_url('admin.php?page=' . $slug));
						die;
				}
				
				$mvAppointmentStatus->delete($id);
				
				$slug = $_GET['page'];
				header('Location: ' . admin_url('admin.php?page=' . $slug));
				die;
		}
}
