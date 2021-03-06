<?php

/**
 * @file
 * Definition of Drupal\domain\DomainCreatorInterface.
 */

namespace Drupal\domain;

use Drupal\domain\DomainInterface;

/**
 * Handles the creation of new domain records.
 */
interface DomainCreatorInterface {

  /**
   * Creates a domain object for saving.
   *
   * @param array $values
   *   The values to assign to the domain record.
   *   Required values are: hostname, name.
   *   Passing an empty array will create a domain from the current request.
   *
   * @return DomainInterface $domain
   *   A domain record object.
   */
  public function createDomain(array $values = array());

  /**
   * Creates a numeric id for the domain.
   *
   * The node access system still requires numeric keys.
   *
   * @return integer
   */
  public function createNextId();

  /**
   * Gets the hostname of the active request.
   *
   * @return string
   *   The hostname string of the current request.
   */
  public function createHostname();

  /**
   * Creates a machine-name string from the hostname.
   *
   * This string is the primary key of the entity.
   *
   * @param string $hostname
   *   The hostname of the domain record. If empty, the current request will be
   *   used.
   *
   * @return
   *   A string containing A-Z, a-z, 0-9, and _ characters.
   */
  public function createMachineName($hostname = NULL);

}
