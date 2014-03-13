<?php

namespace Toolbox\Router;

/**
 * Route parser.
 */
class RouteFileParser {

  const COMMENT_PATTERN = '`^#`';

  const ROUTE_PATTERN = '`(^\/[\w@-{}\/]*|[\/\w@{}:()]*$)`i';

  /**
   * Parse a route file.
   *
   * @param  file   $file File content describing routes.
   *
   * @return array        Array containing RouteDescriptions objects.
   */
  public static function parse($file) {
    $result = [];

    foreach ($file as $line) {
      if (!preg_match(self::COMMENT_PATTERN, $line)) {
        $route = self::parseLine($line);
        array_push($result, $route);
      }
    }

    return $result;
  }

  /**
   * Parse a line from a file content describing routes.
   * @param  string $line      Description of a route
   * @return RouteDescritpion  A RouteDescription instance.
   */
  public static function parseLine($line) {
    if(preg_match_all(self::ROUTE_PATTERN, $line, $matches)) {
      $uri = $matches[0][0];
      $action = $matches[0][1];

      return new RouteDescription($uri, $action);
    } else {
      throw new \Exception('The line "' . $line . '" is invalid.', 1);
    }
  }
}