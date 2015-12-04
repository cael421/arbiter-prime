<?php get_header(); ?>
    <section>
      <div class="wrapper">
<?php
if (get_theme_mod('sidebar_on_post_list')) {
add_sidebar(get_theme_mod('sidebar_on_post_list'));
}
?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article>
          <h1><a href=/?p=<?php the_ID(); ?>><?php the_title(); ?></a></h1>
          <small><?php the_date() ?>, <?php the_author() ?>:</small>
          <!-- Content (Weird Indents)-->
<?php the_content(__('(&#187;)')); ?>
          <!-- END -->
          <hr>
        </article>
<?php endwhile; else : ?>
<?php _e( 'Sorry, no posts matched your criteria.' ); ?>
<?php endif;?>
      </div>
    </section>
<?php get_footer(); ?>
