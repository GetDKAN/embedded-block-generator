<?php

namespace Drupal\embedded_block_generator;

use Drupal\Core\Extension\ModuleHandler;
use Drupal\Core\File\FileSystemInterface;

/**
 * Creates module files.
 */
class SetupModule {
  protected $modulePath;
  protected $fileSystem;

  /**
   * Constructor for Setup.
   */
  public function __construct(ModuleHandler $modulehandler, FileSystemInterface $filesystem) {
    $this->modulePath = $modulehandler->getModule('embedded_block_generator')->getPath();
    $this->fileSystem = $filesystem;
  }

  /**
   * Builds module files.
   *
   * @param string $name
   *   The name of the new module.
   * @param string $path
   *   The path of the new module
   */
  public function buildModuleFiles($name, $path) {
    $module_name = preg_replace('@[^a-z0-9-]+@', '_', strtolower($name));
    // Add settings form
    // Convert to spaces
    $camel_case_name = preg_replace('/[^a-z0-9' . implode("", []) . ']+/i', ' ', $module_name);
    $camel_case_name = trim($camel_case_name);
    // Uppercase the first character of each word
    $camel_case_name = ucwords($camel_case_name);
    // Remove spaces
    $camel_case_name = str_replace(" ", "", $camel_case_name);

    $files_and_replacments = [
      '/module_files/default_info.yml' => [
        'replacements' => [
          '[[module_name]]' => $name,
        ],
        'file_name' => $module_name . '.info.yml',
        'path' => $path,
      ],
      '/module_files/default_form.php' => [
        'replacements' => [
          '[[module_name]]' => $module_name,
          '[[form_name]]' => $camel_case_name . 'SettingsForm',
          '[[phptag]]' => '<?php',
        ],
        'file_name' => $camel_case_name . 'SettingsForm.php',
        'path' => $path . '/src/Form',
      ],
      '/module_files/default_block.php' => [
        'replacements' => [
          '[[module_name]]' => $module_name,
          '[[block_name]]' => $camel_case_name,
          '[[name]]' => $name,
          '[[phptag]]' => '<?php',
        ],
        'file_name' => $camel_case_name . '.php',
        'path' => $path . '/src/Plugin/Block',
      ],
      '/module_files/default.routing.yml' => [
        'replacements' => [
          '[[module_name]]' => $module_name,
          '[[form_name]]' => $camel_case_name . 'SettingsForm',
          '[[name]]' => $name,
        ],
        'file_name' => $module_name . '.routing.yml',
        'path' => $path,
      ],
      '/module_files/default.permissions.yml' => [
        'replacements' => [
          '[[name]]' => $name,
        ],
        'file_name' => $module_name . '.permissions.yml',
        'path' => $path,
      ],
      '/module_files/default.libraries.yml' => [
        'replacements' => [
          '[[module_name]]' => $module_name,
        ],
        'file_name' => $module_name . '.libraries.yml',
        'path' => $path,
      ],
      '/module_files/default.links.menu.yml' => [
        'replacements' => [
          '[[name]]' => $name,
          '[[module_name]]' => $module_name,
        ],
        'file_name' => $module_name . '.links.menu.yml',
        'path' => $path,
      ],
    ];

    // Add JS, CSS, and React directories.
    $directories_and_readmes = [
      $path . '/css' => [
        'readme' => 'Compile app css to this directory.',
      ],
      $path . '/js' => [
        'readme' => 'Compile app JS to this directory.',
      ],
      $path . '/react' => [
        'readme' => 'Add root of react project to the directory. In some cases where the application is managed in a separate repo, you will want to add this directory to gitignore.'
      ],
      $path => [
        'readme' => 'Update this README to match your project.'
      ]
    ];
    $this->buildDefaultFiles($files_and_replacments);
    $this->buildDirectories($directories_and_readmes);
  }

  /**
   * Builds the default files.
   *
   * @param array $files
   *   An array of files and properties.
   */
  private function buildDefaultFiles($files) {
    foreach ($files as $default_file => $properties) {
      $file_name = $properties['file_name'];
      $file_data = file_get_contents($this->modulePath . $default_file);
      foreach ($properties['replacements'] as $replacement_key => $replacement_value) {
        $file_data = str_replace($replacement_key, $replacement_value, $file_data);
      }
      if ($this->fileSystem->prepareDirectory($properties['path'], FileSystemInterface::CREATE_DIRECTORY)) {
        file_put_contents($properties['path'] . '/' . $file_name, $file_data);
      }
    }
  }

  /**
   * Builds the directories with README.
   *
   * @param array $directories
   *   An array of directories and readmes.
   */
  private function buildDirectories($directories) {
    foreach ($directories as $directory => $properties) {
      if ($this->fileSystem->prepareDirectory( $directory, FileSystemInterface::CREATE_DIRECTORY)) {
        file_put_contents($directory . '/' . 'README.md', $properties['readme']);
      }
    }
  }
}
