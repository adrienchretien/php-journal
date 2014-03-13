<?php $this->layoutClass = 'one-column'; ?>
    <h1><?php echo $title; ?></h1>
<?php
if (count($articles) === 0) { ?>
    <p>Aucun article.</p>
<?php
} else {
  $formatter = new \IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::SHORT);
  $formatter->setPattern('dd MMMM Y');
?>
    <ul class="no-markers">
<?php
  foreach ($articles as $article) {
    $d = new \DateTime($article->get('date'));
    $date = $formatter->format($d);
?>
      <li><?php echo $date; ?> − <a href="/journal/<?php echo $article->get('uri'); ?>"><?php echo $article->get('title'); ?></a></li>
<?php
  }
?>
    </ul>
<?php
}
?>