<?php

namespace Toolbox;

class Config {

  const COMMENT_PATTERN = '`^#+`';

  const CONFIG_PATTERN = '`^\s*([\w-]+)\s?:\s?(.*)$`i';

  const PATH = 'config/config';

  private $data;

  private static $instance;

  /**
   * Will find the config/config file and parse it.
   */
  private function __construct() {
    $file = file(self::PATH, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $this->data = $this->parse($file);
  }

  /**
   * Parse a route file.
   *
   * @param  file   $file File content describing routes.
   *
   * @return array        Array containing RouteDescriptions objects.
   */
  private function parse($file) {
    $result = [];

    foreach ($file as $line) {
      if (!preg_match(self::COMMENT_PATTERN, $line)) {
        $r = $this->parseLine($line);
        $result = array_merge($result, $r);
      }
    }

    return $result;
  }

  /**
   * Parse a line from a file content describing config.
   *
   * @param  string $line Description of a config.
   *
   * @return array        Returns a duo key value in an array.
   */
  private function parseLine($line) {
    $r = [];

    if(preg_match_all(self::CONFIG_PATTERN, $line, $matches, PREG_SET_ORDER)) {
      $matches = $matches[0];
      $r[$matches[1]] = $matches[2];
    }

    return $r;
  }

  /**
   * Get config file value.
   *
   * @param  string $key A valid key.
   *
   * @return string      Returns a value.
   */
  public static function get($key) {
    if (!isset(self::$instance)) {
      self::$instance = new Config;
    }
    return self::$instance->data[$key];
  }
}