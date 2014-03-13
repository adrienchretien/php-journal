<?php

require_once 'models/MarkdownDocument.php';

  /**
   * Respresentation of an article written following Markdown notation.
   */
  class Article extends MarkdownDocument{

    public function isPrivate() {
      return $this->document->get('private') === 'true';
    }

    public function isUnfinished() {
      return $this->document->get('unfinished') === 'true';
    }

    /**
     * Set the document private metatdata value
     * @param boolean $isPrivate TRUE if the document is private of FALSE if 
     *                           it's not.
     */
    public function setPrivate($isPrivate) {
      $this->set('private', $isPrivate);
      return $this;
    }

    /**
     * Set the document unfinished metatdata value
     * @param boolean $isUnfinished TRUE if the document is unfinished of 
     *                              FALSE if it's not.
     */
    public function setUnfinished($isUnfinished) {
      $this->set('unfinished', $isUnfinished);
      return $this;
    }

    /**
     * Find the main title of the article from the given content.
     *
     * @throws Exception  If no title found.
     *
     * @return string     The title of the article.
     */
    private function findTitle($content = '') {
      if(preg_match('|^\#{1}[ ]*(.+)[ ]*\#*\n*|xm', $content, $matches)) {
        return preg_replace('|[\r\n]|', '', $matches[1]);
      } else {
        throw new Exception('No title found.');
      }
    }

    /**
     * Find an article by compare its metadata with the given value.
     *
     * @param  string  $key   The metadata valid keyword.
     *
     * @param  string  $value The value to compare.
     *
     * @return Article        The article found or NULL if not found.
     */
    public static function findByMeta($key, $value) {
      $list = Article::findAll();
      $article = null;

      foreach ($list as $item) {
        if($item->get($key) === $value) {
          $article = $item;
        }
      }

      return $article;
    }

    /**
     * Create an article object from its content.
     *
     * @param  string $content Content written in Markdown notation and must 
     *                         have a main title.
     *
     * @return Article         A new article instance.
     */
    public static function fromContent($content) {
      $article = new self();
      $date = new DateTime();

      $title = $article->findTitle($content);
      $slug = $article->formatToSlug($title);

      $article->document->setContent($content);
      $article->set('date', $date->format(DateTime::ATOM));
      $article->set('title', $title);
      $article->set('slug', $slug);
      $article->set('uri', $date->format('Y/m/d/') . $slug);

      $article->filename = $date->format('Y-m-d-') . $slug . '.md';

      return $article;
    }

    /**
     * Sort an array of article objects by date. Default sorting is ascendant.
     *
     * @param  array   $list      Array of article objects.
     *
     * @param  mixed[] $sortOrder Order used to sort the array. Either SORT_ASC 
     *                            to sort ascendingly or SORT_DESC to sort 
     *                            descendingly.
     *
     * @throws Exception          If one of the item of the array is not an 
     *                            article object.
     *
     * @return array              The array sorted.
     */
    public static function sortByDate($list, $sortOrder = null) {
      $ascendingTest = function ($a, $b) {
        return new DateTime($a->get('date')) < new DateTime($b->get('date'));
      };
      $descendingTest = function ($a, $b) {
        return new DateTime($a->get('date')) > new DateTime($b->get('date'));
      };

      $testToUse = $sortOrder === SORT_DESC ? $descendingTest : $ascendingTest;

      return self::sortBy($testToUse, $list);
    }
  }