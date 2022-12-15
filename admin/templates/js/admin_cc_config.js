jQuery(document).ready(function( $ ) {
		mv_init_event_location_select_button();
		mv_init_time_input_for_monday();
		mv_reset_abweichende_zeiten_action_buttons_actions();
		mv_init_weekdays_by_checkbox_status();
		mv_init_weekday_checkbox_click_action_handler();
		mv_calculate_automatic_times_display();		//Da wir Termine von Vortagen übernehmen, wenn keine abweichenden Zeiten eingetragen sind, zeigen wir das deutlich an.
		mv_init_create_appointment_buttons();
});

//////////////////////////////////////////////////////////////////////////////////////////
// Betriebsstätten Toggle Button Action aktivieren.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_init_event_location_select_button() {
		jQuery('#event_location').off('change');
		jQuery('#event_location').on('change', function() {
				//Dropdown-Liste für UserUnit (Team) aktualisieren.
				updateUserUnitList(jQuery(this).val());
		});
}

//////////////////////////////////////////////////////////////////
// User-Unit Liste aktualisieren
//////////////////////////////////////////////////////////////////
function updateUserUnitList(event_location) {
		jQuery('#user-unit').val(0);						//Reset chosen values..
		
		jQuery("#user-unit option").removeAttr('disabled');
		jQuery("#user-unit option").show();
			
		if(event_location == 0) {
				//Show all..
		} else {
				jQuery("#user-unit").find('option').each(function() {
						var item = this;
						
						var event_location_id = jQuery(item).attr('data-attr-event-location-id');
						var value = jQuery(item).val();
						
						//Nicht die "Alle" box ausblenden!
						if(value == 0) {
								return;
						}
						
						//Ausblenden, wenn diese Option nicht zur aktuell gewählten Event-Location passt.
						if(event_location_id != event_location) {
								jQuery(item).attr("disabled", "disabled");
								jQuery(item).hide();
						}
				});
		}
}

//////////////////////////////////////////////////////////////////////////////////////////
// Für den Montag wird ein erstes Zeitinterval initialisiert als Default..
//////////////////////////////////////////////////////////////////////////////////////////
function mv_init_time_input_for_monday() {
		mv_weektime_add_range(1);
}

//////////////////////////////////////////////////////////////////////////////////////////
// Zeitinterval hinzufügen
//////////////////////////////////////////////////////////////////////////////////////////
function mv_weektime_add_range(weekday) {
		var id = '#weekday-row-' + weekday;
		
		var template = jQuery('#mv-weekday-times-template').html();
		jQuery(id + ' .mv-weekday-times').append(template);
		
		mv_weektime_reset_action_handlers();
		mv_calculate_automatic_times_display();
		mv_init_time_input_change_action_handlers();
}

//////////////////////////////////////////////////////////////////////////////////////////
// Action Handler neu intialisieren.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_weektime_reset_action_handlers() {
		mv_weektime_reset_add_btns();
		mv_weektime_reset_del_btns();
}

//////////////////////////////////////////////////////////////////////////////////////////
// Action Handler für Weektime Add Action neu initialisieren.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_weektime_reset_add_btns() {
		jQuery('.weekday-time-add-btn').off('click');
		jQuery('.weekday-time-add-btn').on('click', function() {
				mv_weektime_add_button_clicked(this);
		});
}

//////////////////////////////////////////////////////////////////////////////////////////
// Action Handler für Weektime Del Action neu initialisieren.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_weektime_reset_del_btns() {
		jQuery('.weekday-time-del-btn').off('click');
		jQuery('.weekday-time-del-btn').on('click', function() {
				mv_weektime_del_button_clicked(this);
		});
}

//////////////////////////////////////////////////////////////////////////////////////////
// Action Handler für Weektime Add Action.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_weektime_add_button_clicked(item) {
		//Id heraussuchen.
		var parent = jQuery(item).closest('.mv-weekday-row');
		var id = jQuery(parent).attr('id');
		var id = id.replace('weekday-row-', '');
		
		//Eintrag hinzufügen.
		mv_weektime_add_range(id);
		mv_calculate_automatic_times_display();
}

