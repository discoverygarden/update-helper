<?php

namespace Discoverygarden;

use Drupal\Core\Extension\ExtensionNameLengthException;
use Drupal\Core\Extension\MissingDependencyException;
use Drupal\Core\Utility\UpdateException;

/**
 * Update helper functions.
 */
class UpdateHelper {

  /**
   * Helper to ensure a given module is enabled.
   *
   * @param string $module
   *   The name of the module to ensure is enabled.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   A messge regarding the state of managing the module.
   *
   * @throws \Drupal\Core\Utility\UpdateException
   *   Thrown if we could not enable the module for some reason.
   */
  public static function ensureModuleEnabled(string $module) {
    $module_handler = \Drupal::moduleHandler();
    if ($module_handler->moduleExists($module)) {
      return t('The "@module_name" module is already enabled, no action necessary.', [
        '@module_name' => $module,
      ]);
    }

    /** @var \Drupal\Core\Extension\ModuleInstallerInterface $installer */
    $installer = \Drupal::service('module_installer');

    try {
      if ($installer->install([$module], TRUE)) {
        return t('The "@module_name" module was enabled successfully.', [
          '@module_name' => $module,
        ]);
      }
    }
    catch (ExtensionNameLengthException | MissingDependencyException $e) {
      throw new UpdateException("Failed; ensure that the '$module' module is available in the Drupal installation.", 0, $e);
    }
    catch (\Exception $e) {
      throw new UpdateException("Failed; encountered an exception while trying to enable '$module'.", 0, $e);
    }

    // Theoretically impossible to hit, as ModuleInstaller::install() only
    // return TRUE (or throws/propagates an exception), but... probably a good
    // idea to have the here, just in case?
    throw new UpdateException('Failed; hit the end of the update hook implementation, which is not expected.');
  }

}
