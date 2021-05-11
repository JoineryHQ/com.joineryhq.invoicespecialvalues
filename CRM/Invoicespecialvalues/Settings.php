<?php

// phpcs:disable
use CRM_Invoicespecialvalues_ExtensionUtil as E;
// phpcs:enable

/**
 * Settings-related utility methods.
 *
 * This settings is copied from com.joineryhq.cdashtabs extension
 */
class CRM_Invoicespecialvalues_Settings {

  /**
   * Get settings for a given section, as defined by a type and an ID.
   *
   * @param int $id Entity id (custom_field_id)
   * @param string $type Entity type (custom_field)
   * @return array Json-decoded settings from optionValue for this section.
   */
  public static function getSettings($id, $type) {
    $settingName = "{$type}_{$id}";
    $result = \Civi\Api4\OptionValue::get()
      ->addWhere('option_group_id:name', '=', 'invoicespecialvalues')
      ->addWhere('name', '=', $settingName)
      ->execute();

    $resultValue = CRM_Utils_Array::value(0, $result, array());
    $settingJson = CRM_Utils_Array::value('value', $resultValue, '{}');
    return json_decode($settingJson, TRUE);
  }

  /**
   * Save value as json-encoded settings, for a given optionValue, as defined by type and ID.
   *
   * @param int $id Entity id (e.g., custom_field_id.)
   * @param array $settings Full list of all settings to save. This will NOT be merged with any existing settings.
   * @param string $type Entity type ('custom_field')
   *
   * @return void
   */
  public static function saveAllSettings($id, $settings, $type) {
    $settingName = "{$type}_{$id}";
    $result = \Civi\Api4\OptionValue::get()
      ->addWhere('option_group_id:name', '=', 'invoicespecialvalues')
      ->addWhere('name', '=', $settingName)
      ->execute()
      ->first();

    $createParams = array();

    if ($optionValueId = CRM_Utils_Array::value('id', $result)) {
      $createParams['id'] = $optionValueId;
    }
    else {
      $createParams['name'] = $settingName;
      $createParams['option_group_id'] = "invoicespecialvalues";
    }

    // Add custom_field_id to settings. Without this, optionValue.create api was failing
    // to save new settings with a message like "value already exists in the database"
    // if the values for this customField are the same as for some other customFields. So by
    // adding custom_field_id, we make it unique to this customField.
    $settings["{$type}_id"] = $id;
    $createParams['value'] = json_encode($settings);

    civicrm_api3('optionValue', 'create', $createParams);
  }

  /**
   * Get an array of saved settings-per-section, filtered per the given criteria.
   *
   * @param boolean $displayOnInvoices If given, filter only for settings-per-section where
   *    the setting value display_on_invoices matches the given value.
   * @param string $type Entity type ('custom_field')
   * @return array of data base of the display_on_invoices field that has a value of TRUE
   *
   */
  public static function getFilteredSettings($displayOnInvoices = NULL, $type) {
    $filteredSettings = [];
    // Get invoicespecialvalues optionGroup and all of its optionValues.
    $optionGroup = \Civi\Api4\OptionGroup::get()
      ->setCheckPermissions(FALSE)
      ->addWhere('name', '=', 'invoicespecialvalues')
      ->addChain('get_optionValue', \Civi\Api4\OptionValue::get()->addWhere('option_group_id', '=', '$id')->addOrderBy('weight', 'ASC'))
      ->execute()
      ->first();
    foreach ($optionGroup['get_optionValue'] as $optionValue) {
      $optionSettingJson = $optionValue['value'] ?? '{}';
      $optionSettings = json_decode($optionSettingJson, TRUE);
      if (
        $optionSettings["{$type}_id"]
        && ($displayOnInvoices === NULL || ($optionSettings['display_on_invoices'] ?? 0) == intval($displayOnInvoices))
      ) {
        $filteredSettings[] = $optionSettings;
      }
    }
    return $filteredSettings;
  }

  /**
   * Get custom field label with values base on the custom field id which has display_on_invoices..
   * and entityIds which list of mix contribution and participants id
   *
   * @param int $customFieldId
   * @param array entityIds list of mix contribution and participants id
   * @return FALSE or array of custom field label and values
   *
   */
  public static function getCustomFieldValues($customFieldId, $entityIds) {
    // Get custom field and custom group data
    $customField = civicrm_api3('CustomField', 'get', [
      'sequential' => 1,
      'id' => $customFieldId,
      'api.CustomGroup.get' => [
        'id' => '$value.custom_group_id',
      ],
    ]);

    // Init returnData
    $returnData = [];
    // Get entity type (Contribution or Participant)
    $entityType = $customField['values'][0]['api.CustomGroup.get']['values'][0]['extends'];

    foreach ($entityIds as $entityId) {
      // Get the value of the custom field using entity id
      $customFieldValue = civicrm_api3($entityType, 'get', [
        'sequential' => 1,
        'return' => ["custom_{$customFieldId}"],
        'id' => $entityId,
      ]);

      // If custom_$customFieldId has value, add it on the returnData as values array
      if ($customFieldValue && $customFieldValue['values']['0']["custom_{$customFieldId}"]) {
        $returnData['values'][] = $customFieldValue['values']['0']["custom_{$customFieldId}"];
      }
    }

    // If return data have values, add label and return all data,
    // if not return false
    if ($returnData) {
      $returnData['label'] = $customField['values'][0]['label'];
      return $returnData;
    }
    else {
      return FALSE;
    }
  }

}
