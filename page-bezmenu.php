<?php /* Template Name: Bez menu */ ?>
<?php get_header(); ?>
<?php #add_custom_page("testa1", "lalall"); ?>
    <section>
      <div class="wrapper">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div id="page">
          <!-- Content (Weird Indents)-->
<?php the_content(__('(&#187;)')); ?>
          <!-- END -->
        </div>
<?php endwhile; else : ?>
<?php _e( 'Sorry, no posts matched your criteria.' ); ?>
<?php endif;?>
      </div>
    </section>
<?php get_footer(); ?>
