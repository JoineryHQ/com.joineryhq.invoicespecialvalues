<?php
use CRM_Invoicespecialvalues_ExtensionUtil as E;

/**
 * Invoicespecialvalues.Get API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_invoicespecialvalues_Get_spec(&$spec) {
  $spec['id']['api.required'] = 1;
}

/**
 * Invoicespecialvalues.Get API
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 *
 * @throws API_Exception
 */
function civicrm_api3_invoicespecialvalues_Get($params) {
  // Validate id if exist, if not, throw error
  // id = contribution_id
  if (!array_key_exists('id', $params)) {
    throw new API_Exception('id parameter is required', 'id');
  }

  // Check if id is integer, throw error if not
  if ( filter_var($params['id'], FILTER_VALIDATE_INT) === false ) {
    throw new API_Exception('id should be integer', 'id');
  }

  // Initialize return value
  $returnValues = [];

  // Assign id as contributionId
  $contributionId = $params['id'];

  // Get contribution using contributionId
  $contribution = civicrm_api3('Contribution', 'get', [
    'sequential' => 1,
    'return' => ["receive_date"],
    'id' => $contributionId,
  ]);

  // Get the short date format in Date Formats settings.
  $dateFormat = Civi::Settings()->get('dateformatshortdate');

  // Get the received date of the contribution and assign it as a return value
  $returnValues[0]['receive_date'] = CRM_Utils_Date::customFormat($contribution['values'][0]['receive_date'], $dateFormat);

  // Get participat payment using contributionId
  $participantPayments = civicrm_api3('ParticipantPayment', 'get', [
    'sequential' => 1,
    'contribution_id' => $contributionId,
  ]);

  // Store contribution id and participant id to check if there is a display on invoices field
  $displayOnInvoicesIds[] = $contributionId;

  // Loop participant payment data
  foreach ($participantPayments['values'] as $participantPayment) {
    // Get participant data using id as participantPayment['participant_id']
    $participants = civicrm_api3('Participant', 'get', [
      'sequential' => 1,
      'id' => $participantPayment['participant_id'],
    ]);

    // Get custom field value  of the participant payment and assign it as a return value
    // custom_8 custom field is static for now
    $returnValues[0]['custom_field_value'] = $participants['values'][0]['custom_143'];
    $returnValues[0]['participants'][$participants['values'][0]['participant_id']]['name'] = $participants['values'][0]['display_name'];
    $returnValues[0]['participants'][$participants['values'][0]['participant_id']]['role'] = $participants['values'][0]['participant_role'];

    // Get participant data using registered_by_id as participantPayment['participant_id']
    $participantsRegisteredBy = civicrm_api3('Participant', 'get', [
      'sequential' => 1,
      'registered_by_id' => $participantPayment['participant_id'],
    ]);

    $displayOnInvoicesIds[] = $participantPayment['participant_id'];

    // Get all participantsRegisteredBy display_name and assign it as a return value
    foreach ($participantsRegisteredBy['values'] as $participantRegisteredBy) {
      $returnValues[0]['participants'][$participantRegisteredBy['participant_id']]['name'] = $participantRegisteredBy['display_name'];
      $returnValues[0]['participants'][$participantRegisteredBy['participant_id']]['role'] = $participantRegisteredBy['participant_role'];
    }
  }

  // Get Payment data using contributionId
  $payments = civicrm_api3('Payment', 'get', [
    'sequential' => 1,
    'contribution_id' => $contributionId,
  ]);

  // Get Payments payments_received date and amount and assign it as a return value
  foreach ($payments['values'] as $key => $payment) {
    $returnValues[0]['payments_received'][$key]['date'] = CRM_Utils_Date::customFormat($payment['trxn_date'], $dateFormat);
    $returnValues[0]['payments_received'][$key]['amount'] = $payment['total_amount'];
  }

  // Get payments transaction that we can use in the invoice
  $paymentInfo = CRM_Contribute_BAO_Contribution::getPaymentInfo($contributionId, 'contribution', TRUE);
  if ($paymentInfo['transaction']) {
    $returnValues[0]['transaction'] = $paymentInfo['transaction'];
  }

  // Get all display on invoices custom field and init customFieldData
  $displayOnInvoices = CRM_Invoicespecialvalues_Settings::getFilteredSettings(TRUE, 'custom_field');
  $customFieldData = [];

  // If there is display on invoices custom field, foreach data
  if ($displayOnInvoices) {
    foreach ($displayOnInvoices as $field) {
      $customFieldId = $field['custom_field_id'];
      $customFieldLabelAndValues = CRM_Invoicespecialvalues_Settings::getCustomFieldValues($customFieldId, $displayOnInvoicesIds);
      // If there is a returned data on the getCustomFieldValues add it on customFieldData
      if ($customFieldLabelAndValues) {
        $customFieldData['html'] .= $customFieldLabelAndValues['html'];
        $customFieldData['array']["custom_{$customFieldId}"] = $customFieldLabelAndValues["custom_{$customFieldId}"];
      }
    }
  }

  // If customFieldData is not empty, return as custom_fields_html
  if ($customFieldData) {
    // preg_replace to remove unnecesarry line break
    $returnValues[0]['custom_fields_html'] = preg_replace('/\r|\n/', '', $customFieldData['html']);
    $returnValues[0]['custom_fields'] = $customFieldData['array'];
  }

  return civicrm_api3_create_success($returnValues, $params, 'Invoicespecialvalues', 'Get');
}
