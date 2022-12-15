<?php

$show_event_location = true;
$event_location_default_value = false;

//Daten verarbeiten
if(isset($this->data['SHORTCODE_SETTINGS']['showLadenAuswahl']) && $this->data['SHORTCODE_SETTINGS']['showLadenAuswahl'] == 0) {
		$show_event_location = false;
}

if(isset($this->data['SHORTCODE_SETTINGS']['preSelectLadenAuswahlId'])) {
		$event_location_default_value = $this->data['SHORTCODE_SETTINGS']['preSelectLadenAuswahlId'];
}

if($show_event_location) {
		?>
    <div class="kalender-event-location">
        <label for="mv-kalender-event-location"><?php echo $this->data['MV_CALENDAR_TEXTS']['event_location_title']; ?></label>
        <select id="mv-kalender-event-location" class="form-control">
            <option value="0"><?php echo $this->data['MV_CALENDAR_TEXTS']['event_locations_all']; ?></option>
            <?php
            
            foreach($this->data['EVENT_LOCATIONS'] as $el) {
                $selected = '';
								
								if((int)$el['id'] == (int)$event_location_default_value) {
										$selected = ' selected="selected"';
								}
								
								echo '<option value="' . $el['id'] . '"' . $selected . '>' . htmlspecialchars($el['title']) .'</option>';
            }
            
            ?>
        </select>
    </div>
		<?php
} else {
		if(false === $event_location_default_value) {
				$event_location_default_value = 0;
		}
		?>
		<input type="hidden" id="mv-kalender-event-location" value="<?php echo $event_location_default_value; ?>" />
		<?php
}