<?php

/**
 * @file
 * Definition of Drupal\domain\DomainCreator.
 */

namespace Drupal\domain;

use Drupal\domain\DomainCreatorInterface;
use Drupal\domain\DomainInterface;

class DomainCreator implements DomainCreatorInterface {

  /**
   * Constructs a DomainLoader object.
   *
   * @param \Drupal\domain\DomainLoaderInterface $loader
   *   The domain loader.
   */
  public function __construct(DomainLoaderInterface $loader) {
    $this->loader = $loader;
  }

  /**
   * Creates a new domain record object.
   *
   * @param array $values
   *   The values for the domain.
   * @param bool $inherit
   *   Indicates that values should be calculated from the current domain.
   */
  public function createDomain(array $values, $inherit = FALSE) {
    $default = $this->loader->getDefaultId();
    $domains = $this->loader->loadMultiple();
    $values += array(
      'scheme' => empty($GLOBALS['is_https']) ? 'http' : 'https',
      'status' => 1,
      'weight' => count($domains) + 1,
      'is_default' => (int) empty($default),
    );
    if ($inherit) {
      $values['hostname'] = $this->createHostname();
      $values['name'] = \Drupal::config('system.site')->get('name');
      $values['id'] = $this->createMachineName($values['hostname']);
    }
    // Fix this.
    $domain = Domain::create($values);
    return $domain;
  }

  /**
   * Gets the next numeric id for a domain.
   */
  public function createNextId() {
    $domains = $this->loader->loadMultiple();
    $max = 0;
    foreach ($domains as $domain) {
      if ($domain->domain_id > $max) {
        $max = $domain->domain_id;
      }
    }
    return $max + 1;
  }

  /**
   * Gets the hostname of the active request.
   */
  public function createHostname() {
    return !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
  }

  /**
   * Gets the machine name of a host, used as primary key.
   */
  public function createMachineName($hostname) {
    return preg_replace('/[^a-z0-9_]+/', '_', $hostname);
  }


}