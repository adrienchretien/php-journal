<h1><?php echo $title; ?></h1>
<?php
  if (count($articles) > 0) {
?>
<ul class="no-markers spaced-links">
  <?php foreach ($articles as $key => $article) { ?>
    <li class="element-item">
      <?php echo $article->get('title'); ?>
      <div class="f-right">
        <a href="/journal/<?php echo $article->get('uri'); ?>">Voir</a>
        <a href="/admin/journal/<?php echo $article->get('uri'); ?>/edit">Éditer</a>
        <a href="/admin/journal/<?php echo $article->get('uri'); ?>/delete">Supprimer</a>
      </div>
    </li>
  <?php } ?>
  </ul>
<?php
  } else {
?>
  <p>Aucun article.</p>
<?php
  }
?>