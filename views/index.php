<?php
  use Toolbox\Config;
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?php echo $title; ?></title>
  <script src="/scripts/scripts.js" async></script>
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Code+Pro|Source+Sans+Pro:400,700,400italic,700italic" type="text/css">
  <link rel="stylesheet" href="/styles/styles.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/styles/home.css" type="text/css" media="screen">
</head>
<body>
  <p class="spaced upper"><?php echo Config::get('site-name'); ?></p>
  <ul class="nav spaced-links no-markers" role="navigation">
    <li><a href="/journal">Journal</a></li>
  </ul>
</body>
</html>