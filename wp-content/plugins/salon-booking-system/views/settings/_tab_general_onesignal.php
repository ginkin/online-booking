<?php
/**
 * @var $plugin SLN_Plugin
 * @var $helper SLN_Admin_Settings
 */
?>
<div id="sln-api_services" class="sln-box sln-box--main">
<h2 class="sln-box-title"><?php _e('API services', 'salon-booking-system')?></h2>
<div class="sln-box--sub row">
    <div class="col-xs-12">
        <h2 class="sln-box-title"><?php _e('Onesignal Notifications service', 'salon-booking-system')?></h2>
    </div>
    <div class="col-xs-12 col-sm-6">
        <div class="row">
            <div class="col-xs-12 sln-input--simple">
                <?php $helper->row_input_text(
	'onesignal_app_id',
	__('App ID', 'salon-booking-system')
);?>
            </div>
            <div class="col-xs-12">
                <div class="sln-checkbox">
                <?php $helper->row_input_checkbox(
	'onesignal_new',
	__('Send Onesignal notification on new bookings', 'salon-booking-system')
);?>            </div>
                <div class="sln-box-maininfo">
                    <p class="sln-box-info"><?php _e('Onesignal notification will be sent to a staff member', 'salon-booking-system');?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <div class="row">
	    <div class="col-xs-12 sln-input--simple">
		<?php $helper->row_input_textarea('onesignal_notification_message', __('Customize the Onesignal notification message', 'salon-booking-system'), array(
	'textarea' => array(
		'attrs' => array(
			'placeholder' => str_replace("\r\n", " ", SLN_Admin_SettingTabs_GeneralTab::getDefaultOnesignalNotificationMessage()),
		),
	),
));?>
		<p class="sln-input-help">
		    <?php _e('You can use [NAME], [SALON NAME], [DATE], [TIME], [PRICE], [BOOKING ID]', 'salon-booking-system')?>
		</p>
	    </div>
	</div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="sln-box--sub row">
    <div class="col-xs-12">
        <h2 class="sln-box-title"><?php _e('Google Maps API', 'salon-booking-system')?></h2>
    </div>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12 col-sm-8 form-group sln-input--simple">
                <input type="hidden" id="salon_settings_google_maps_api_key_valid" name="salon_settings[google_maps_api_key_valid]"
                value="<?php echo $helper->getOpt('google_maps_api_key_valid', isset($settings['default']) ? $settings['default'] : null) ?>">
                <?php $helper->row_input_text(
	'google_maps_api_key',
	__('Google Maps API Key', 'salon-booking-system')
);?>
            </div>
        </div>
    </div>
</div>
</div>