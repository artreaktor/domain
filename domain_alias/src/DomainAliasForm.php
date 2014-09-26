<?php

/**
 * @file
 * Definition of Drupal\domain_alias\DomainAliasForm.
 */

namespace Drupal\domain_alias;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Base form controller for domain alias edit forms.
 */
class DomainAliasForm extends EntityForm {

  /**
   * Overrides Drupal\Core\Entity\EntityForm::form().
   */
  public function form(array $form, FormStateInterface $form_state) {
    $alias = $this->entity;

    $form['domain_id'] = array(
      '#type' => 'value',
      '#value' => $alias->domain_id,
    );
    $form['pattern'] = array(
      '#type' => 'textfield',
      '#title' => t('Pattern'),
      '#size' => 40,
      '#maxlength' => 80,
      '#default_value' => $alias->pattern,
      '#description' => t('The matching pattern for this alias.'),
      '#required' => TRUE,
    );
    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $alias->id(),
      '#machine_name' => array(
        'source' => array('pattern'),
        'exists' => 'domain_alias_load', // @TODO
      ),
    );
    $form['redirect'] = array(
      '#type' => 'select',
      '#options' => $this->redirectOptions(),
      '#default_value' => $alias->redirect,
      '#description' => t('Redirect status'),
    );

    return parent::form($form, $form_state, $alias);
  }

  public function redirectOptions() {
    return array(
      0 => t('Do not redirect'),
      301 => t('301 redirect: Moved Permanently'),
      302 => t('302 redirect: Found'),
    );
  }

  /**
   * Overrides \Drupal\Core\Entity\EntityForm::validate().
   */
  public function validate(array $form, FormStateInterface $form_state) {
    $entity = $this->buildEntity($form, $form_state);
    $errors = $entity->validate();
    if (!empty($errors)) {
      form_set_error('pattern', $errors);
    }
  }

  /**
   * Overrides Drupal\Core\Entity\EntityForm::save().
   */
  public function save(array $form, FormStateInterface $form_state) {
    $alias = $this->entity;
    if ($alias->isNew()) {
      drupal_set_message(t('Domain alias created.'));
    }
    else {
      drupal_set_message(t('Domain alias updated.'));
    }
    $alias->save();
    $form_state['redirect'] = 'admin/structure/domain/alias/' . $alias->domain_id;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityForm::delete().
   */
  public function delete(array $form, FormStateInterface $form_state) {
    $alias = $this->entity;
    $alias->delete();
    $form_state['redirect'] = 'admin/structure/domain/alias/' . $alias->domain_id;
  }
}