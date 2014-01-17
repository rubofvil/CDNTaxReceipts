<?php

class CdnTaxReceipts_Installer{
	static function install(){
		//Generate variable to know if we want sent a email when the contact have mail
		$params = array();
		$params[] = array(
		  'version' => 3,
		  'sequential' => 1,
		  'send_mail_taxreceipt' => 1,
		);
		$result = self::executeAPI($params, 'Setting', 'create');
		//Generate activity type
		$params = array();
		$params[] = array(
		  'version' => 3,
		  'sequential' => 1,
		  'value' => 7,
		  'label' => 'Tax receipt',
		  'name' => 'tax_receipt',
		  'weight' => 7,
		  'is_active' => 1,
		  'is_reseved' => 1
		);
		$result = self::executeAPI($params, 'ActivityType', 'create');
		//Custom group to the activity type
		$params = array();
		$params[] = array(
			'version' => 3,
			'name' => 'Tax_receipt',
			'title' => 'Tax receipt',
			'extends' => 'Activity',
			'extends_entity_column_value' => array('7'),
			'style' => 'Inline',
			'collapse_display' => '0',
			'help_pre' => '',
			'help_post' => '',
			'weight' => '1',
			'is_active' => '1',					
			'is_multiple' => '0',
			'collapse_adv_display' => '0',			
		);
		$custom_group_ids = self::executeAPI($params,'CustomGroup', 'create');


		$option_group		= array();
		$option_group[0]	= array('version' => 3, 'name' => 'tax_year', 'label' => 'Tax year',		'description' => 'Tax year',	'is_reserved' => 1, 'is_active' => 1, );
		$option_group[1]	= array('version' => 3, 'name' => 'tax_type', 'label' => 'Type of tax',		'description' => 'Type of tax',	'is_reserved' => 1, 'is_active' => 1, );
		$option_group_ids = self::executeAPI($option_group,'OptionGroup', 'create');

		$option_value		= array();
		$option_value[]	= array('version' => 3, 'id' => '', 'name' => '2012','label' => '2012', 'value' => '2012',	'weight' => 1,	'is_reserved' => 0, 'is_active' => 1, 'option_group_id' => $option_group_ids[0]);
		$option_value[]	= array('version' => 3, 'id' => '', 'name' => '2013','label' => '2013', 'value' => '2013',	'weight' => 2,	'is_reserved' => 0, 'is_active' => 1, 'option_group_id' => $option_group_ids[0]);
		$option_value[]	= array('version' => 3, 'id' => '', 'name' => '2014','label' => '2014', 'value' => '2014',	'weight' => 3,	'is_reserved' => 0, 'is_active' => 1, 'option_group_id' => $option_group_ids[0]);
		$option_value[]	= array('version' => 3, 'id' => '', 'name' => '2015','label' => '2015', 'value' => '2015',	'weight' => 4,	'is_reserved' => 0, 'is_active' => 1, 'option_group_id' => $option_group_ids[0]);
		$option_value[]	= array('version' => 3, 'id' => '', 'name' => '2016','label' => '2016', 'value' => '2016',	'weight' => 5,	'is_reserved' => 0, 'is_active' => 1, 'option_group_id' => $option_group_ids[0]);

		$option_value[]	= array('version' => 3, 'id' => '', 'name' => 'single','label' => 'Single', 'value' => 'single',	'weight' => 1,	'is_reserved' => 0, 'is_active' => 1, 'option_group_id' => $option_group_ids[1]);
		$option_value[]	= array('version' => 3, 'id' => '', 'name' => 'grouped','label' => 'Grouped', 'value' => 'grouped',	'weight' => 2,	'is_reserved' => 0, 'is_active' => 1, 'option_group_id' => $option_group_ids[1]);
		
		$option_value_ids = self::executeAPI($option_value, 'OptionValue', 'Create');

		$custom_field = array();
		$custom_field[]	= array('version' => 3, 'weight' => '1', 'custom_group_id' => $custom_group_ids[0], 'label' => 'Year of tax', 'data_type' => 'String', 'html_type' => 'Select', 'is_active' => '1', 'text_length' => '255', 'option_group_id' => $option_group_ids[0], 'is_searchable' => '1', 'is_required' => '0' );
		$custom_field[]	= array('version' => 3, 'weight' => '1', 'custom_group_id' => $custom_group_ids[0], 'label' => 'Type of Tax', 'data_type' => 'String', 'html_type' => 'Select', 'is_active' => '1', 'text_length' => '255', 'option_group_id' => $option_group_ids[1], 'is_searchable' => '1', 'is_required' => '0' );
		$custom_field_ids = self::executeAPI($custom_field, 'CustomField', 'Create');
	}		



	private static function executeAPI($array_items, $item_type, $action_type){
		$results = array();

		for ($i=0; $i<sizeof($array_items); $i++) {
			$result = civicrm_api($item_type, $action_type, $array_items[$i]);
			if($result['is_error'] == 1) {
				$statusMsg = "Error creating $item_type: " . $result['error_message'] ;
				CRM_Core_Session::setStatus( $statusMsg, false );
				//CRM_Core_Error::fatal();
				break;
				//return 1;
			}
			else {
				$value = array_pop($result['values']);
				$results[$i] = $value['id'];
			}
		}

		return $results;
	}

}