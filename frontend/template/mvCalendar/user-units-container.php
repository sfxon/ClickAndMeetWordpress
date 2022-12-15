<?php

$show_event_location = true;
$event_location_default_value = false;

$show_user_unit = true;
$user_unit_default_value = false;

//Daten verarbeiten
if(isset($this->data['SHORTCODE_SETTINGS']['showLadenAuswahl']) && $this->data['SHORTCODE_SETTINGS']['showLadenAuswahl'] == 0) {
		$show_event_location = false;
}

if(isset($this->data['SHORTCODE_SETTINGS']['preSelectLadenAuswahlId'])) {
		$event_location_default_value = $this->data['SHORTCODE_SETTINGS']['preSelectLadenAuswahlId'];
}

if(isset($this->data['SHORTCODE_SETTINGS']['showTeamMitarbeiterAuswahl']) && $this->data['SHORTCODE_SETTINGS']['showTeamMitarbeiterAuswahl'] == 0) {
		$show_user_unit = false;
}

if(isset($this->data['SHORTCODE_SETTINGS']['preSelectTeamMitarbeiterAuswahl'])) {
		$user_unit_default_value = $this->data['SHORTCODE_SETTINGS']['preSelectTeamMitarbeiterAuswahl'];
}

if($show_user_unit) {
		?>
    <div class="kalender-user-unit">
        <label for="mv-kalender-user-unit"><?php echo $this->data['MV_CALENDAR_TEXTS']['user_unit_title']; ?></label>
        <select id="mv-kalender-user-unit" class="form-control">
            <option value="0"><?php echo $this->data['MV_CALENDAR_TEXTS']['user_unit_dropdown_all']; ?></option>
            <?php
            
            foreach($this->data['USER_UNITS'] as $uu) {
                $selected = '';
								
								if((int)$uu['id'] == (int)$user_unit_default_value) {
										$selected = ' selected="selected"';
								}
								
								echo '<option value="' . $uu['id'] . '" data-attr-event-location-id="' . $uu['event_location_id'] . '"' . $selected . '>' . $uu['title_long'] . '</option>';
            }
            
            ?>
        </select>
    </div>
   	<?php
} else {
		if(false === $user_unit_default_value) {
				$user_unit_default_value = 0;
		}
		?>
		<input type="hidden" id="mv-kalender-user-unit" value="<?php echo $user_unit_default_value; ?>" />
		<?php
}