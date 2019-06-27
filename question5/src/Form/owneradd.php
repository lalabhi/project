<?php
namespace Drupal\question5\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
/**
 * Defines a form that configures forms module settings.
 */
class owneradd extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'addperson_settings';
  }
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'addperson.settings'
    ];
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('addperson.settings');
    $form['person'] = [
      '#type' => 'email',
      '#title' => $this->t('person id'),
      '#required' => TRUE,
    ];
    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    if($this->config('addperson.settings')->get('person')){
      $arr = $this->config('addperson.settings')->get('person');
    }
    else{
      $arr = [];
    }
//    kint($arr);
//    exit;
    array_push($arr,$form_state->getValue('person'));
    //kint($form_state->getValue('person'));
    //kint($arr);
    $this->config('addperson.settings')->set('person', $arr)->save();
    $arr2 = $this->config('addperson.settings')->get('person');
    //kint($arr2);
    parent::submitForm($form, $form_state);
  }
}
