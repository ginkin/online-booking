<?php

class SLN_Enum_CheckoutFields
{
    static  $settings = [];
    static protected $fields = [];
    
    static protected $cache = [];
    
    static $default_fields_settings;
    static $field_type;
    static $field_widths;
    
    static $default_atts = [
        "key" => '',
        "label"     => '',
        "type"      => 'text',
        "width"     => 6,
        "required"  => false,
        "hidden"    => false,
        "options"   => '',
        "customer_profile" => false,
        "booking_hidden"   => false,
        "additional" => true,
        "default_value" => ''
    ];
    
    static function init(){
        
        self::$default_fields_settings = array(
            'firstname' => [ 'label' => __('First name', 'salon-booking-system'), 'required' => true,"customer_profile" => true ],
            'lastname'  => [ 'label'=> __('Last name', 'salon-booking-system'),"customer_profile" => true],
            'email'     => [ 'label' => __('E-mail', 'salon-booking-system'), 'required' => true,"customer_profile" => true ],
            'phone'     => [ 'label'=> __('Mobile phone', 'salon-booking-system'),"customer_profile" => true],
            'address'   => [ 'label'=> __('Address', 'salon-booking-system'),"customer_profile" => true, 'width' => 12],
        );
        
        self::$field_type = ['text'=> __('Text', 'salon-booking-system'),'textarea'=> __('Textarea', 'salon-booking-system'),'checkbox'=> __('Checkbox', 'salon-booking-system'),'select'=> __('Select', 'salon-booking-system')];
    
        self::$field_widths = [ 12 => __('Full', 'salon-booking-system'), 6 => __('Half', 'salon-booking-system'), 3 => __('Quarter', 'salon-booking-system')];
        
        $default_fields =  array_map(function($field){
            $field['additional'] = false;
            return $field;
        },self::$default_fields_settings);
        $plugin_settings = SLN_Plugin::getInstance()->getSettings();
        $settings = $plugin_settings->get('checkout_fields');
        
        if(is_array($settings) && !isset($settings['email']['customer_profile'])){
            $migrated_fields = self::migrateSettings( $settings ?: [] ,$default_fields);
            $plugin_settings->set('checkout_fields',$migrated_fields);
            $plugin_settings->save();
            $settings = $migrated_fields;
        }
        
        self::$settings = $settings ?: $default_fields;
        self::$fields= self::createFields();
    }

    static function refresh(){
        self::$settings = SLN_Plugin::getInstance()->getSettings()->get('checkout_fields');
        self::$fields = [];
        self::$cache = [];
        self::$fields = self::createFields();
    }

    static function createFields($settings = false){
        $settings = $settings ?: self::$settings;
        $fields = [];
        foreach ($settings as $field => $opts ) {
            $opts['key'] = $field;
            $fields[$field] = self::createField(self::validateField($opts));
        }
        return $fields;
    }
    
    protected static function getCollection($fields = false){
        if(!$fields){
            if(self::$fields === null) self::init();
            $fields = self::$fields;
        }
        return new SLN_CheckoutFieldsCollection($fields);
    }

    protected static function createField($opts){
        return new SLN_CheckoutField($opts);
    }
    
    protected static function validateField($opts = []){
        $ret =[];
        foreach (self::$default_atts as $key => $value) {
            if(array_key_exists($key, $opts)){
                if(!in_array($key,['default_value'])){
                    $type = gettype($value);
                                settype($opts[$key],$type);
                }
                if($key === 'type'){
                    $opts[$key] = in_array($opts[$key],array_keys(self::$field_type)) ? $opts[$key] : $value;
                }
                if($key === 'width'){
                    $opts[$key] = in_array($opts[$key],array_keys(self::$field_widths)) ? $opts[$key] : $value;
                }
                                
                if($value === false && in_array($opts[$key],['true','false'],true)){
                    $opts[$key] =  $opts[$key] === 'true' ? true : false;
                }
                $ret[$key] = $opts[$key];
            }else{
                $ret[$key] = $value;
            }
        }
        return $ret;
    }
    