//////////////////////////////////////////////////////////////////////////////////////////
// Action Handler für Weektime Del Action.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_weektime_del_button_clicked(item) {
		//Id heraussuchen.
		var parent = jQuery(item).closest('.mv-weekday-row');
		var id = jQuery(parent).attr('id');
		var id = id.replace('weekday-row-', '');
		
		//Eltern-Container für Liste auslesen.
		var rows_parent = jQuery(item).closest('.mv-weekday-times');
		
		//Id heraussuchen.
		var parent = jQuery(item).closest('.mv-weekday-times-row');
		jQuery(parent).remove();
		
		//Zeige "Abweichende Zeiten Button" an, wenn keine weiteren Zeiten mehr vorhanden sind..
		var items = jQuery(rows_parent).find('.mv-weekday-times-row');
		
		if(items.length == 0) {
				mv_show_abweichende_zeiten_button_for_weekday(id);
		}
		
		mv_calculate_automatic_times_display();
}

//////////////////////////////////////////////////////////////////////////////////////////
// "Abweichende Zeiten" Button anzeigen.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_show_abweichende_zeiten_button_for_weekday(weekday) {
		var id = '#weekday-row-' + weekday;
		jQuery(id).find('.mv-abweichende-zeiten-container').show();
		
		mv_reset_abweichende_zeiten_action_buttons_actions();
}

//////////////////////////////////////////////////////////////////////////////////////////
// "Abweichende Zeiten" Button verstecken.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_hide_abweichende_zeiten_button_for_weekday(weekday) {
		var id = '#weekday-row-' + weekday;
		jQuery(id).find('.mv-abweichende-zeiten-container').hide();
}

//////////////////////////////////////////////////////////////////////////////////////////
// "Abweichende Zeiten" Button Action Handler neu initialisieren.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_reset_abweichende_zeiten_action_buttons_actions() {
		jQuery('.add-custom-time-button').off('click');
		jQuery('.add-custom-time-button').on('click', function() {
				mv_add_abweichende_zeiten_button_clicked(this);
		});
}

//////////////////////////////////////////////////////////////////////////////////////////
// "Abweichende Zeiten" Button Action Handler.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_add_abweichende_zeiten_button_clicked(item) {
		//Id heraussuchen.
		var parent = jQuery(item).closest('.mv-weekday-row');
		var id = jQuery(parent).attr('id');
		var id = id.replace('weekday-row-', '');
		
		mv_hide_abweichende_zeiten_button_for_weekday(id);
		
		//Eintrag hinzufügen.
		mv_weektime_add_range(id);
		mv_calculate_automatic_times_display();
}

//////////////////////////////////////////////////////////////////////////////////////////
// Alle Werktage anhand des Checkbox Status initialisieren.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_init_weekdays_by_checkbox_status() {
		jQuery('.weekday-checkbox').each(function() {
				var item = this;
				
				mv_update_weekday_row_by_checkbox_by_status(item);
		});
}

//////////////////////////////////////////////////////////////////////////////////////////
// Werktag anhand des Checkbox Status aktualisieren.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_update_weekday_row_by_checkbox_by_status(item) {
		//Status abfragen
		var status = jQuery(item).is(':checked');
		
		//Zeilen-ID abfragen
		//Id heraussuchen.
		var parent = jQuery(item).closest('.mv-weekday-row');
		var id = jQuery(parent).attr('id');
		//var id = id.replace('weekday-row-', '');
		
		if(true == status) {
				jQuery('#' + id + ' .mv-weekday-times').show();
		} else {
				jQuery('#' + id + ' .mv-weekday-times').hide();
		}
}

//////////////////////////////////////////////////////////////////////////////////////////
// Action-Handler für Wochentag Checkboxen aktivieren.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_init_weekday_checkbox_click_action_handler() {
		jQuery('.weekday-checkbox').off('click');
		jQuery('.weekday-checkbox').on('click', function() {
				mv_update_weekday_row_by_checkbox_by_status(this);
				mv_calculate_automatic_times_display();
		});
}

