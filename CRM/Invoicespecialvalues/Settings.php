<?php

// phpcs:disable
use CRM_Invoicespecialvalues_ExtensionUtil as E;
// phpcs:enable

/**
 * Settings-related utility methods.
 *
 */
class CRM_Invoicespecialvalues_Settings {

  /**
   * Get settings for a given section, as defined by a type and an ID.
   *
   * @param int $id Entity id (e.g., profile id, natived dashboard component id, etc.)
   * @param string $type Entity type ('uf_group' or 'native')
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
   * @param int $id Entity id (e.g., profile id, natived dashboard component id, etc.)
   * @param array $settings Full list of all settings to save. This will NOT be merged with any existing settings.
   * @param string $type Entity type ('uf_group' or 'native')
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

    // Add uf_group_id to settings. Without this, optionValue.create api was failing
    // to save new settings with a message like "value already exists in the database"
    // if the values for this ufGroup are the same as for some other ufGroup. So by
    // adding uf_group_id, we make it unique to this ufGroup.
    $settings["{$type}_id"] = $id;
    $createParams['value'] = json_encode($settings);

    civicrm_api3('optionValue', 'create', $createParams);
  }

  /**
   * Get an array of saved settings-per-section, filtered per the given criteria.
   *
   * @param boolean $isCdash If given, filter only for settings-per-section where
   *    the setting value is_cdash matches the given value.
   * @param string $type Entity type ('uf_group' or 'native')
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

}
