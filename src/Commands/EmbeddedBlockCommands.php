<?php

namespace Drupal\embedded_block_generator\Commands;

use Drush\Commands\DrushCommands;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * A drush command file.
 *
 * @package Drupal\embedded_block_generator\Commands
 */
class EmbeddedBlockCommands extends DrushCommands {
  use StringTranslationTrait;
  /**
   * Drush command that displays the given text.
   *
   * @param string $module_name
   *   Argument with message to be displayed.
   * @command EmbeddedBlockCommands:init
   * @aliases ebc-init
   * @usage EmbeddedBlockCommands:init module_name
   */
  public function init($module_name) {
    $buildmodule = \Drupal::service('embedded_block_generator.setup_module');
    try {
      $path_name = preg_replace('@[^a-z0-9-]+@', '_', strtolower($module_name));
      $path = DRUPAL_ROOT . '/modules/custom/' . $path_name;
      if (file_prepare_directory($path, FILE_CREATE_DIRECTORY)) {

        $buildmodule->buildModuleFiles($module_name, $path);
//        $buildmodule->buildRoutingFile($values, $path, $module_name);


//        if ($values['default_controller'] == 1) {
//          $create_controller = $buildmodule->buildController($values, $path, $module_name);
//        }
//        if ($values['default_form'] == 1) {
//          $create_form = $buildmodule->buildForm($values, $path, $module_name);
//        }
//        if ($values['default_block'] == 1) {
//          $create_block = $buildmodule->buildBlock($values, $path, $module_name);
//        }
//        chmod($path, 0777);
//        if ($values['module_option'] == 2) {
//          module_maker_enable_module($module_name, $name);
//        }
//        if (!empty($values['module_option']) && $values['module_option'] == 1) {
//          module_maker_create_zip($module_name);
//          drupal_set_message(t('Module "<i> @name </i>" Created successfully', ['@name' => $name]), 'status');
//        }
      }
      $this->output()->writeln('Module ' . $module_name . ' Created successfully');
    }
    catch (Exception $e) {
      $this->output()->writeln($e->getMessage());
    }



  }

}
