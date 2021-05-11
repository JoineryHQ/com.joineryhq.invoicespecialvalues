CRM.$(function($) {
  $(document).ready(function(){
    var displayOnInvoices = $('#display_on_invoices');
    var displayOnInvoicesParent = displayOnInvoices.parents('tr');
    displayOnInvoicesParent.addClass('crm-custom-field-form-block-display_on_invoices display_on_invoices');
    displayOnInvoicesParent.insertAfter('.crm-custom-field-form-block-is_view');
  });
});
