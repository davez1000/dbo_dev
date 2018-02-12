<?php

declare(strict_types = 1);

namespace Drupal\dbo_dev\Alter;

use Drupal\Core\Form\FormStateInterface;

/**
 * Interface FormAlterInterface.
 *
 * @package Drupal\dbo_dev\Alter
 */
interface DboFormAlterInterface {

  /**
   * Hook for form alter as service.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param string $form_id
   *   The form id.
   *
   * @see hook_form_alter()
   */
  public function hookFormAlter(array &$form, FormStateInterface $form_state, $form_id);

  /**
   * If altering the submit of a form alterSubmit is provided.
   *
   * Usage: $form['actions']['submit']['#submit'][] = '::alterSubmit';
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @see hook_form_alter()
   */
  public function alterSubmit(array &$form, FormStateInterface $form_state);

  /**
   * If altering the validation of a form alterValidation is provided.
   *
   * Usage: $form['actions']['submit']['#submit'][] = '::alterValidation';
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @see hook_form_alter()
   */
  public function alterValidation(array &$form, FormStateInterface $form_state);

}
