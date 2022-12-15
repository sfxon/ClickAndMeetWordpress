class mvCmCalLoadInitial {
		////////////////////////////////////////////////////////////////////////////
		// Konstruktor
		////////////////////////////////////////////////////////////////////////////
		constructor(data) {
				this.url_selector = null;
				this.url_controller = null;
				this.url_action = null;
				this.kalendar_container = null;
				this.timer_container = null;
				this.event_location_container = null;
				this.user_unit_container = null;
				this.showLadenAuswahl = 1;
				this.preSelectLadenAuswahlId = 0;
				this.showTeamMitarbeiterAuswahl = 1;
				this.preSelectTeamMitarbeiterAuswahl = 0;
				
				//Leave this values = 0, if you want to fetch the current moment!
				this.day = 0;
				this.month = 0;
				this.year = 0;
				
				if(typeof data != 'undefined') {
						if(typeof data.url_selector != 'undefined') {
								this.url_selector = data.url_selector;
						}
						
						if(typeof data.url_controller != 'undefined') {
								this.url_controller = data.url_controller;
						}
						
						if(typeof data.url_action != 'undefined') {
								this.url_action = data.url_action;
						}
						
						if(typeof data.kalendar_container != 'undefined') {
								this.kalendar_container = data.kalendar_container;
						}
						
						if(typeof data.timer_container != 'undefined') {
								this.timer_container = data.timer_container;
						}
						
						if(typeof data.event_location_container != 'undefined') {
								this.event_location_container = data.event_location_container;
						}
						
						if(typeof data.user_unit_container != 'undefined') {
								this.user_unit_container = data.user_unit_container;
						}
				}
				
				//Laden Auswahl abholen und vorparsen.
				this.showLadenAuswahl = this.fetchShortcodeAttributesField('#mv-shortcode-attributes-showLadenAuswahl', 1);
				this.preSelectLadenAuswahlId = this.fetchShortcodeAttributesField('#mv-shortcode-attributes-preSelectLadenAuswahlId', 0);
				this.showTeamMitarbeiterAuswahl = this.fetchShortcodeAttributesField('#mv-shortcode-attributes-showTeamMitarbeiterAuswahl', 1);
				this.preSelectTeamMitarbeiterAuswahl = this.fetchShortcodeAttributesField('#mv-shortcode-attributes-preSelectTeamMitarbeiterAuswahl', 1);
				
				this.base_url = '';
		}
		
		//////////////////////////////////////////////////////////////////
		// Shortcode auslesen.
		//////////////////////////////////////////////////////////////////
		fetchShortcodeAttributesField(id, default_value) {
				var tmp = jQuery(id).val();
				tmp = parseInt(tmp);
				
				if(isNaN(tmp)) {
						tmp = default_value;
				}
				
				return tmp;
		}
		
		////////////////////////////////////////////////////////////////////////////
		// Start request.
		////////////////////////////////////////////////////////////////////////////
		loadCalender() {
				var self = this;
				
				if(self.url_selector != null) {
						self.base_url = jQuery(this.url_selector).val();
				} else {
						self.base_url = '';
				}
				
				var callbacks = [
						{
								//Upload company if not selected..
								callback: self.prepareRequest,
								result_callback: self.processRequestResult,
								type: 'ajax'
						}
				];
			
				var my_queue = new mvUploadQueue(self, callbacks, self.error_callback);
				my_queue.process();
		}
		
		////////////////////////////////////////////////////////////////////////////
		// Request vorbereiten.
		////////////////////////////////////////////////////////////////////////////
		prepareRequest(queue) {
				/*
				var get_params = {
						s: queue.data.url_controller,
						action: queue.data.url_action
				};
				get_params = jQuery.param(get_params);
				*/
				var tmpUploadQueryBuilder = new mvUploadQueryBuilder();
				var get_params = tmpUploadQueryBuilder.makeByPlatform('wordpress', queue.data.url_controller, queue.data.url_action);
				
				var request = {
						url: queue.data.base_url + '?' + get_params,
						post_data: {
								event_location: queue.data.event_location,
								user_unit_id: queue.data.user_unit_id,
								day: queue.data.day,
								month: queue.data.month,
								year: queue.data.year,
								showLadenAuswahl: queue.data.showLadenAuswahl,
								preSelectLadenAuswahlId: queue.data.preSelectLadenAuswahlId,
								showTeamMitarbeiterAuswahl: queue.data.showTeamMitarbeiterAuswahl,
								preSelectTeamMitarbeiterAuswahl: queue.data.preSelectTeamMitarbeiterAuswahl
						},
						mode: 'POST'
				};
				
				return request;
		}
		
		//////////////////////////////////////////////////////////////////
		// Anfrage-Ergebnis verarbeiten.
		//////////////////////////////////////////////////////////////////
		processRequestResult(queue, result) {
				//Try to parse the result
				try {
						var data = jQuery.parseJSON(result);
						
						if(data.status == "success") {
								jQuery(queue.data.kalendar_container).html(data.data.calendar_html);
								jQuery(queue.data.timer_container).html(data.data.timer_html);
								jQuery(queue.data.event_location_container).html(data.data.event_location_html);
								jQuery(queue.data.user_unit_container).html(data.data.user_unit_html);
								
								mvCalMasterObject = new theCalender({
										elementEventLocationSelector: '#mv-kalender-event-location',
										elementUserUnitSelector: '#mv-kalender-user-unit',
										elementMonthInputSelector: '#current_month',
										elementYearInputSelector: '#current_year',
										elementPrevMonthSelector: '.kalender-top-nav-prev',
										elementNextMonthSelector: '.kalender-top-nav-next',
										daySelector: '.kalender-entry',
										daySelectorClickCallback: mv_calendar_day_clicked,
										url_controller: queue.data.url_controller
								});
						}
						
						return true;
				} catch(e) {
						console.log("Ergebnis: ", result, "Exception: ", e);
				}
				
				return false;
		}
		
		//////////////////////////////////////////////////////////////////
		// Data-Load error handler.
		//////////////////////////////////////////////////////////////////
		error_callback(error) {
				console.log(error);
				alert('Es ist ein Fehler aufgetreten. Weitere Informationen finden Sie in der Konsole.');
		}
}