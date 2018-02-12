<?php

/**
 * @file
 * Contains \Drupal\dbo_dev\Form\DboDevForm.
 */

namespace Drupal\dbo_dev\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DboTestForm
 */
class DboDevForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dbo_dev_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $region = NULL) {
    // Let's build the actual form.
    $form['dbo_region'] = array (
      '#type' => 'select',
      '#title' => ('Filter by region'),
      '#options' => array(
        'All' => t('All'),
        'Blue Mountains' => t('Blue Mountains'),
        'Central Coast' => t('Central Coast'),
        'Country NSW' => t('Country NSW'),
        'Hunter' => t('Hunter'),
        'Lord Howe Island' => t('Lord Howe Island'),
        'North Coast' => t('North Coast'),
        'Outback NSW' => t('Outback NSW'),
        'Snowy Mountains' => t('Snowy Mountains'),
        'South Coast' => t('South Coast'),
      ),
      '#default_value' => $region,
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('See some results!'),
      '#button_type' => 'primary',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Nothing to do here really, as we don't have any manual input.
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    switch ($form['#form_id']) {
      case 'dbo_dev_form':
        // Redirect to the correct URL if we have selected an option.
        $values = $form_state->getValues();
        if (isset($values['dbo_region'])) {
          $form_state->setRedirect('dbo.form', array(
            'region' => $values['dbo_region'],
          ));
        }
        break;
      default:
        break;
    }
  }

}
