<?php

namespace Toolbox\Router;

/**
 * Interface to manipulate Routes.
 */
class RouteController {

  /**
   * Array of all available routes.
   * @var array
   */
  private $descriptions;

  /**
   * Construct a new RouteController instance.
   * @param string $path Path to a file describing routes.
   */
  public function __construct($path) {
    $file = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $this->descriptions = RouteFileParser::parse($file);
  }

  /**
   * Find a route from a URI string.
   * @param  string $uri An URI formatted string.
   * @return array      An array of all corresponding routes.
   */
  public function find($uri) {
    $result = [];

    foreach ($this->descriptions as $desc) {
      $match = $desc->matchUri($uri);
      if(!is_null($match)) {
        array_push($result, $match);
      }
    }

    return $result;
  }
}