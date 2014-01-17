<?php

class CRM_Cdntaxreceipts_Search_ContactContributionTax implements CRM_Contact_Form_Search_Interface {

  protected $_formValues;

  function __construct(&$formValues) {
    $this->_formValues = $formValues;

    /**
     * Define the columns for search result rows
     */
    $this->_columns = array(
      ts('Contact Id') => 'contact_id',
      ts('Name') => 'sort_name',
      ts('Donation Count') => 'donation_count',
      ts('Donation Amount') => 'donation_amount',
    );
  }

  function buildForm(&$form) {

    /**
     * You can define a custom title for the search form
     */
    $this->setTitle('Find Contacts with tax receipts generated');

    /**
     * Define the search form fields here
     */    

    $years = array('' => ts('- select -'), "2012" => 2012, "2013" => 2013, "2014" => 2014);
    $form->add('select',
      'year',
      ts('Year'),
      $years, TRUE
    );
  }

  /**
   * Define the smarty template used to layout the search form and results listings.
   */
  function templateFile() {
    return 'CRM/Cdntaxreceipts/Search/ContactContributionTax.tpl';
  }

  /**
   * Construct the search query
   */
  function all($offset = 0, $rowcount = 0, $sort = NULL,
    $includeContactIDs = FALSE, $justIDs = FALSE
  ) {

    // SELECT clause must include contact_id as an alias for civicrm_contact.id
    if ($justIDs) {
      $select = "contact_a.id as contact_id";
    }
    else {
      $select = "
DISTINCT contact_a.id as contact_id,
contact_a.sort_name as sort_name,
sum(contrib.total_amount) AS donation_amount,
count(contrib.id) AS donation_count
";
    }
    $from = $this->from();

    $where = $this->where($includeContactIDs);

    $having = $this->having();
    if ($having) {
      $having = " HAVING $having ";
    }

    $sql = "
SELECT $select
FROM   $from
WHERE  $where
GROUP BY contact_a.id
$having
";
    //for only contact ids ignore order.
    if (!$justIDs) {
      // Define ORDER BY for query in $sort, with default value
      if (!empty($sort)) {
        if (is_string($sort)) {
          $sql .= " ORDER BY $sort ";
        }
        else {
          $sql .= " ORDER BY " . trim($sort->orderBy());
        }
      }
      else {
        $sql .= "ORDER BY donation_amount desc";
      }
    }

    if ($rowcount > 0 && $offset >= 0) {
      $sql .= " LIMIT $offset, $rowcount ";
    }
    return $sql;
  }

  function from() {
  $from = "";
  $from .= "
    civicrm_contact AS contact_a
    INNER JOIN civicrm_contribution AS contrib
    ON contact_a.id = contrib.contact_id
    RIGHT JOIN cdntaxreceipts_log_contributions AS contax
    ON contax.contribution_id = contrib.id
  ";

  return $from;
  }

  /*
      * WHERE clause is an array built from any required JOINS plus conditional filters based on search criteria field values
      *
      */
  function where($includeContactIDs = FALSE) {
    $clauses = array();

    $clauses[] = "contrib.contact_id = contact_a.id";
    $clauses[] = "contrib.is_test = 0";
    




    if ($this->_formValues["year"]) {
      $year = $this->_formValues["year"];
      $clauses[] = "contrib.receive_date >= $year" . "0101";
      $clauses[] = "contrib.receive_date <= $year" . "1231";
    }


    
    



    if ($includeContactIDs) {
      $contactIDs = array();
      foreach ($this->_formValues as $id => $value) {
        if ($value &&
          substr($id, 0, CRM_Core_Form::CB_PREFIX_LEN) == CRM_Core_Form::CB_PREFIX
        ) {
          $contactIDs[] = substr($id, CRM_Core_Form::CB_PREFIX_LEN);
        }
      }

      if (!empty($contactIDs)) {
        $contactIDs = implode(', ', $contactIDs);
        $clauses[] = "contact_a.id IN ( $contactIDs )";
      }
    }


    return implode(' AND ', $clauses);
  }

  function having($includeContactIDs = FALSE) {
    $clauses = array();
    $min = CRM_Utils_Array::value('min_amount', $this->_formValues);
    if ($min) {
      $min = CRM_Utils_Rule::cleanMoney($min);
      $clauses[] = "sum(contrib.total_amount) >= $min";
    }

    $max = CRM_Utils_Array::value('max_amount', $this->_formValues);
    if ($max) {
      $max = CRM_Utils_Rule::cleanMoney($max);
      $clauses[] = "sum(contrib.total_amount) <= $max";
    }

    return implode(' AND ', $clauses);
  }

  /*
     * Functions below generally don't need to be modified
     */
  function count() {
    $sql = $this->all();    
    $dao = CRM_Core_DAO::executeQuery($sql,
      CRM_Core_DAO::$_nullArray
    );
    return $dao->N;
  }

  function contactIDs($offset = 0, $rowcount = 0, $sort = NULL) {
    return $this->all($offset, $rowcount, $sort, FALSE, TRUE);
  }

  function &columns() {
    return $this->_columns;
  }

  function setTitle($title) {
    if ($title) {
      CRM_Utils_System::setTitle($title);
    }
    else {
      CRM_Utils_System::setTitle(ts('Search'));
    }
  }

  function summary() {
    return NULL;
  }
}

