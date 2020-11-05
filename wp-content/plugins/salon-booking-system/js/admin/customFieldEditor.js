( function($,getFieldDefault){
	var UPDATE_FIELD_BUTTON_TEXT = salonCheckoutFieldsEditor_l10n['update_field'];
	var ADD_FIELD_BUTTON_TEXT = salonCheckoutFieldsEditor_l10n['add_field'];
	var REQUIRED_BY_DEFAULT = ['label','type'];
	
	var FIELD_KEY = 'checkout_fields';
	var EDITOR_KEY = 'fields_editor';
	var UPDATE_KEY = 'update';
	var NEW_KEY = 'new';

	var EDITOR_SELECTOR = '#fields-editor';
	var ROW_SELECTOR = '.sln-checkout-fields--row';
	var EDIT_BUTTON_SELECTOR = '.sln-custom-fields-edit';
	var DELETE_BUTTON_SELECTOR = '.sln-custom-fields-delete';
	var EDITOR_BUTTON_SELECTOR = '.field-editor-button';
	var EDITOR_CLOSE_BUTTON_SELECTOR = '.fields-editor-close';
	var ROW_WRAPPER_SELECTOR = '.sln-checkout-fields--row-wrapper';
	var LABEL_INPUT_SELECTOR = '#fields_editor_label';
	var TYPE_SELECT_SELECTOR = '#fields_editor_type';
	var OPTIONS_TEXT_AREA_SELECTOR = '#fields_editor_options';
	var GRIP_CELL_SELECTOR = '.sln-checkout-fields--grip--cell';
	var DEFAULT_VALUE_TEXT_COL_SELECTOR = '#sln-fields-editor-default-field-text-wrapper';
	var DEFAULT_VALUE_CHECKBOX_COL_SELECTOR = '#sln-fields-editor-default-field-checkbox-wrapper';
	
	var isValidEditor = true;
	
	var slugify = function (str) {
		var separator = "_";
	    return str
	        .toString()
	        .normalize('NFD')                   // split an accented letter in the base letter and the acent
	        .replace(/[\u0300-\u036f]/g, '')   // remove all previously split accents
	        .toLowerCase()
	        .trim()
	        .replace(/[^a-z0-9 ]/g, '')   // remove all chars not letters, numbers and spaces (to be replaced)
	        .replace(/\s+/g, separator);
	};
	
	var rowToField = function(mode,key){
	    var defaultField = getFieldDefault();
	    Object.keys(defaultField).forEach(function(k){
	    	var selector = '#' + ( mode === EDITOR_KEY ? 
	    		mode + '_' + k : 
	    		'salon_settings_' + mode + '_' + key +'_' + k 
	    		)
	    	if(k === 'default_value' && mode === EDITOR_KEY){
	    		var field_type = $('#'+ mode + '_type').find('option:selected').val();
				selector = '#sln-fields-editor-default-field-'+(field_type === 'checkbox' ? 'checkbox' : 'text') +'-wrapper input'	    		
	    	}
	    	var el = $(selector);
	    	var v = el.is(':checkbox') ? el.is(':checked') : ( el.is('select') ? el.find('option:selected').val() : el.val() );
	    	defaultField[k] = v;
	    })
	    return defaultField;
	}
	
	var fieldToRow = function(field,mode,key){
		if(mode === EDITOR_KEY){
			$(EDITOR_SELECTOR).find('input,select,textarea').prop('disabled',false);
		}
		Object.keys(field).forEach(function(k){
			var selector = '#' + ( mode === EDITOR_KEY ? 
	    		mode + '_' + k : 
	    		'salon_settings_' + mode + '_' + key +'_' + k 
	    		)
			if(k === 'default_value' && mode === EDITOR_KEY){
				selector = '#sln-fields-editor-default-field-'+(field.type === 'checkbox' ? 'checkbox' : 'text') +'-wrapper input'
			}
	    	if(mode === FIELD_KEY && ['additional','default_value'].indexOf(k) !== -1 && [true,false].indexOf(field[k]) !== -1){
	    		field[k] = field[k] === true ? 'true' : 'false';
			}
	    	var el = $(selector);
	    	el.is(':checkbox') ? el.prop( "checked", field[k] ) : el.val(field[k]);
	    	if(el.is('select')){
	    		el.change()
	    	}
	    	
	    	if(mode === EDITOR_KEY && !field.additional){
	    		var isreadonly = $('#salon_settings_' + FIELD_KEY + '_' + key +'_' + k).prop('disabled');
	    		if(isreadonly || 'type' === k) el.prop('disabled',true);
	    	}
	    	
	    	if(mode === FIELD_KEY && REQUIRED_BY_DEFAULT.indexOf(k) !== -1){
	    		var val = k === 'type' ? $( TYPE_SELECT_SELECTOR + ' option[value='+field[k]+']').text() : field[k];
	    		$('.sln_'+key+'_'+k+'_cell').text(val)
	    	}
	    })
	}
	
	var newFieldToRow = function(field,key){
		var row = $(ROW_SELECTOR+'[data-index=firstname]').clone();
		var temp = $('<div>').append(row);
		var html = temp.html();				
		temp.html(html.replace(/firstname/g,key));
		row = temp.children();
		row.find('.sln-checkbox label + input[type="hidden"]').remove();
		row.find('input,select,textarea').prop('disabled',false);
		row.hide().appendTo(ROW_WRAPPER_SELECTOR);
		row.find(DELETE_BUTTON_SELECTOR).show();
		fieldToRow(field, FIELD_KEY ,key);
		row.show();
		return row;
	}
			
	var getEditorKey = function(){
		
		return $(EDITOR_SELECTOR).data().key
	}
	
	var setEditorKey = function(key){
		
		return $(EDITOR_SELECTOR).data('key',key)
	}
			
	var getEditMode = function(){				
		return $(EDITOR_SELECTOR).data().mode
	}
	
	var setEditMode = function(mode){
		
		return $(EDITOR_SELECTOR).data('mode',mode)
	}
	
	var getAllKeys = function(){
		var ret = [];
		$(ROW_SELECTOR).each(function(i,v){
			ret.push($(v).data().index);
		})
		return ret;
	}
	
	var getNewKey = function(){
		var name = slugify($(LABEL_INPUT_SELECTOR).val());
		var keys = getAllKeys();
		var x = 0;
		while(keys.indexOf(name) !== -1){
			name = name +'_'+ x++;
		}
		
		return name;
	}
	
	var getKey = function($el){				
		return $el.parents(ROW_SELECTOR).data().index
	}
	
	var editField = function(el,key){
		var field = rowToField(FIELD_KEY,key);
		fieldToRow(field, EDITOR_KEY,key);					
		setEditMode(UPDATE_KEY);
		setEditorKey(key);
		$(EDITOR_BUTTON_SELECTOR).text(UPDATE_FIELD_BUTTON_TEXT)
		$(EDITOR_CLOSE_BUTTON_SELECTOR).show();
		$(ROW_SELECTOR).removeClass('selected');
		$(ROW_SELECTOR+'[data-index="'+key+'"]').addClass('selected');
	}
	
	var clearFieldEditor = function(){
		fieldToRow(getFieldDefault(),EDITOR_KEY);				
		setEditMode(NEW_KEY)
		setEditorKey(false);
		$(EDITOR_BUTTON_SELECTOR).text(ADD_FIELD_BUTTON_TEXT)
		$(EDITOR_CLOSE_BUTTON_SELECTOR).hide();
		$(ROW_SELECTOR).removeClass('selected');
	}
	
	var updateField  = function(){
		var field = rowToField(EDITOR_KEY);
		fieldToRow(field,FIELD_KEY,getEditorKey());
	}
	
	var addNewField = function(){
		var field = rowToField(EDITOR_KEY);
		if(!field.label){
			$(EDITOR_SELECTOR).addClass('invalid');
			isValidEditor = false;
			return;
		}
		newFieldToRow(field,getNewKey())
	};
	
	var toggleDefaultFieldType = function(type){		
		var defaultValueText = $(DEFAULT_VALUE_TEXT_COL_SELECTOR);
		var defaultValueCheckbox = $(DEFAULT_VALUE_CHECKBOX_COL_SELECTOR);
		if(type === 'checkbox'){
			defaultValueText.children('input').prop('disabled',true);
			defaultValueText.hide();
			defaultValueCheckbox.children('input').prop('disabled',false);
			defaultValueCheckbox.show();
		}else{
			defaultValueCheckbox.children('input').prop('disabled',true);
			defaultValueCheckbox.hide();
			defaultValueText.children('input').prop('disabled',false);
			defaultValueText.show();
		}
	}

	$('body').on('click',EDIT_BUTTON_SELECTOR,function(){
		var $el = $(this);
		var key = getKey($el);
		if( key !== getEditorKey() ){
			editField($el, key)
		}
	});
	
	$('body').on('click',DELETE_BUTTON_SELECTOR,function(){
		var $el = $(this);
		if(getEditMode() === UPDATE_KEY && getKey($el) === getEditorKey()){
			clearFieldEditor();	
		}
		$el.parents(ROW_SELECTOR).remove();
	});
	
	$(TYPE_SELECT_SELECTOR).change(function(){
		var val = $(this).val();
		var selectOptions = $(OPTIONS_TEXT_AREA_SELECTOR).parent();
		val === 'select' ? selectOptions.show() : selectOptions.hide();
		toggleDefaultFieldType(val);
	})
	
	$(EDITOR_BUTTON_SELECTOR).click(function(){
		if(getEditMode() === NEW_KEY){
			addNewField()
		}else{
			updateField()
		}
		clearFieldEditor();
	})	
	$(EDITOR_CLOSE_BUTTON_SELECTOR).click(function(){
		clearFieldEditor();	
	})
	
	$(LABEL_INPUT_SELECTOR).change(function(){
		if(!isValidEditor){
			var val = $(this).val();
			if(val){
				$(EDITOR_SELECTOR).removeClass('invalid');
				isValidEditor = true;
			}
			
		}
	})
	var sortable = Sortable.create($(ROW_WRAPPER_SELECTOR).get(0),{handle: GRIP_CELL_SELECTOR});
})(jQuery,sln_getFieldDefault)