CDNTaxReceipts
==============

Canadian Tax Receipts extension for CiviCRM

To set up the extension (instructions for 4.3.5):
------------

1. Make sure your CiviCRM Extensions directory is set (Administer > System Settings > Directories).
2. Make sure your CiviCRM Extensions Resource URL is set (Administer > System Settings > Resource URLs).
3. Unpack the code
    - cd extensions directory
    - git clone https://github.com/jake-mw/CDNTaxReceipts.git org.civicrm.ixiam.cdntaxreceipts
4. Enable the extension at Administer > System Settings > Manage Extensions
5. Configure CDN Tax Receipts at Administer > CiviContribute > CDN Tax Receipts. (Take note of the dimensions for each of the image parameters. Correct sizing is important. You might need to try a few times to get it right.)

Next: review and the permissions - the modules has added a new CiviCRM permission.

NOTE: if upgrading site that uses existing Drupal CiviCRM CDN Tax Receipts module - you need to:
1. disable the CiviCRM CDN Tax Receipts module on the admin/modules page.
2. remove the tcpdf/ from your /libraries

Now you should be able to use the module.

Operations
------------
**Individual or Single Tax Receipts**

These are receipts issued as one receipt to one contribution.
- To issue an individual receipt, pull up the contact record, go to 'contributions' tab, view the contribution, and click the "Tax Receipt" button. Follow on-screen instructions from there.
Single receipts can be issued in bulk for multiple contributions. This process issues one receipt per contribution.
- To issue bulk-issue receipts, go to Contributions > Find Contributions, run a search, select one or more search results, and select "Issue Tax Receipts" in the actions drop-down. Follow on-screen instructions from there.

**Annual Tax Receipts**

These are receipts that collect all outstanding contributions for the year into one receipt. If some contributions have already been sent a receipt they will not be included in the total. 
Since there are multiple contributions on one receipt there are some differences in the template. In-kind fields are not shown, contribution type and source are also not shown since the collected contributions over the year could be of multiple types and from multiple sources.

- To issue Annual Tax Receipts, go to Search > Find Contacts (or Search > Advanced Search), run a search for contacts, select one or more contacts, and select "Issue Annual Tax Receipts" in the actions drop-down. Follow on-screen instructions from there.

The extension also enables two report templates, which can be used to see a list of receipts issued and receipts outstanding.

- Tax Receipts - Receipts Issued
- Tax Receipts - Receipts Not Issued


hook_cdntaxreceipts_eligible()
------------

You may be in a situation where certain Contributions are eligible for tax receipts and others are not (e.g. donations are receiptable, but only for individuals, and event fees are not receiptable). If this is the case, there is a PHP hook hook_cdntaxreceipts_eligible($contribution) that can be used for complex eligibility criteria. Hook implementations should return one of TRUE or FALSE, wrapped in an array.

    // Example hook implementation:
    //  Contributions have a custom yes/no field called "receiptable". Issue tax receipt
    //  on any contribution where receiptable = Yes.
    function mymodule_cdntaxreceipts_eligible( $contribution ) {

      // load custom field
      $query = "
      SELECT receiptable_119
      FROM civicrm_value_tax_receipt_23
      WHERE entity_id = %1";

      $params = array(1 => array($contribution->id, 'Integer'));
      $field = CRM_Core_DAO::singleValueQuery($query, $params);

      if ( $field == 1 ) {
        return array(TRUE);
      }
      else {
        return array(FALSE);
      }

    }

By default, a contribution is eligible for tax receipting if it is completed, and if its Financial Type is deductible.

Disclaimer
------------

This extension has been developed in consultation with a number of non-profits and with the help of a senior accountant. The maintainers have made every reasonable effort to ensure compliance with CRA guidelines and best practices. However, it is the reponsibility of each organization using this extension to do their own due diligence in ensuring compliance with CRA guidelines and with their organizational policies.

DOMPDF Configuration
------------
Edit the next vars in dompdf_config.inc.php

def("DOMPDF_ENABLE_REMOTE", true);
def('DEBUGKEEPTEMP', true);