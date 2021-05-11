<?php

require_once 'invoicespecialvalues.civix.php';
// phpcs:disable
use CRM_Invoicespecialvalues_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function invoicespecialvalues_civicrm_config(&$config) {
  _invoicespecialvalues_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function invoicespecialvalues_civicrm_xmlMenu(&$files) {
  _invoicespecialvalues_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function invoicespecialvalues_civicrm_install() {
  _invoicespecialvalues_civix_civicrm_install();
  // Create the option group where we store the data of the display_on_invoices..
  // field when extension is intalled
  $results = \Civi\Api4\OptionGroup::create()
    ->setCheckPermissions(FALSE)
    ->addValue('name', 'invoicespecialvalues')
    ->addValue('title', 'Invoice Special Values Extension Options')
    ->addValue('is_active', TRUE)
    ->addValue('is_locked', TRUE)
    ->addValue('is_reserved', TRUE)
    ->execute();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function invoicespecialvalues_civicrm_postInstall() {
  _invoicespecialvalues_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function invoicespecialvalues_civicrm_uninstall() {
  _invoicespecialvalues_civix_civicrm_uninstall();
  // Delete the option group if extension is uninstall
  $results = \Civi\Api4\OptionGroup::delete()
    ->setCheckPermissions(FALSE)
    ->addWhere('name', '=', 'invoicespecialvalues')
    ->execute();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function invoicespecialvalues_civicrm_enable() {
  _invoicespecialvalues_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function invoicespecialvalues_civicrm_disable() {
  _invoicespecialvalues_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function invoicespecialvalues_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _invoicespecialvalues_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function invoicespecialvalues_civicrm_managed(&$entities) {
  _invoicespecialvalues_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function invoicespecialvalues_civicrm_caseTypes(&$caseTypes) {
  _invoicespecialvalues_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function invoicespecialvalues_civicrm_angularModules(&$angularModules) {
  _invoicespecialvalues_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function invoicespecialvalues_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _invoicespecialvalues_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function invoicespecialvalues_civicrm_entityTypes(&$entityTypes) {
  _invoicespecialvalues_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function invoicespecialvalues_civicrm_themes(&$themes) {
  _invoicespecialvalues_civix_civicrm_themes($themes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function invoicespecialvalues_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function invoicespecialvalues_civicrm_navigationMenu(&$menu) {
//  _invoicespecialvalues_civix_insert_navigation_menu($menu, 'Mailings', array(
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ));
//  _invoicespecialvalues_civix_navigationMenu($menu);
//}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_buildForm
 */
function invoicespecialvalues_civicrm_buildForm($formName, &$form) {
  // For custom field edit form
  if ($formName == 'CRM_Custom_Form_Field') {
    // Get Custom Group data of the form
    $customGroup = \Civi\Api4\CustomGroup::get()
      ->addSelect('extends')
      ->addWhere('id', '=', $form->getVar('_gid'))
      ->execute()
      ->first();

    // If extends is Participant or Contribution, continue
    if ($customGroup['extends'] == 'Participant' || $customGroup['extends'] == 'Contribution' ) {
      // Add js for the placing of our injected custom field
      CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.invoicespecialvalues', 'js/CRM_Custom_Form_Field.js', 100, 'page-footer');

      // Add custom field checkbox
      $form->addElement('checkbox', 'display_on_invoices', E::ts('Display on invoices'));

      // Assign bhfe to inject fields on the template.
      $tpl = CRM_Core_Smarty::singleton();
      $bhfe = $tpl->get_template_vars('beginHookFormElements');
      if (!$bhfe) {
        $bhfe = array();
      }
      $bhfe[] = 'display_on_invoices';
      $form->assign('beginHookFormElements', $bhfe);

      // Set defaults so our field has the right value if form is edit
      $id = $form->getVar('_id');
      if ($id) {
        $settings = CRM_Invoicespecialvalues_Settings::getSettings($id, 'custom_field');
        $defaults = array(
          'display_on_invoices' => $settings['display_on_invoices'],
        );
        $form->setDefaults($defaults);
      }
    }
  }
}

/**
 * Implements hook_civicrm_postProcess().
 *
 * @link hhttps://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postProcess
 */
function invoicespecialvalues_civicrm_postProcess($formName, &$form) {
  if ($formName == 'CRM_Custom_Form_Field') {
    // Get Custom Group data of the form
    $customGroup = \Civi\Api4\CustomGroup::get()
      ->addSelect('extends')
      ->addWhere('id', '=', $form->getVar('_gid'))
      ->execute()
      ->first();

    // If extends is Participant or Contribution, continue
    if ($customGroup['extends'] == 'Participant' || $customGroup['extends'] == 'Contribution' ) {
      // Get the id
      $id = $form->getVar('_id');

      // Save display_on_invoices with the id
      $settings = CRM_Invoicespecialvalues_Settings::getSettings($id, 'custom_field');
      $settings['display_on_invoices'] = $form->_submitValues['display_on_invoices'];
      CRM_Invoicespecialvalues_Settings::saveAllSettings($id, $settings, 'custom_field');
    }
  }
}
