<div class="crm-block crm-form-block crm-miscellaneous-form-block">

<h3>System Options</h3>

  <table class="form-layout">
    <tbody>
      <tr>
        <td class="label">{$form.issue_inkind.label}</td>
        <td class="content">{$form.issue_inkind.html}
          <p class="description">Checking this box will set up the fields required to generate in-kind tax receipts. Unchecking the box will not disable in-kind receipts: you will need to do that manually, by disabling the In-kind contribution type or making it non-deductible in the CiviCRM administration pages.</p></td>
      </tr>
      <tr>
        <td class="label">{$form.enable_email.label}</td>
        <td class="content">{$form.enable_email.html}
          <p class="description">If enabled, tax receipts will be sent via email to donors who have an email address on file.</p></td>
      </tr>
    </tbody>
  </table>

<h3>Email Message</h3>

  <table class="form-layout">
    <tbody>
      <tr>
        <td class="label">{$form.email_subject.label}</td>
        <td class="content">{$form.email_subject.html}
          <p class="description">Subject of the Email to accompany your Tax Receipt. The receipt number will be appended.</p></td>
      </tr>
      <tr>
        <td class="label">{$form.email_from.label}</td>
        <td class="content">{$form.email_from.html}
          <p class="description">Address you would like to Email the Tax Receipt from.</p></td>
      </tr>
      <tr>
        <td class="label">{$form.email_archive.label}</td>
        <td class="content">{$form.email_archive.html}
          <p class="description">Address you would like to Send a copy of the Email containing the Tax Receipt to. This is useful to create an archive.</p></td>
      </tr>
      <tr>
        <td class="label">{$form.email_message.label}</td>
        <td class="content">{$form.email_message.html}
          <p class="description">Text in the Email to accompany your Tax Receipt.</p></td>
      </tr>
    </tbody>
  </table>

<h3>Templates</h3>

  <table class="form-layout">
    <tbody>
      <tr>
        <td class="label">{$form.original_template.label}</td>
        <td class="content">{$form.original_template.html}
          <p class="description">{ts}Original template to generate the pdf's{/ts}</p></td>
      </tr>     
      <tr>
        <td class="label">{$form.copy_template.label}</td>
        <td class="content">{$form.copy_template.html}
          <p class="description">{ts}Copy template to generate the pdf's{/ts}</p></td>
      </tr>
      <tr>
        <td class="label">{$form.pdf_format.label}</td>
        <td class="content">{$form.pdf_format.html}
          <p class="description">{ts}PDF format{/ts}</p></td>
      </tr>           
    </tbody>
  </table>



<div class="status message"><strong>Tip:</strong> After you fill out this form and save your Configuration, create a fake Donation in CiviCRM and issue a Tax Receipt for it to check the graphics/layout of the Tax Receipt that is generated. If necessary - rework your graphics and come back to this Form to upload the new version(s).</div>

{* FOOTER *}
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>

</div>
