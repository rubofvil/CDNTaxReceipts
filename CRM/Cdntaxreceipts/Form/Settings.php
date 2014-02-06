<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Cdntaxreceipts_Form_Settings extends CRM_Core_Form {

  CONST SETTINGS = 'CDNTaxReceipts';

  function buildQuickForm() {

    CRM_Utils_System::setTitle(ts('Configure CDN Tax Receipts'));    
    $this->processSystemOptions('build');
    $this->processEmailOptions('build');
    $this->processTemplateOptions('build');

    $arr1 = $this->processTemplateOptions('defaults');
    $arr3 = $this->processSystemOptions('defaults');
    $arr4 = $this->processEmailOptions('defaults');
    $defaults = array_merge($arr3, $arr4, $arr1);
    $this->setDefaults($defaults);

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));

    parent::buildQuickForm();
  }  

  function processSystemOptions($mode) {
    if ( $mode == 'build' ) {
      $this->addElement('checkbox', 'issue_inkind', ts('Setup in-kind receipts?'));

      $yesno_options = array();
      $yesno_options[] = $this->createElement('radio', NULL, NULL, 'Yes', 1);
      $yesno_options[] = $this->createElement('radio', NULL, NULL, 'No', 0);
      $this->addGroup($yesno_options, 'enable_email', ts('Send receipts by email?'));
      $this->addRule('enable_email', 'Enable or disable email receipts', 'required');
    }
    else if ( $mode == 'defaults' ) {
      $defaults = array(
        'issue_inkind' => 0,
        'enable_email' => CRM_Core_BAO_Setting::getItem(self::SETTINGS, 'enable_email', NULL, 0),
      );
      return $defaults;
    }
    else if ( $mode == 'post' ) {
      $values = $this->exportValues();
      CRM_Core_BAO_Setting::setItem($values['enable_email'], self::SETTINGS, 'enable_email');

      if ( $values['issue_inkind'] == 1 ) {
        cdntaxreceipts_configure_inkind_fields();
      }
    }
  }

  function processEmailOptions($mode) {
    if ( $mode == 'build' ) {
      $this->add('text', 'email_subject', ts('Email Subject'));
      $this->add('text', 'email_from', ts('Email From'));
      $this->add('text', 'email_archive', ts('Archive Email'));
      $this->addElement('textarea', 'email_message', ts('Email Message'));

      $this->addRule('email_subject', 'Enter email subject', 'required');
      $this->addRule('email_from', 'Enter email from address', 'required');
      $this->addRule('email_archive', 'Enter email archive address', 'required');
      $this->addRule('email_message', 'Enter email message', 'required');
    }
    else if ( $mode == 'defaults' ) {
      $subject = ts('Your Tax Receipt');
      $message = ts('Attached please find your official tax receipt for income tax purposes.');
      $defaults = array(
        'email_subject' => CRM_Core_BAO_Setting::getItem(self::SETTINGS, 'email_subject', NULL, $subject),
        'email_from' => CRM_Core_BAO_Setting::getItem(self::SETTINGS, 'email_from'),
        'email_archive' => CRM_Core_BAO_Setting::getItem(self::SETTINGS, 'email_archive'),
        'email_message' => CRM_Core_BAO_Setting::getItem(self::SETTINGS, 'email_message', NULL, $message),
      );
      return $defaults;
    }
    else if ( $mode == 'post' ) {
      $values = $this->exportValues();
      CRM_Core_BAO_Setting::setItem($values['email_subject'], self::SETTINGS, 'email_subject');
      CRM_Core_BAO_Setting::setItem($values['email_from'], self::SETTINGS, 'email_from');
      CRM_Core_BAO_Setting::setItem($values['email_archive'], self::SETTINGS, 'email_archive');
      CRM_Core_BAO_Setting::setItem($values['email_message'], self::SETTINGS, 'email_message');
    }
  }

  function processTemplateOptions($mode) {
    if ( $mode == 'build' ) {
      $this->add('select', 'original_template', ts('Original template'),
        array('' => ts('- select -')) + CRM_Core_BAO_MessageTemplates::getMessageTemplates(FALSE)
      );

      $this->add('select', 'copy_template', ts('Copy template'),
        array('' => ts('- select -')) + CRM_Core_BAO_MessageTemplates::getMessageTemplates(FALSE)
      );

      $this->add('select', 'pdf_format', ts('PDF format'),
        array('' => ts('- select -')) + CRM_Core_BAO_PdfFormat::getList(TRUE)
      );      
    }
    else if ( $mode == 'defaults' ) {
      $subject = ts('Your Tax Receipt');
      $message = ts('Attached please find your official tax receipt for income tax purposes.');
      $defaults = array(
        'original_template' => CRM_Core_BAO_Setting::getItem(self::SETTINGS, 'original_template', NULL, $subject),
        'copy_template' => CRM_Core_BAO_Setting::getItem(self::SETTINGS, 'copy_template'),
        'pdf_format' => CRM_Core_BAO_Setting::getItem(self::SETTINGS, 'pdf_format'),
      );
      return $defaults;
    }
    else if ( $mode == 'post' ) {
      $values = $this->exportValues();                
      CRM_Core_BAO_Setting::setItem($values['original_template'], self::SETTINGS, 'original_template');
      CRM_Core_BAO_Setting::setItem($values['copy_template'], self::SETTINGS, 'copy_template');
      CRM_Core_BAO_Setting::setItem($values['pdf_format'], self::SETTINGS, 'pdf_format');      
    }
  }


  function postProcess() {
    parent::postProcess();    
    $this->processSystemOptions('post');
    $this->processEmailOptions('post');
    $this->processTemplateOptions('post');    

    $statusMsg = ts('Your settings have been saved.');
    CRM_Core_Session::setStatus( $statusMsg, '', 'success' );
  }
}
