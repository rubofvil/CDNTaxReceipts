<?php

class CRM_ContributeTax_BAO_Query extends CRM_Contribute_BAO_Query{

	static function whereClauseSingle(&$values, &$query) {

		list($name, $op, $value, $grouping, $wildcard) = $values;
		CRM_Core_Error::debug($name);
    die;

    $fields = self::getFields();

    if (!empty($value) && !is_array($value)) {
      $quoteValue = "\"$value\"";
    }

    $strtolower = function_exists('mb_strtolower') ? 'mb_strtolower' : 'strtolower';
    CRM_Core_Error::debug($name);
    die;
		switch ($name) {
			case 'contribution_printed_tax_receipt':
				CRM_Core_Error::debug("entramos");
				die;
        $query->_where[$grouping][] = " cdntaxreceipts_log_contributions.contribution_id IS NOT NULL";
        $query->_tables['cdntaxreceipts_log_contributions'] = $query->_whereTables['cdntaxreceipts_log_contributions'] = 1;
        $query->_tables['civicrm_contribution'] = $query->_whereTables['civicrm_contribution'] = 1;
        return;
		}
		parent::whereClauseSingle(&$values, &$query);

	}
	static function from($name, $mode, $side) {
		CRM_Core_Error::debug("entramos");
		die;
		$from = parent::from($name, $mode, $side);
		$from = NULL;
    switch ($name) {
  	  case 'cdntaxreceipts_log_contributions':
      $from .= " $side JOIN cdntaxreceipts_log_contributions ON civicrm_contribution.id = cdntaxreceipts_log_contributions.contribution_id ";
      break;
    }
   	return $from;
	}
	static function buildSearchForm(&$form) {
		$form->addYesNo('contribution_printed_tax_receipt', ts('Printed the tax receipt?'));
		parent::buildSearchForm(&$form);
	}

}