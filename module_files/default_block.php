[[phptag]]

namespace Drupal\[[module_name]]\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\Cache;

/**
 * Provides the React App block.
 *
 * @Block(
 *   id = "[[module_name]]_block",
 *   admin_label = @Translation("[[name]] block"),
 * )
 */
class [[block_name]] extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The Config.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a [[block_name]] object.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin id.
   * @param mixed $plugin_definition
   *   The plugin definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory services.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $div_id = $this->configFactory->get('[[module_name]].app_settings')->get('div_id') ?? 'app';

    $build = [
      '#type' => 'inline_template',
      '#template' => '<div id="' . $div_id . '"></div>',
      '#attached' => [
    // 'drupalSettings' => [
    // 'itemName' => 'itemValue',
    // ],
        'library' => [
        '[[module_name]]/[[module_name]]'
        ]
      ],
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), ['config:[[module_name]].app_settings']);
  }
}
