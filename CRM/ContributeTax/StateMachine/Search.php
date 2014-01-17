<?php

//class CRM_ContributeTax_StateMachine_Search extends CRM_Contribute_StateMachine_Search {

// function __construct($controller, $action = CRM_Core_Action::NONE) {
//     parent::__construct($controller, $action);

//     $this->_pages = array();

//     $this->_pages['CRM_ContributeTax_Form_Search'] = NULL;
//     list($task, $result) = $this->taskName($controller, 'Search');
//     $this->_task = $task;

//     if (is_array($task)) {
//       foreach ($task as $t) {
//         $this->_pages[$t] = NULL;
//       }
//     }
//     else {
//       $this->_pages[$task] = NULL;
//     }
//     if ($result) {
//       $this->_pages['CRM_Contribute_Form_Task_Result'] = NULL;
//     }
//     $this->addSequentialPages($this->_pages, $action);
//   }
// }