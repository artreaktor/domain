<?php

/**
 * @file
 * Contains \Drupal\domain_access\Plugin\Action\DomainAccessRemoveEditor.
 */

namespace Drupal\domain_access\Plugin\Action;

use Drupal\domain_access\Plugin\Action\DomainAccessActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Removes an editor from a domain.
 *
 * @Action(
 *   id = "domain_access_remove_editor_action",
 *   label = @Translation("Remove domain from editors"),
 *   type = "user"
 * )
 */
class DomainAccessRemoveEditor extends DomainAccessActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    $id = $this->configuration['id'];
    $user_domains = \Drupal::service('domain_access.manager')->getAccessValues($entity);
    // Skip adding the role to the user if they already have it.
    if ($entity !== FALSE && isset($user_domains[$id])) {
      unset($user_domains[$id]);
      $entity->set(DOMAIN_ACCESS_FIELD, array_keys($new_domains));
      $entity->save();
    }
  }

}
