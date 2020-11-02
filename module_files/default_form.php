[[phptag]]

namespace Drupal\[[module_name]]\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\State;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class [[form_name]].
 *
 * @package Drupal\[[module_name]]\Form
 */
class [[form_name]] extends ConfigFormBase {

  /**
   * The Drupal State service.
   *
   * @var \Drupal\Core\State\State
   */
  protected $state;

  /**
   * Constructs a [[form_name]] object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\State\State $state
   *   The Drupal State service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, State $state) {
    parent::__construct($config_factory);
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return '[[module_name]]_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['[[module_name]].app_settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['div_id'] = [
      '#type' => 'textfield',
      '#title' => 'ID of div to embed into.',
      '#default_value' => $this->config('[[module_name]].app_settings')->get('div_id') ?: '',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save settings'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config('[[module_name]].app_settings')->set('div_id', $form_state->getValue('div_id'))->save();

    parent::submitForm($form, $form_state);
  }
}