//////////////////////////////////////////////////////////////////////////////////////////
// Automatische Zeiten berechnen und anzeigen.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_calculate_automatic_times_display() {
		var previous = [];
		
		jQuery('.mv-weekday-row').each(function() {
				var item = this;
				
				//Check if checkbox is active? No? not activated -> return Zero!
				//Status abfragen
				var status = jQuery(item).find('.weekday-checkbox').is(':checked');
				
				if(true == status) {
						//has items?
						var rows = mv_check_weekday_appointments(item);
						
						if(rows > 0) {		//yes? -> fetch and remember in previous..
								previous = mv_get_time_preview_values_from_row(item);
						} else {					//no? -> show previous -> no previous? -> show error!
								mv_show_weekday_time_preview(item, previous);
						}
				}
		});
}

//////////////////////////////////////////////////////////////////////////////////////////
// Prüfen, ob Wochentag über eingetragene Zeiten verfügt.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_check_weekday_appointments(item) {
		//Id heraussuchen.
		var parent = jQuery(item).closest('.mv-weekday-row');
		var id = jQuery(parent).attr('id');
		var id_numeric = id.replace('weekday-row-', '');
		
		//Eltern-Container für Liste auslesen.
		var rows_parent = jQuery(item).find('.mv-weekday-times');
		
		//Zeige "Abweichende Zeiten Button" an, wenn keine weiteren Zeiten mehr vorhanden sind..
		var items = jQuery(rows_parent).find('.mv-weekday-times-row');
		return items.length;
}

//////////////////////////////////////////////////////////////////////////////////////////
// Zeit-Array unter Button "Abweichende Zeiten einfügen" anzeigen.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_show_weekday_time_preview(item, times_array) {
		if(times_array.length == 0) {
				jQuery(item).find('.mv-abweichende-zeiten-details').html('<span style="color: red;">Warnung! Für diesen Tag ist keine Zeit eingetragen.</span>');
		} else {
				var final_text = "";
				
				for(i = 0; i < times_array.length; i++) {
						if(final_text.length > 0) {
								final_text += "<br />";
						}
						
						final_text += "von " + times_array[i].from;
						final_text += " bis " + times_array[i].to;
				}
				
				jQuery(item).find('.mv-abweichende-zeiten-details').html(final_text);
		}
}

//////////////////////////////////////////////////////////////////////////////////////////
// Zeitwerte als Array aus Wochentag-Zeilen auslesen.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_get_time_preview_values_from_row(item) {
		var retval = [];
		
		jQuery(item).find('.mv-weekday-times-row').each(function() {
				var row = this;
				var time_from = jQuery(this).find('.time_from').val();
				var time_to = jQuery(this).find('.time_to').val();
				
				retval.push(
						{
								from: time_from,
								to: time_to
						}
				);
		});
		
		return retval;
}

//////////////////////////////////////////////////////////////////////////////////////////
// Reinitialisisert die Action-Handler für die Zeit-Eingabefelder.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_init_time_input_change_action_handlers() {
		jQuery('.time_from').off('keyup');
		jQuery('.time_from').on('keyup', function() {
				mv_calculate_automatic_times_display();
		});
		
		jQuery('.time_to').off('keyup');
		jQuery('.time_to').on('keyup', function() {
				mv_calculate_automatic_times_display();
		});
}

//////////////////////////////////////////////////////////////////////////////////////////
// Button zum Erstellen der Termine angeklickt.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_init_create_appointment_buttons() {
		jQuery('#create-appointments').off('click');
		jQuery('#create-appointments').on('click', function() {
				if(false == mv_cc_check_values()) {
						return false;
				}
				
				mv_cc_upload_start();
		});
}

//////////////////////////////////////////////////////////////////////////////////////////
// Button "von vorn" angeklickt.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_init_restart_button() {
		jQuery('#mv-process-start-again').off('click');
		jQuery('#mv-process-start-again').on('click', function() {
				//Reset selection of "event location"
				mv_reset_event_location();
				
				jQuery('#mv-success-container').hide();
				jQuery('#mv-config-container').show();
		});
}

//////////////////////////////////////////////////////////////////////////////////////////
// Event-Location zurücksettzen.
//////////////////////////////////////////////////////////////////////////////////////////
function mv_reset_event_location() {
		jQuery('#betriebsstaette').val(0);
		jQuery('#betriebsstaette-toggle-button').attr('data-attr-current-status', 'select');
		jQuery('#betriebsstaette-new-container').hide();
		jQuery('#betriebsstaette-select-container').show();
}




