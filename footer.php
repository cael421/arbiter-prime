    <footer>
      <div class="wrapper">
        <!--<h1><?php bloginfo('description'); ?></h1>-->
<?php if ( is_active_sidebar( 'footer' ) ) { ?>
          <div id="footer">
            <table>
              <tr>
                <!-- Widgets (Weird Indents)-->
<?php dynamic_sidebar( 'footer' ); ?>
                <!-- END -->
              </tr>
            </table>
          </div>
<?php } ?>
        <div id="copyright">
          <p>&copy; <?php bloginfo('name'); ?> 2015-2016</p>
        </div>
      </div>
    </footer>
<?php wp_footer(); ?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/footer.css">
  </body>
</html>
