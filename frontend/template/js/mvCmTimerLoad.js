class mvCmTimerLoad {
		//////////////////////////////////////////////////////////////////
		// Constructor.
		//////////////////////////////////////////////////////////////////
		constructor(base_url, url_controller, event_location, user_unit_id, day, month, year) {
				this.event_location = event_location;
				this.user_unit_id = user_unit_id;
				this.day = day;
				this.month = month;
				this.year = year;
				this.baseUrl = base_url;
				this.url_controller = url_controller;
		}
		
		//////////////////////////////////////////////////////////////////
		// Kalenderdaten - Querydaten aufbereiten.
		//////////////////////////////////////////////////////////////////
		startRequest() {
				var self = this;
				
				var callbacks = [
						{
								//Upload company if not selected..
								callback: self.prepareTimerRequest,
								result_callback: self.processTimerRequestResult,
								type: 'ajax'
						}
				];
			
				var my_queue = new mvUploadQueue(self, callbacks, this.error_callback);
				my_queue.process();
		}
		
		//////////////////////////////////////////////////////////////////
		// Timer-Daten - Querydaten für Ajax Request vorbereiten.
		//////////////////////////////////////////////////////////////////
		prepareTimerRequest(queue) {
				var tmpUploadQueryBuilder = new mvUploadQueryBuilder();
				var get_params = tmpUploadQueryBuilder.makeByPlatform('wordpress', queue.data.url_controller, 'ajaxLoadAppointments');
				
				var request = {
						url: queue.data.baseUrl + '/admin-ajax.php?' + get_params,
						post_data: {
								event_location: queue.data.event_location,
								user_unit_id: queue.data.user_unit_id,
								day: queue.data.day,
								month: queue.data.month,
								year: queue.data.year
						},
						mode: 'POST'
				};
				
				return request;
		}
		
		//////////////////////////////////////////////////////////////////
		// Anfrage-Ergebnis verarbeiten.
		//////////////////////////////////////////////////////////////////
		processTimerRequestResult(queue, result) {
				//Try to parse the result
				try {
						var data = jQuery.parseJSON(result);
						
						if(data.status == "success") {
								if(typeof data.data.appointments != "undefined") {
										//Datum anzeigen.
										var date = data.data.day + '.' + data.data.month + '.' + data.data.year;
										jQuery('.times-list-top-left').html(date);
										
										//Termine anzeigen.
										if(data.data.appointments.length == 0) {
												jQuery('.times-list-top-right-count').html("");
												jQuery('.times-list-content').html('Keine Termine an diesem Tag');
										} else {
												jQuery('.times-list-top-right-count').html(data.data.appointments.length.toString() + ' Termine');
												jQuery('.times-list-content').html('');
												
												for(var i = 0; i < data.data.appointments.length; i++) {
														queue.data.addAppointmentToTimer(data.data.appointments[i]);
														queue.data.initTimerListEntryClickActions(queue.data);
												}
										}
								}
						}
						
						return true;
				} catch(e) {
						console.log("Ergebnis: ", result, "Exception: ", e);
				}
				
				return false;
		}
		
		//////////////////////////////////////////////////////////////////
		// Eintrag im Kalender hinzufügen.
		//////////////////////////////////////////////////////////////////
		addAppointmentToTimer(appointment) {
				var timer_template = jQuery('#mv-cm-timer-template').html();
				var dom = jQuery(jQuery.parseHTML(timer_template));
				
				var appointment_data_json = JSON.stringify(appointment);
				
				//Parse time
				var time = moment(appointment.datetime_of_event, 'YYYY-MM-DD HH:mm:ss');
				var time_formated = this.buildTimeOutput(time, appointment.duration_in_minutes);
				
				//Parse user mail, phone, firstname, lastname, customer_number -> if there is any!
				var description = "";
				
				if(appointment.user_unit.length > 0) {
						description += "Abteilung: " + appointment.user_unit;
				}
				
				//Ziel parsen..
				var description = "";
				
				if(appointment.event_location.length > 0) {
						description += "Ort: " + appointment.event_location;
				}
				
				var event_location_info = "";
				
				if(appointment.user_unit.length > 0) {
						event_location_info += "Abteilung: " + appointment.user_unit;
				}
				
				//Status parsen
				var event_status = "";
				
				if(appointment.event_status.length > 0) {
						event_status = appointment.event_status;
				}
				
				dom.attr('data-attr-appointment-data-json', appointment_data_json);
				dom.find('.mv-cm-timer-time').html(time_formated);
				dom.find('.mv-cm-timer-description').html(description);
				dom.find('.mv-cm-timer-user').html(event_location_info);
				dom.find('.mv-cm-timer-status').html(event_status);
				dom.find('.mv-cm-timer-action').html('');		//Action Part - keep empty for right now.
				
				dom.addClass('mv-cm-event-status-' + appointment.status);
				dom.addClass('mv-cm-event-id-' + appointment.id);
				
				jQuery('.times-list-content').append(dom);
		}
		
		//////////////////////////////////////////////////////////////////
		// Ausgabe der Zeit erstellen.
		// Parameter:
		//	time: Ein Objekt vom Klassentyp moment.
		//	duration_in_minutes: integer
		//////////////////////////////////////////////////////////////////
		buildTimeOutput(time, duration_in_minutes) {
				var output = "";
				var time_setting = jQuery('#mv-shortcode-attributes-time').val();
				
				console.log(time_setting);
				
				if(time_setting == 'duration') {		//Erste Option -> Dauer mit anzeigen.
						var hours = parseInt(duration_in_minutes / 60);
						var minutes = parseInt(duration_in_minutes % 60);
						
						output = time.format('HH:mm') + " Uhr";
						output += '<div>Dauer: ';
						
						if(hours > 0) {
								//Stunden ausgeben.
								output += hours + '&nbsp;';
								
								if(hours == 1) {
										output += "Stunde ";
								} else {
										output += "Stunden ";
								}
								
								//Minuten nur anzeigen, wenn die Stunde nicht rund ist..
								if(minutes > 0) {
										output += minutes + '&nbsp;';
										
										if(minutes == 1) {
												output += 'Minute';
										} else {
												output += 'Minuten';
										}
								}
						} else {
								//Nur Minuten ausgeben (weniger als eine Stunde)
								output += duration_in_minutes;
							
								if(duration_in_minutes == 1) {
										output += '&nbsp;Minute';
								} else {
										output += '&nbsp;Minuten';
								}
						}
						
						output += "</div>";
				} else if(time_setting == 'from_to') {		//Zweite Option -> Start und Ziel-Zeit anzeigen.
						var dest = time.clone().add(duration_in_minutes, 'minutes');		//moment.js time has to be cloned. otherwise it will alter the original time.
						
						output = time.format('HH:mm') + " Uhr";
						output += ' - ';
						output += dest.format('HH:mm') + " Uhr";
				} else {		//Dritte Option: Nur die Zeit anzeigen.
						output = time.format('HH:mm') + " Uhr";
				}
				
				return output;
		}
		
		strPadLeft(value, maxLength) { 
				return String('0'.repeat(maxLength) + value).slice(-maxLength); 
		}
		
		//////////////////////////////////////////////////////////////////
		// Wenn auf einem Eintrag in der Liste geklickt wurde:
		// Timer-Editor anzeigen!
		//////////////////////////////////////////////////////////////////
		initTimerListEntryClickActions(self) {
				jQuery('.mv-cm-timer-row').off('click');
				jQuery('.mv-cm-timer-row').on('click', function(event) {
						event.stopPropagation();
						event.preventDefault();
						
						self.timerListEntryClickAction(this, self);
				});
		}
		
		//////////////////////////////////////////////////////////////////
		// Click-Action Handler: Wenn auf einen Eintrag in der Liste
		// geklickt wurde.
		//////////////////////////////////////////////////////////////////
		timerListEntryClickAction(item, self) {
				var data = null;
				
				try {
						data = jQuery.parseJSON( jQuery(item).attr('data-attr-appointment-data-json') );
				} catch(e) {
						alert('Es ist ein Fehler aufgetreten. Weitere Informationen finden Sie in der Console.');
						console.log('Fehler in mvCmTimerLoad.js -> Funktion timerListEntryClickAction beim parse eines JSON Strings.');
						console.log(e);
						return;
				}
				
				window.mv_cm_timer.updateWithData(data);
		}
}
	