<?php

use Kurenai\Document;
use Kurenai\DocumentParser;

class MarkdownDocument {

  const PATH = 'file-storage/';

  /**
   * Document representation.
   *
   * @var Document
   */
  protected $document;

  /**
   * File name.
   *
   * @var string
   */
  protected $filename;

  /**
   * Construct an empty document.
   *
   * @return Document Returns a new document instance.
   */
  public function __construct() {
    $this->document = new Document();
    $this->filename = null;
  }

  /**
   * Get metadata information about the document from its key.
   *
   * @param  string $key A metadata valid key.
   *
   * @return string      The value corresponding to the key.
   */
  public function get($key) {
    return $this->document->get($key);
  }

  /**
   * Get the HTML content of the document.
   *
   * @return string The HTML content.
   */
  public function getHtml() {
    return $this->document->getHtmlContent();
  }

  /**
   * Get the markdown content of the document.
   *
   * @return string The markdown content.
   */
  public function getMarkdown() {
    return $this->document->getContent();
  }

  /**
   * Set metadata information about the document.
   *
   * @param  string $key   A metadata valid key.
   * @param  string $value A metadata value.
   */
  public function set($key, $value) {
    $this->document->add($key, $value);
    return $this;
  }

  /**
   * Set the markdown content of the document.
   *
   * @param string $content The markdown content.
   */
  public function setMarkdown($content) {
    $this->document->setContent($content);
    return $this;
  }

  /**
   * Format string to slug format.
   *
   * @param   string $str String to format.
   *
   * @return  string      The slug.
   */
  protected function formatToSlug($toSlug) {
    // Replace accented characters
    setlocale(LC_ALL, 'en_GB.UTF-8');
    $toSlug = iconv('UTF-8', 'ASCII//TRANSLIT', $toSlug);
    // Set all characters to lower case
    $toSlug = strtolower($toSlug);
    // Replace undesired characters
    $regex = ['/\s|\p{P}|\\|\//', '/(-)\1+/', '/[-]+$/'];
    $replacements = ['-', '$1', ''];
    $toSlug = preg_replace($regex, $replacements, $toSlug);

    return $toSlug;
  }

  /**
   * Get metadata into Markdown format.
   *
   * @return string The document metadata into the Markdown format.
   */
  protected function metadataToMarkdown() {
    $metadata = $this->document->get();
    $result = '---' . PHP_EOL;

    foreach ($metadata as $key => $value) {
      $result = $result . $key . ': ' . $value . PHP_EOL;
    }

    $result = $result . '---' . PHP_EOL . PHP_EOL;

    return $result;
  }

  protected function dir_exists($path) {
    if (!file_exists($path)) {
      throw new \Exception('No ' . $path . ' directory found.', 1);
      return false;
    }
    return true;
  }

  /**
   * Save the document as a file.
   *
   * @return Document The document.
   */
  public function save() {
    $markdown = $this->metadataToMarkdown();
    $markdown = $markdown . $this->getMarkdown();

    $path = self::PATH . strtolower(get_class($this));

    $this->dir_exists($path);

    if(!file_put_contents($path . '/' . $this->filename, $markdown)) {
      throw new \Exception('Invalid file path or destination folder rights too restrictive.');
    }

    return $this;
  }

  /**
   * Delete the document file.
   *
   * @return Document The document.
   */
  public function delete() {
    $path = self::PATH . strtolower(get_class($this));
    if(!$this->dir_exists($path) || !unlink($path . '/' . $this->filename)) {
      throw new \Exception('Document not deleted.', 1);
    }
    return $this;
  }

  /**
   * Find all documents.
   *
   * @return array An array of documents.
   */
  public static function findAll() {
    $list = array();
    $path = self::PATH . strtolower(get_called_class());

    if(!file_exists($path)) {
      return $list;
    }

    if($dir = opendir($path)) {
      while (($entry = readdir($dir)) !== false) {
        if ($entry !== '.' && $entry !== '..') {
          try {
            $source = file_get_contents(self::PATH . strtolower(get_called_class()) . '/' . $entry);
            $parser = new DocumentParser();
            $document = static::fromFile($parser->parse($source), $entry);
            array_push($list, $document);
          } catch (Exception $e) {
            throw new \Exception('The file ' . $entry . 'can\'t be parsed.' . 
                                'It must have a metadata section', 1);
          }
        }
      }
      closedir($dir);
    }

    return $list;
  }

  /**
   * Create an document object from an existing file.
   *
   * @param  string $file Instance of a Document object.
   *
   * @return Document          A new document instance.
   */
  public static function fromFile($file, $filename) {
    $document = new static();
    $document->document = $file;
    $document->filename = $filename;
    return $document;
  }

  /**
   * Sort an array of MarkdownDocument objects by the given test.
   * This method implement a simple sort algorhythm and the given 
   * test must return TRUE to allow item permutation or FALSE to
   * disallow it.
   *
   * @param  callable $test A callable test.
   *
   * @param  array    $list Array of article objects.
   *
   * @throws Exception      If one of the item of the array is not an 
   *                        article object.
   *
   * @return array          The array sorted.
   */
  public static function sortBy($test, $list) {
    for($i = 0; $i < count($list); $i++) {
      $document = $list[$i];

      if($document instanceof static) {
        for($j = 0; $j < $i; $j++) {
          $currentDoc = $list[$j];

          if ($test($document, $currentDoc)) {
            $list[$j] = $document;
            $document = $currentDoc;
          }
        }
      } else {
        throw new \Exception('All item of the given array has to be an Article object.', 1);
      }

      $list[$i] = $document;
    }

    return $list;
  }
}