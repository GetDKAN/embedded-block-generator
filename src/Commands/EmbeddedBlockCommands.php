<?php

namespace Drupal\embedded_block_generator\Commands;

use Drush\Commands\DrushCommands;
use Drupal\Core\File\FileSystemInterface;

/**
 * A drush command file.
 *
 * @package Drupal\embedded_block_generator\Commands
 */
class EmbeddedBlockCommands extends DrushCommands {

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
      if (\Drupal::service('file_system')->prepareDirectory($path, FileSystemInterface::CREATE_DIRECTORY)) {
        $buildmodule->buildModuleFiles($module_name, $path);
      }
      $this->output()->writeln('Module ' . $module_name . ' Created successfully');
    }
    catch (Exception $e) {
      $this->output()->writeln($e->getMessage());
    }

  }
}
