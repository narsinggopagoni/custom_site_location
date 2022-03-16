<?php

namespace Drupal\custom_site_location;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Custom Service for the displaying the LocalTimezone.
 */
class MyTimezone {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The logged in userAccount.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, AccountInterface $account) {
    $this->configFactory = $config_factory;
    $this->account = $account;
  }

  /**
   * Fetching the local timezone settings.
   */
  public function localTimezone() {
    $config = $this->configFactory->get('custom_site_location.timezonesettings');
    $timezone = $config->get('timezone') ? $config->get('timezone') : $this->defaultUserTimezone();
    $date = new \DateTime('now', new \DateTimeZone($timezone));
    return $date->format('dS M Y - H:i A');
  }

  /**
   * Default usertimezone.
   */
  protected function defaultUserTimezone() {
    $user = $this->account;
    $config = $this->configFactory->get('system.date');

    if ($user &&
        $config->get('timezone.user.configurable') &&
        $user->isAuthenticated() &&
        $user->getTimezone()) {
      return $user->getTimezone();
    }
    else {
      $config_data_default_timezone = $config->get('timezone.default');
      return !empty($config_data_default_timezone) ?
            $config_data_default_timezone :
            @date_default_timezone_get();
    }
  }

}
