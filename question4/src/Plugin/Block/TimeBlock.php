<?php

namespace Drupal\question4\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Component\Serialization\Json;


/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "time_example_block",
 *   admin_label = @Translation("My time"),
 * )
 */
class TimeBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $client = \Drupal::httpClient();
    if ($this->configuration['roundoff']) {
      $title = 'EST';
      $val = $client
        ->request('GET', 'http://worldclockapi.com/api/json/est/now');
       $res = Json::decode($val->getBody());

    }
    else{
      $title = 'UTC';
      $val = $client
        ->request('GET', 'http://worldclockapi.com/api/json/utc/now');
      $res = Json::decode($val->getBody());
    }
    $date = explode('T', $res['currentDateTime']);
    //kint($date);
    $val = 'DATE:' . $date[0].'<br/>'.'TIME:' .$date[1];
    return [
      '#title' => $title,
      '#markup' => $val ,
      '#prefix' => '<div>',
      '#suffix' => '</div>',
      '#attached' => [
        'library' => [
          'question4/customcss',
        ],
      ],
    ];
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
  public function blockForm($form, FormStateInterface $form_state) {
    $form['roundoff'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('should go with UTS (deafault:est)'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['roundoff'] = $form_state->getValue('roundoff');

  }
}
