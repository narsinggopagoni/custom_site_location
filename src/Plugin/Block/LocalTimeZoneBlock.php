<?php

namespace Drupal\custom_site_location\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\custom_site_location\MyTimezone;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a block with custom timezone settings.
 *
 * @Block(
 *   id = "custom_timezone_block",
 *   admin_label = @Translation("Local Time")
 * )
 */
class LocalTimeZoneBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The localtimezone.
   *
   * @var LocalTimezone
   */
  protected $localtimezone;

  /**
   * The Immutable Config variables.
   *
   * @var configFactory
   */
  protected $configFactory;

  /**
   * Constructs a SyndicateBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param Drupal\custom_site_location\MyTimezone $timezone
   *   The custom timezone service.
   * @param Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The Immutable config factory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MyTimezone $timezone, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->localtimezone = $timezone;
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
      $container->get('custom_site_location.mytimezone'),
      $container->get('config.factory'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(
           parent::getCacheTags(),
           ['custom_site_location:block.custom_timezone_block']
       );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->configFactory->get('custom_site_location.timezonesettings');
    $country = $config->get('country');
    $city = $config->get('city');
    return [
      "#theme" => 'local_timestamp',
      '#local_time' => $this->localtimezone->localTimezone(),
      '#city' => $city,
      '#country' => $country,
    ];
  }

}
