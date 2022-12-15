class mvCmTimerLoad {
		//////////////////////////////////////////////////////////////////
		// Constructor.
		//////////////////////////////////////////////////////////////////
		constructor(base_url, event_location, user_unit_id, day, month, year) {
				this.event_location = event_location;
				this.user_unit_id = user_unit_id;
				this.day = day;
				this.month = month;
				this.year = year;
				this.baseUrl = ajaxurl;
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
				/*
				var get_params = {
						s: 'cAdminCmCalendar',
						action: 'ajaxLoadAppointments'
				};
				get_params = jQuery.param(get_params);
				*/
				
				var tmpUploadQueryBuilder = new mvUploadQueryBuilder();
				var get_params = tmpUploadQueryBuilder.makeByPlatform('wordpress', 'cAdminCmCalendar', 'ajaxLoadAppointments');
				
				var request = {
						url: queue.data.baseUrl + '?' + get_params,
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
				var time = moment(appointment.datetime_of_event, 'YYYY-MM-DD HH:mm:ss').format('HH:mm');
				
				//Parse user mail, phone, firstname, lastname, customer_number -> if there is any!
				var description = "";
				
				if(appointment.email_address.length > 0) {
						description += "E-Mail: " + appointment.email_address + "<br />";
				}
				
				if(appointment.phone.length > 0) {
						description += "Telefon: " + appointment.phone + "<br />";
				}
				
				if(appointment.firstname.length > 0) {
						description += "Vorname: " + appointment.firstname + "<br />";
				}
				
				if(appointment.lastname.length > 0) {
						description += "Nachname: " + appointment.lastname + "<br />";
				}
				
				if(appointment.customers_number.length > 0) {
						description += "Kundennummer: " + appointment.customers_number + "<br />";
				}
				
				//Ziel parsen..
				var event_location_info = "";
				
				if(appointment.event_location.length > 0) {
						event_location_info += "Ort: " + appointment.event_location;
				}
				
				//Status parsen
				var event_status = "";
				
				if(appointment.event_status.length > 0) {
						event_status = appointment.event_status;
				}
				
				dom.attr('data-attr-appointment-data-json', appointment_data_json);
				dom.find('.mv-cm-timer-time').html(time + " Uhr");
				dom.find('.mv-cm-timer-description').html(description);
				dom.find('.mv-cm-timer-user').html(event_location_info);
				dom.find('.mv-cm-timer-status').html(event_status);
				dom.find('.mv-cm-timer-action').html('');		//Action Part - keep empty for right now.
				
				dom.addClass('mv-cm-event-status-' + appointment.status);
				dom.addClass('mv-cm-event-id-' + appointment.id);
				
				jQuery('.times-list-content').append(dom);
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
				
				console.log(data);
				
				//mv_cm_timer = new mvCmTimer();
				window.mv_cm_timer.updateWithData(data);
		}
}
	