    private static function migrateSettings($db_settings,$defaults){
        foreach ($db_settings as $key => $opts) {
            foreach ($opts as $opt => $value) {
                if($opt === 'require' ){ $opt = 'required';  }
                if($value === '1'){
                    $defaults[$key][$opt] = true;
                }
            }
        }
        $legacy_additional_fields = apply_filters('sln.checkout.additional_fields',array());
        $width_migration = ['half'=>6,'quarter'=>3,'full'=>12];
        foreach ($legacy_additional_fields as $field_key => $opts) {
            $new_field = [];
            foreach (self::$default_atts as $key => $default) {
                
                if(isset($opts[$key])){
                    $opt_value = $opts[$key];
                    if($key === 'default_value'){
                        $opt_value = $opts['default'];
                    }
                    if($key === 'width'){
                        $opt_value = $width_migration[$opt_value];  
                    }
                    if($key === 'customer_profile' && $opt_value === 'booking_hidden'){
                        $opt_value = true;
                        $new_field['booking_hidden'] = true;
                    }
                    
                    $new_field[$key] = $opt_value;              
                }elseif(!isset($new_field[$key])){
                    if($key === 'key') $default = $field_key;
                    $new_field[$key] = $default;
                }
            }
            $legacy_additional_fields[$field_key] = $new_field;
        }
        
        return array_merge($defaults,$legacy_additional_fields);
    }
    
    protected static function cache($value,$key){
        self::$cache[$key] = serialize($value);
        return $value;
    }

    protected static function getCache($key){
        if(array_key_exists($key,self::$cache)){
            return unserialize(self::$cache[$key]);
        }
        return false;
    }

    static function getField($key){
        return self::getCache( __METHOD__ ) ?: self::cache(self::getCollection()->getField($key), __METHOD__ );
    }
    
    static function all(){
        return self::getCache( __METHOD__ ) ?: self::cache(self::getCollection(), __METHOD__ );
    }
    
    static function defaults(){
        return self::getCache( __METHOD__ ) ?: self::cache(self::getCollection()->defaults(), __METHOD__ );
    }
    
    static function additional(){
        return self::getCache( __METHOD__ ) ?: self::cache(self::getCollection()->additional(), __METHOD__ );
    }
    
    static function forBooking(){
        return self::getCache( __METHOD__ ) ?: self::cache(self::getCollection()->filter("booking_hidden",false ), __METHOD__ );
    }
    
    static function forCustomer(){
        return self::getCache( __METHOD__ ) ?: self::cache(self::getCollection()->filter('customer_profile'), __METHOD__ );
    }
    
    static function forBookingAndCustomer(){
        return self::getCache( __METHOD__ ) ?: self::cache(self::forBooking()->intersect(self::forCustomer()), __METHOD__ );
    }    
    
    static function forBookingNotCustomer(){
        return self::getCache( __METHOD__ ) ?: self::cache(self::forBooking()->diff(self::forCustomer()), __METHOD__ );
    }    
    
    static function forRegistration(){
        return self::getCache( __METHOD__ ) ?: self::cache(self::forDetailsStep()->intersect(self::forCustomer()), __METHOD__ );
    }
    
    static function forGuestCheckout(){
         return self::getCache( __METHOD__ ) ?: self::cache(self::forDetailsStep()->filter(function($field){ return !$field->isCustomer() || !$field->isAdditional(); }), __METHOD__ );
    }
    
    static function forDetailsStep(){
        return self::getCache( __METHOD__ ) ?:  self::cache(self::getCollection()->filter('hidden',false), __METHOD__ );
    }
    
    static function passwordField(){
        return self::getCache( __METHOD__ ) ?: self::cache(self::getCollection(self::createFields([
            'password' => ['label' => __('Password', 'salon-booking-system')],
            'password_confirm' =>['label' => __('Confirm your password', 'salon-booking-system')],
        ])), __METHOD__ );
    }
    
    public static function hasSelectFields(){
        return self::cache((bool) self::getCollection()->filter('type','select')->count(), __METHOD__ );
    }
}



