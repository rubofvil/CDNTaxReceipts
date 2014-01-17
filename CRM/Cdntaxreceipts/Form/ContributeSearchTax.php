<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Cdntaxreceipts_Form_ContributeSearchTax extends CRM_Contribute_Form_Search {

  function buildQuickForm() {
    parent::buildQuickForm($this);
    CRM_ContributeTax_BAO_Query::buildSearchForm($this);
  }

  function postProcess() {
    if ($this->_done) {
      return;
    }

    $this->_done = TRUE;

    if (!empty($_POST)) {
      $this->_formValues = $this->controller->exportValues($this->_name);
    }

    $this->fixFormValues();

    // We don't show test records in summaries or dashboards
    if (empty($this->_formValues['contribution_test']) && $this->_force) {
      $this->_formValues["contribution_test"] = 0;
    }

    foreach (array(
      'contribution_amount_low', 'contribution_amount_high') as $f) {
      if (isset($this->_formValues[$f])) {
        $this->_formValues[$f] = CRM_Utils_Rule::cleanMoney($this->_formValues[$f]);
      }
    }

    $config = CRM_Core_Config::singleton();
    $tags = CRM_Utils_Array::value('contact_tags', $this->_formValues);
    if ($tags && !is_array($tags)) {
      unset($this->_formValues['contact_tags']);
      $this->_formValues['contact_tags'][$tags] = 1;
    }

    if ($tags && is_array($tags)) {
      unset($this->_formValues['contact_tags']);
      foreach($tags as $notImportant => $tagID) {
          $this->_formValues['contact_tags'][$tagID] = 1;
      }
    }


    if (!$config->groupTree) {
      $group = CRM_Utils_Array::value('group', $this->_formValues);
      if ($group && !is_array($group)) {
        unset($this->_formValues['group']);
        $this->_formValues['group'][$group] = 1;
      }

      if ($group && is_array($group)) {
        unset($this->_formValues['group']);
        foreach($group as $notImportant => $groupID) {
            $this->_formValues['group'][$groupID] = 1;
        }
      }

    }

    CRM_Core_BAO_CustomValue::fixFieldValueOfTypeMemo($this->_formValues);

    $this->_queryParams = CRM_Contact_BAO_Query::convertFormValues($this->_formValues);

    $this->set('formValues', $this->_formValues);
    $this->set('queryParams', $this->_queryParams);

    $buttonName = $this->controller->getButtonName();
    if ($buttonName == $this->_actionButtonName || $buttonName == $this->_printButtonName) {
      // check actionName and if next, then do not repeat a search, since we are going to the next page

      // hack, make sure we reset the task values
      $stateMachine = $this->controller->getStateMachine();
      $formName = $stateMachine->getTaskFormName();
      $this->controller->resetPage($formName);
      return;
    }


    $sortID = NULL;
    if ($this->get(CRM_Utils_Sort::SORT_ID)) {
      $sortID = CRM_Utils_Sort::sortIDValue($this->get(CRM_Utils_Sort::SORT_ID),
        $this->get(CRM_Utils_Sort::SORT_DIRECTION)
      );
    }

    $this->_queryParams = CRM_Contact_BAO_Query::convertFormValues($this->_formValues);
    // CRM_Core_Error::debug($this->_queryParams);
    // die;

    $selector = new CRM_ContributeTax_Selector_Search($this->_queryParams,
      $this->_action,
      NULL,
      $this->_single,
      $this->_limit,
      $this->_context
    );

    // $selector->_where[$grouping][] = " cdntaxreceipts_log_contributions.contribution_id IS NOT NULL";
    // $selector->_tables['cdntaxreceipts_log_contributions'] = $query->_whereTables['cdntaxreceipts_log_contributions'] = 1;
    // $selector->_tables['civicrm_contribution'] = $query->_whereTables['civicrm_contribution'] = 1;

    $selector->setKey($this->controller->_key);
    // CRM_Core_Error::debug($selector);
    // die;

    $prefix = NULL;
    if ($this->_context == 'basic' || $this->_context == 'user') {
      $prefix = $this->_prefix;
    }

    $controller = new CRM_Core_Selector_Controller($selector,
      $this->get(CRM_Utils_Pager::PAGE_ID),
      $sortID,
      CRM_Core_Action::VIEW,
      $this,
      CRM_Core_Selector_Controller::SESSION,
      $prefix
    );
    $controller->setEmbedded(TRUE);

    $query = &$selector->getQuery();



    if ($this->_context == 'user') {
      $query->setSkipPermission(TRUE);
    }
    $summary = &$query->summaryContribution($this->_context);
    // CRM_Core_Error::debug($summary);
    // die;
    $this->set('summary', $summary);
    $this->assign('contributionSummary', $summary);

    $controller->run();

  }
}
