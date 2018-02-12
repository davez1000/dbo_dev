<?php

/*
 * @file
 * Helper function.
 */

namespace Drupal\dbo_dev\Helper;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class DboHelper
 *
 * @package Drupal\dbo_dev\Helper
 */
class DboHelper {

  /**
   * GuzzleHttp\Client definition.
   *
   * @var GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * Symfony\Component\HttpFoundation\RequestStack definition.
   *
   * @var Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    Client $http_client,
    RequestStack $request_stack
  ) {
    $this->httpClient = $http_client;
    $this->requestStack = $request_stack;
  }

  /**
   * Helper guzzle get call function.
   *
   * @param string $url
   *   This is the url to call.
   * @return string $data
   *  JSON data.
   */
  public function getApiCall(string $url) {
    // Guzzle request.
    try {
      $call = $this->httpClient->request('GET', $url);
      $code = $call->getStatusCode();
      if ($code == 200) {
        $content = $call->getBody()->getContents();
        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');
        if (!empty($content)) {
          $content = json_decode($content, TRUE);
          return $content;
        }
        else {
          drupal_set_message(t('no content found'), 'error', FALSE);
          return FALSE;
        }
      }
    }
    catch (Exception $e) {
      drupal_set_message(t('SERVICE ERROR'), 'error', FALSE);
      return FALSE;
    }

  }

  /**
   * URL builder.
   *
   * @param string $region
   *  The region to search.
   *
   * @return string
   *  Built URL.
   */
  public function buildUrl($region) {
    // Get query string for page number to pass to API URL.
    $query_string = $this->requestStack->getCurrentRequest()->getQueryString();
    $pg = '';
    if (!empty($query_string)) {
      $query_parts = explode('=', $query_string);
      $pg = '&pge=' . $query_parts[1];
    }
    $region = urlencode($region);
    // Get URL and key from config.
    $config = \Drupal::config('dbo_dev.config');
    // Build URL.
    $url = $config->get('api_url') . 'products?key=' . $config->get('api_key') . '&cats=ACCOMM&st=NSW' . ($region != 'All' ? '&rg=' . $region : '') . '&out=json' . $pg;
    return $url;
  }

}
