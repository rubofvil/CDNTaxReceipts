{* Confirmation of tax receipts  *}
<div class="crm-block crm-form-block crm-contact-task-delete-form-block">
<div class="messages status no-popup">
  <div class="icon inform-icon"></div>
  {ts}You have selected <strong>{$totalSelectedContributions}</strong> contributions. Of these, <strong>{$receiptTotal}</strong> are eligible to receive tax receipts.{/ts}
</div>
  <table>
    <thead>
      {ts}<th>Tax Receipt Status</th>
      <th>Total</th>
      <th>Email</th>
      <th>Print</th>{/ts}
    </thead>
    <tr>
      <td>{ts}Not yet receipted{/ts}</td>
      <td>{$originalTotal}</td>
      <td>{$receiptCount.original.email}</td>
      <td>{$receiptCount.original.print}</td>
    </tr>
    <tr>
      <td>{ts}Already receipted{/ts}</td>
      <td>{$duplicateTotal}</td>
      <td>{$receiptCount.duplicate.email}</td>
      <td>{$receiptCount.duplicate.print}</td>
    </tr>
  </table>
  <p>{$form.receipt_option.original_only.html}<br />
     {$form.receipt_option.include_duplicates.html}</p>
  {ts}<p>Clicking 'Issue Tax Receipts' will issue the selected tax receipts.
    <strong>This action cannot be undone.</strong> Tax receipts will be logged for auditing purposes,
    and a copy of each receipt will be submitted to the tax receipt archive.
  <ul>
  <li>Email receipts will be emailed directly to the contributor.</li>
  <li>Print receipts will be compiled into a file for download.  Please print and mail any receipts in this file.</li>
  </ul></p>{/ts}
  <p>{$form.is_preview.html} {$form.is_preview.label}{ts} (Generates receipts marked 'preview', but does not issue the receipts.  No logging or emails sent.){/ts}</p>
  <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl"}</div>
</div>
