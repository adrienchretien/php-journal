<?php

namespace Toolbox\Router;

// require_once 'Route.php';

class RouteDescription {

  /**
   * Regular expressions used in this class. The two variables are used as
   * constants because constant don't accept complex type values.
   */

  /**
   * Recognize any route file pattern using curly braces.
   */
  const ANY_PATTERN = '`{@?[\w-]+}`i';

  /**
   * Array of all regular expression patterns used to recognise route file 
   * patterns.
   * @var array
   */
  private $patterns;

  /**
   * Array of all regular expression replacements used to recognise route file
   * patterns.
   * @var array
   */
  private $replacements;

  /* End of regular expressions */

  /**
   * Description of an URI from the route file.
   * @var string
   */
  private $uriFormat;

  /**
   * Description of an action from the route file.
   * @var string
   */
  private $actionFormat;

  /**
   * Regular expression used match the uriFormat and URIs from the server.
   * @var string
   */
  private $regex;


  /**
   * Create a representation of a route description.
   *
   * @param string $uriFormat    String describing the structure of a URI
   *                             format. URI format must, at least, have a
   *                             slash character at the beginning. Keyword
   *                             can be used by putting them into curly
   *                             braces.
   *
   *                             Example of URI format :
   *                             /
   *                             /{keyword}
   *                             /admin/{keyword1}/{keyword2}/
   *
   * @param string $actionFormat String describing how to access a static
   *                             ressource or a controller action. Keyword
   *                             can be used by putting them into curly
   *                             braces.
   *
   *                             Example of action format :
   *                             /path/to/a/ressource.ext
   *                             /path/to/a/Controller::method()
   *                             /path/to/a/{controller}::{action}()
   *
   * @return RouteDescription    A new instance.
   */
  public function __construct($uriFormat, $actionFormat){
    $this->patterns = ['/{@path}/i','/{[\w-]+}/i'];
    $this->replacements = ['((?:\w[\w-.]*/)*(?:[\w-.]+([.][a-z]{2,4}$){0,1}))','([\w-]+)'];

    $this->uriFormat = $uriFormat;
    $this->actionFormat = $actionFormat;

    $this->regex = $this->formatToRegex($uriFormat);
  }

  /**
   * Determine if the given URI match with the route.
   *
   * @param  string $uri An URI formatted string.
   *
   * @return Route       Returns a Route instance if it matches else NULL.
   */
  public function matchUri($uri) {
    $match = preg_match($this->regex, $uri) === 1;
    $route = null;

    if ($match) {
      $patternValues = $this->extractPatternValues($uri);

      $action = $this->fillActionFormat($patternValues);
      $params = $patternValues;
      $route = Route::fromAction($action, $params);
    }

    return $route;
  }

  private function deduceParameters($params) {
    foreach ($params as $key => $value) {
      if(preg_match($key, $this->actionFormat)) {
        unset($params[$key]);
      }
    }
    return $params;
  }

  /**
   * Extract keywords from URI accordingly to the uri format.
   *
   * @param  string $uri An URI formatted string.
   *
   * @return array       An associated array with keywords as keys and their 
   *                     values from the given URI.
   */
  private function extractPatternValues($uri) {
    $keywords = [];
    $validateValue = function ($val) {
      return $val !== '';
    };

    $keys = $this->findKeywords();
    $values = $this->findValues($uri);

    $len = count($keys);

    $values = array_filter($values, $validateValue);
    $values = array_values($values);

    for ($i=0; $i < $len; $i++) {
      $keywords[$keys[$i]] = $values[$i];
    }

    return $keywords;
  }

  /**
   * Fill the action format with the given keywords.
   *
   * @param  array $keywords An array of keywords from the extractKeywords
   *                         method.
   * @return string          Returns a string.
   */
  private function fillActionFormat($keywords) {
    $action = $this->actionFormat;

    foreach ($keywords as $key => $value) {
      $action = str_replace($key, $value, $action);
    }

    return $action;
  }

  private function findKeywords() {
    preg_match_all(self::ANY_PATTERN, $this->uriFormat, $matches);
    return $matches[0];
  }

  private function findValues($uri) {
    preg_match_all($this->regex, $uri, $matches, PREG_SET_ORDER);
    $matches = $matches[0];
    array_splice($matches, 0, 1);
    return $matches;
  }

  /**
   * Transform a URI format as a regular expression pattern.
   *
   * @param  string $format String representing a URI format.
   *
   * @return string         Returns a regular expression.
   */
  private function formatToRegex($format) {
    $format = preg_replace($this->patterns, $this->replacements, $format);

    return '`^' . $format . '$`';
  }
}