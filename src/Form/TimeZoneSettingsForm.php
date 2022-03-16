<?php

namespace Drupal\custom_site_location\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;

/**
 * Config form for setting the local timezone.
 */
class TimeZoneSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'custom_site_location.timezonesettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_timezone_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom_site_location.timezonesettings');

    $form['country'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Country'),
      '#description' => $this->t('Enter Country'),
      '#default_value' => $config->get('country'),
    ];

    $form['city'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('City'),
      '#description' => $this->t('Enter City'),
      '#default_value' => $config->get('city'),
    ];

    $options = [
      '' => ' - ' . $this->t('Select') . ' - ',
      'America/Chicago' => $this->t('America/Chicago'),
      'America/New_York' => $this->t('America/New_York'),
      'Asia/Tokyo' => $this->t('Asia/Tokyo'),
      'Asia/Dubai' => $this->t('Asia/Dubai'),
      'Asia/Kolkata' => $this->t('Asia/Kolkata'),
      'Europe/Amsterdam' => $this->t('Europe/Amsterdam'),
      'Europe/Oslo' => $this->t('Europe/Oslo'),
      'Europe/London' => $this->t('Europe/London'),
    ];

    $form['timezone'] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#title' => $this->t('TimeZone'),
      '#options' => $options,
      '#description' => $this->t('Select TimeZone'),
      '#default_value' => $config->get('timezone'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    Cache::invalidateTags(['custom_site_location:block.custom_timezone_block']);

    $this->config('custom_site_location.timezonesettings')
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('timezone', $form_state->getValue('timezone'))
      ->save();
  }

}
