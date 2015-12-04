<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php wp_title('&raquo;','true','right'); ?><?php bloginfo('name'); ?></title>
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/default.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
    <!-- Wordpress Generated Header (Weird Indents)-->
    <?php wp_head(); ?>
    <!-- END -->
  </head>
  <body>
    <header>
      <div class="wrapper">
      <h1><?php bloginfo('name'); ?></h1>
        <nav>
          <!-- Navigation (Weird Indents)-->
<?php wp_nav_menu( array( 'theme_location' => 'header-menu') ); echo "\n" ?>
          <!-- END -->
        </nav>
      </div>
    </header>
