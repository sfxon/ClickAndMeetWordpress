<div class="times-list">
    <input type="hidden" id="mv-times-list-current-date-day" value="<?php echo $this->data['DATA']['date']['today_day']; ?>" />
    <input type="hidden" id="mv-times-list-current-date-month" value="<?php echo $this->data['DATA']['date']['today_month']; ?>" />
    <input type="hidden" id="mv-times-list-current-date-year" value="<?php echo $this->data['DATA']['date']['today_year']; ?>" />
    <div class="times-list-title"><?php echo $this->data['MV_CALENDAR_TEXTS']['timer_title']; ?></div>
    <div class="times-list-top">
        <div class="times-list-top-left"><?php echo $this->data['MV_CALENDAR_TEXTS']['loading_data_title']; ?></div>
        <div class="times-list-top-right pull-right">
            <div class="times-list-top-right-count"></div>
            <div class="times-list-top-right-add">
                &nbsp;
            </div>
        </div>
        <div class="mv-clearfix"></div>
    </div>
    <div class="times-list-content">
    </div>
</div>

<div id="mv-cm-timer-template" style="display: none;" data-attr-appointment-data-json="">
		<div class="mv-cm-timer-row">
    		<div class="mv-cm-timer-time"></div>
        <div class="mv-cm-timer-description"></div>
        <div class="mv-cm-timer-user"></div>
        <div class="mv-cm-timer-action">
        		<button type="button" class="btn btn-primary mv-cm-timer-action-edit"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_title']; ?></button>
        </div>
    </div>
</div>