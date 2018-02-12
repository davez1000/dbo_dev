<?php

namespace Drupal\dbo_dev\Alter;

use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\dbo_dev\Helper\DboHelper;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class DboDevFormAlter.
 *
 * @package Drupal\dbo_dev\Alter
 */
class DboDevFormAlter implements DboFormAlterInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The current user.
   *
   * @var \Drupal\dbo_dev\Helper\DboHelper
   */
  protected $dboHelper;

  /**
   * Symfony\Component\HttpFoundation\RequestStack definition.
   *
   * @var Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs a new DboFormAlterInterface object.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\dbo_dev\Helper\DboHelper $dbo_helper
   *   Helper functions.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Helper functions.
   */
  public function __construct(
    AccountInterface $current_user,
    DboHelper $dbo_helper,
    RequestStack $request_stack
  ) {
    $this->currentUser = $current_user;
    $this->dboHelper = $dbo_helper;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public function hookFormAlter(array &$form, FormStateInterface $form_state, $form_id) {

    // Get URL, pass region to API call.
    $current_path = \Drupal::service('path.current')->getPath();
    $path_args = explode('/', $current_path);
    $path = !isset($path_args[3]) ? 'All' : $path_args[3];
    $url = $this->dboHelper->buildUrl($path);
    $call = $this->dboHelper->getApiCall($url);

    // Total number of items.
    $items = array();
    $items[] = '<div><strong>Total number of items: ' . number_format($call['numberOfResults']) . '</strong></div><div><hr /></div>';

    // Page number.
    $query_string = $this->requestStack->getCurrentRequest()->getQueryString();
    if (!empty($query_string)) {
      $query_parts = explode('=', $query_string);
      $items[] = '<div><strong>Page: ' . $query_parts[1] . '</strong></div><div><hr /></div>';
    }

    // Display items.
    if (!empty($call['products'])) {
      foreach ($call['products'] as $item) {
        $s = array();
        $s[] = '<div><strong>' . $item['productName'] . '</strong></div>';
        $s[] = '<div>' . $item['productDescription'] . '</div>';
        $s[] = '<div><img src="' . $item['productImage'] . '" /></div>';
        $s[] = '<div><hr /></div>';
        $items[] = implode('', $s);
      }
    }

    // Pagaination.
    $pages = ceil($call['numberOfResults'] / 10);
    $page_links = array();
    for ($i = 1; $i <= $pages; $i++) {
      $page_links[] = '<a href="' . $current_path . '?page=' . $i . '">' . $i . '</a> | ';
    }
    $items[] = '<hr />';
    $items[] = implode('', $page_links);

    $display_items = implode('', $items);

    // Markup form element to display the items.
    $form['dbo_results'] = array(
      '#markup' => $display_items,
      '#weight' => 1000,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function alterSubmit(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function alterValidation(array &$form, FormStateInterface $form_state) {}

}
