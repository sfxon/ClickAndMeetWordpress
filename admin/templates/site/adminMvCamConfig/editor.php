<div class="container-fluid">
		<div class="mv-box">
				<div class="mvbox">
						<div class="mvbox-body">
								<div class="row">
										<div class="col-xs-12" id="mv-config-container">
                    		<div class="alert alert-danger" role="alert"><b>WARNUNG!</b> 
                        Mit diesem Werkzeug können sehr viele Einträge auf einmal erstellt werden. 
                        Bitte legen Sie vor dem Start unbedingt ein Backup Ihrer Daten an! 
                        Diese Aktion kann nicht rückgängig gemacht werden!
                        Ein falsch eingestellter Server oder falsch eingestellter Import kann hierbei zu unerwarteten Problemen führen.<br />
												Bitte beachten Sie auch die Leistungsgrenzen Ihrer MySQL Datenbank (bspw. mehr als 2 Millionen Datensätze..).
                    		</div>
                    
                        <h1>Kalender - Termin Erzeugungs-Assistent</h1>
                        
                        <p>Wenn Sie keinen Laden und kein Team/Mitarbeiter wählen, werden die Termine für alle Läden und Teams angelegt.</p>
                    
                        <div class="row" id="betriebsstaette-select-container">
                        		<div class="form-group col-sm-12">
                            		<label for="event_location">Laden:</label><br />
                                <select name="event_location" id="event_location" class="form-control" style="">
                                		<option value="0">-- Alle --</option>
                                    
                                    <?php
																				foreach($this->data['EVENT_LOCATIONS'] as $el) {
																						echo '<option value="' . $el['id'] . '">' . htmlspecialchars($el['title']) . '</option>';
																				}
																		?>
                                </select>
                            </div>
                        </div>
                        
                        <div id="error-event_location" class="mverror" style="display: none;">
                        </div>
                        
                        <p>Wenn Sie kein Team/Mitarbeiter wählen, werden die Daten für alle Teams/Mitarbeiter des gewählten Ladens erstellt.</p>
                        
                        <div class="row" id="user-unit-select-container">
                        		<div class="form-group col-sm-12">
                            		<label for="user-unit">Team/Mitarbeiter:</label><br />
                                <select name="user-unit" id="user-unit" class="form-control" style="">
                                		<option value="0">-- Alle --</option>
                                    
                                    <?php
																				foreach($this->data['USER_UNITS'] as $uu) {
																						echo '<option value="' . $uu['id'] . '" data-attr-event-location-id="' . $uu['event_location_id'] . '">' . htmlspecialchars($uu['title_long']) . '</option>';
																				}
																		?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-sm-12" style="padding-top: 24px;">
                                <label for="zeitraum">Zeitraum</label>
                                <div>
                                von <input type="text" class="form-control" placeholder="Datum von" id="date_from" value="<?php echo $this->data['DATE_FROM']; ?>" style="width: 100px!important; display: inline!important;" />
                                bis <input type="text" class="form-control" placeholder="Datum bis" id="date_to" value="<?php echo $this->data['DATE_TO']; ?>" style="width: 100px!important; display: inline!important; " />
                                </div>
                            </div>
                        </div>
                        
                        <div id="error-zeitraum" class="mverror" style="display: none;">
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-sm-12" style="padding-top: 24px;">
                                <label for="nka_branche">Werktage und Arbeitszeiten auswählen</label>
                                <div class="mv-weekday-row" id="weekday-row-1">
                                		<fieldset>
                                        <label for="weekday-1" class="weekday-label">
                                            <input type="checkbox" class="form-control weekday-checkbox" id="weekday-1" checked="checked" />
                                            Montag
                                        </label>
                                    </fieldset>
                                    
                                    <div class="mv-weekday-times">
                                    		<div class="mv-abweichende-zeiten-container" style="display: none;">
                                        		<button type="btn" class="form-control btn btn-primary add-custom-time-button" >Abweichende Zeiten einfügen</button>
                                            <div class="mv-abweichende-zeiten-details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mv-weekday-row" id="weekday-row-2">
                                		<fieldset>
                                        <label for="weekday-2" class="weekday-label">
                                        		<input type="checkbox" class="form-control weekday-checkbox" id="weekday-2" checked="checked" />
                                        		Dienstag
                                        </label>
                                    </fieldset>
                                    
                                    <div class="mv-weekday-times">
                                    		<div class="mv-abweichende-zeiten-container">
                                        		<button type="btn" class="form-control btn btn-primary add-custom-time-button" >Abweichende Zeiten einfügen</button>
                                            <div class="mv-abweichende-zeiten-details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mv-weekday-row" id="weekday-row-3">
                                		<fieldset>
                                        <label for="weekday-3" class="weekday-label">
                                            <input type="checkbox" class="form-control weekday-checkbox" id="weekday-3" checked="checked" />
                                            Mittwoch
                                        </label>
                                    </fieldset>
                                    
                                    <div class="mv-weekday-times">
                                    		<div class="mv-abweichende-zeiten-container">
                                        		<button type="btn" class="form-control btn btn-primary add-custom-time-button">Abweichende Zeiten einfügen</button>
                                            <div class="mv-abweichende-zeiten-details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mv-weekday-row" id="weekday-row-4">
                                		<fieldset>
                                        <label for="weekday-4" class="weekday-label">
                                            <input type="checkbox" class="form-control weekday-checkbox" id="weekday-4" checked="checked" />
                                            Donnerstag
                                        </label>
                                    </fieldset>
                                    
                                    <div class="mv-weekday-times">
                                    		<div class="mv-abweichende-zeiten-container">
                                        		<button type="btn" class="form-control btn btn-primary add-custom-time-button">Abweichende Zeiten einfügen</button>
                                            <div class="mv-abweichende-zeiten-details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mv-weekday-row" id="weekday-row-5">
                                		<fieldset>
                                        <label for="weekday-5" class="weekday-label">
                                            <input type="checkbox" class="form-control weekday-checkbox" id="weekday-5" checked="checked" />
                                            Freitag
                                        </label>
                                    </fieldset>
                                    
                                    <div class="mv-weekday-times">
                                    		<div class="mv-abweichende-zeiten-container">
                                        		<button type="btn" class="form-control btn btn-primary add-custom-time-button">Abweichende Zeiten einfügen</button>
                                            <div class="mv-abweichende-zeiten-details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mv-weekday-row" id="weekday-row-6">
                                		<fieldset>
                                        <label for="weekday-6" class="weekday-label">
                                            <input type="checkbox" class="form-control weekday-checkbox" id="weekday-6" checked="checked" />
                                            Samstag
                                        </label>
                                    </fieldset>
                                    
                                    <div class="mv-weekday-times">
                                    		<div class="mv-abweichende-zeiten-container">
                                        		<button type="btn" class="form-control btn btn-primary add-custom-time-button">Abweichende Zeiten einfügen</button>
                                            <div class="mv-abweichende-zeiten-details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mv-weekday-row" id="weekday-row-7">
                                		<fieldset>
                                    		<label for="weekday-7" class="weekday-label">
                                            <input type="checkbox" class="form-control weekday-checkbox" id="weekday-7" />
                                            Sonntag
                                    		</label>
                                    </fieldset>
                                    
                                    <div class="mv-weekday-times">
                                    		<div class="mv-abweichende-zeiten-container">
                                        		<button type="btn" class="form-control btn btn-primary add-custom-time-button">Abweichende Zeiten einfügen</button>
                                            <div class="mv-abweichende-zeiten-details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-sm-12" style="padding-top: 24px;">
                                <label for="dauer-in-minuten">Dauer je Termin in Minuten</label>
                                <div>
                                		<input type="text" class="form-control" placeholder="Dauer in Minuten" id="dauer_in_minuten" value="25" style="width: 100px!important; display: inline!important;" />
                                </div>
                                <div>Der Assistent verteilt Termine in Abständen von den hier angegebenen Minuten in die oben angegebenen Zeiträume.</div>
                                <div id="error-dauer_in_minuten" class="mverror" style="display: none;">
				                        </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-sm-12" style="padding-top: 24px;">
                                <label for="dauer-in-minuten">Anzahl gleichzeitiger Termine</label>
                                <div>
                                		<input type="text" class="form-control" placeholder="Anzahl gleichzeitiger Termine" id="appointment_count" value="1" style="width: 100px!important; display: inline!important;" />
                                    <div>Anzahl der Termine, die zum gleichen Zeitpunkt eingetragen werden. Ist hier bspw. eine 2 eingetragen, sind zu jeden Zeitpunkt 2 Buchungen möglich.</div>
                                </div>
                                <div id="error-appointment_count" class="mverror" style="display: none;">
				                        </div>
                            </div>
                        </div>
                        
                        <div id="mverror-general" style="display: none;">Es sind Fehler aufgetreten. Bitte prüfen Sie Ihre Eingaben!</div>
														
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <button type="submit" class="btn btn-primary" id="create-appointments">Termine anlegen</button>
                            </div>
                        </div>
										</div>
                    
                    <div class="col-xs-12" id="mv-process-container" style="display: none;">
                    		<h1>Verarbeitung gestartet - Termine werden angelegt.</h1>
                        <p>Verarbeitung gestartet. Bitte schließe dieses Fenster nicht, bis alle Termine angelegt sind.</p>
                        <div class="mv-loading-spinner-container">
                        		<div class="mv-loading-spinner"><div></div><div></div><div></div></div>
                            <div class="mv-loading-spinner-text" id="mv-loading-spinner-text">Verarbeitung wird gestartet</div>
                        </div>
                        <div id="mv-loading-error" style="display: none;"></div>
                    </div>
                    
                    <div class="col-xs-12" id="mv-success-container" style="display: none;">
                    		<h1>Fertig.</h1>
                        <p>Super! Die Termine wurden angelegt. Ab sofort können Ihre Kunden weitere Termine buchen.</p>
                        <div>
                            <button class="btn btn-primary" id="mv-process-start-again">Weitere Termine hinzufügen</button>
                        </div>
                    </div>
								</div>
						</div>
				</div>
		</div>
</div>

<input type="hidden" id="url" value="{$TEMPLATE_URL}" />











<div id="mv-weekday-times-template" style="display: none;">
    <div class="mv-weekday-times-row">
        von <input type="text" class="form-control time_from" placeholder="von" name="time_from" value="09:00" style="width: 100px!important; display: inline!important;" />
        bis <input type="text" class="form-control time_to" placeholder="bis" name="time_to" value="12:00" style="width: 100px!important; display: inline!important; " />
        <button type="btn" class="form-control btn btn-primary weekday-time-add-btn" style="width: auto!important; display: inline!important;">+ Zeitraum hinzufügen</button>
        <button type="btn" class="form-control btn btn-danger weekday-time-del-btn" style="width: auto!important; display: inline!important;">- Entfernen</button>
        <div class="mv-time-error-general mverror" style="display: none; margin-bottom: 6px;"></div>
        <div class="mv-time-error-range mverror" style="display: none; margin-bottom: 6px;"></div>
        </div>
    </div>
</div>