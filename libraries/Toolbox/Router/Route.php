<?php

namespace Toolbox\Router;

class Route {

  const CONTROLLER_PATTERN = '`((?:[\w-]+\/)*)(\w+)::([\w-]+)$`i';

  const FILE_PATTERN = '`(\/(?:[\w-]+\/)*)([\w?-]+[.][a-z]{2,4})$`i';

  private $callback;

  private $parameters;

  public function __construct($callback, $parameters = null) {
    $this->callback = $callback;
    $this->parameters = $parameters;
  }

  /**
   * Call the callback function defined in the constructor.
   * @return mixed Returns the callback value or FALSE on error.
   */
  public function go() {
    return call_user_func($this->callback);
  }

  /**
   * Create a route from a path, a Controller::method format string or both.
   *
   * @param  string $action     Path or Controller::method string.
   * @param  array  $parameters Optional parameters to add when the method
   *                            will be called.
   * @return Route              A route instance if the action is lead to a 
   *                            valid path.
   */
  public static function fromAction($action, $params = null) {
    $callback = null;
    $path = null;
    $route = null;

    if (preg_match_all(self::CONTROLLER_PATTERN, $action, $output, PREG_SET_ORDER)) {
      $matches = $output[0];
      array_splice($matches, 0, 1);

      $controller = ucfirst($matches[1]);
      $method = str_replace('-', '_', $matches[2]);

      $path = 'controllers/';
      
      if ($matches[0] !== '') {
        $path .= $matches[0];
      }

      $path .= $controller . '.php';

      if (file_exists($path)) {
        require_once $path;
        if (method_exists($controller, $method)) {
          $callback = function () use ($path, $controller, $method, $params) {
            $controller::$method($params);
          };
        }
      }

    }

    if (isset($callback)) {
      $route = new Route($callback, $params);
    }

    return $route;
  }
}