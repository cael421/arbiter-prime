<?php
add_theme_support( 'post-thumbnails' );
//add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link' ) );
add_theme_support( 'post-formats', array( 'gallery' ) );

function list_type($category, $type) {
  echo "<h1>".get_cat_name(get_category_by_slug($category)->cat_ID).":</h1>\n";
  $files = new WP_Query( array ('category_name' => $category, 'post_type' => $type, 'order' => 'ASC', 'orderby' => 'title') );
  while ( $files->have_posts() ) {
  	$files->the_post();
  	echo "<p>".the_post_thumbnail()."<strong>".get_the_title() ."</strong>: ". get_the_content()."</p>\n";
  }
}

add_action( 'init', 'create_post_type' );
function create_post_type() {
  register_post_type( 'file',
    array(
      'labels' => array(
        'name' => __( 'Files' ),
        'singular_name' => __( 'File' )
      ),
			'supports' => array ('title', 'editor', 'thumbnail'),
      'public' => true,
      'has_archive' => true,
			'taxonomies' => array ('category'),
      'hierarchical' => true,
    )
  );
  add_post_type_support( 'page', 'post-formats' );
}


/////////////////////////////////////////////////////
/////////////////////////////////////////////////////

/* Create one or more meta boxes to be displayed on the post editor screen. */
function smashing_add_post_meta_boxes() {
  add_meta_box(
    'smashing-post-class',      // Unique ID
    esc_html__( 'Post Class', 'example' ),    // Title
    'smashing_post_class_meta_box',   // Callback function
    'abt_file',         // Admin page (or post type)
    'normal',         // Context
    'default'         // Priority
  );
}
/* Display the post meta box. */
function smashing_post_class_meta_box( $object, $box ) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'smashing_post_class_nonce' ); ?>
  <p>
    <label for="smashing-post-class"><?php _e( "Add a custom CSS class, which will be applied to WordPress' post class.", 'example' ); ?></label>
    <br />
    <input class="widefat" type="text" name="smashing-post-class" id="smashing-post-class" value="<?php echo esc_attr( get_post_meta( $object->ID, 'smashing_post_class', true ) ); ?>" size="30" />
  </p>
<?php }
/* Save the meta box's post metadata. */
function smashing_save_post_class_meta( $post_id, $post ) {
  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['smashing_post_class_nonce'] ) || !wp_verify_nonce( $_POST['smashing_post_class_nonce'], basename( __FILE__ ) ) )
    return $post_id;
  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );
  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;
  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = ( isset( $_POST['smashing-post-class'] ) ? sanitize_html_class( $_POST['smashing-post-class'] ) : '' );
  /* Get the meta key. */
  $meta_key = 'smashing_post_class';
  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );
  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );
  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );
  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );
}
//add_action( 'add_meta_boxes', 'smashing_add_post_meta_boxes' );
//add_action( 'save_post', 'smashing_save_post_class_meta', 10, 2 );

/////////////////////////////////////////////////////
/////////////////////////////////////////////////////

function add_sidebar($sidebar_side) {
	if ( is_active_sidebar( 'sidebar' ) ) {
		echo "<aside>";
		dynamic_sidebar( 'sidebar' );
		echo "</aside>";
		?>
		<style>
		aside {
			float: <?php echo $sidebar_side; ?>;
			width: 200px;
			overflow: hidden;
			padding: 0px 5px;
		}
		article,div#page {
			margin-<?php echo $sidebar_side; ?>: 220px;
		}
		</style>
		<?php
	};
}

function add_custom_page($title, $section_id) {
	$page_data = get_page_by_title( $title ); // By Name
	//$page_data = get_page( $page_title ); // By ID
	$content = $page_data->post_content;
	$title = $page_data->post_title;
	if ($title) {
	echo '<section id="' . $section_id . '">'."\n";
	echo '<div class="wrapper">'."\n";
	echo '<div class="page">'."\n";
	echo $content;
	echo "\n".'</div>'."\n";
	echo '</div>'."\n";
	echo '</section>'."\n";
	};
}

function arphabet_widgets_init() {
	register_sidebar( array(
		'name'          => 'Footer Widgets',
		'id'            => 'footer',
		'before_widget' => '<td id="widget">',
		'after_widget'  => "</td>\n"
	) );
		register_sidebar( array(
			'name'          => 'Sidebar',
			'id'            => 'sidebar',
			'before_widget' => '<div id="widget">',
			'after_widget'  => "</div>\n"
		) );
}
add_action( 'widgets_init', 'arphabet_widgets_init' );

function register_my_menus() {
	register_nav_menus(
	array(
		'header-menu' => __( 'Header Menu' )
		)
	);
}
add_action( 'init', 'register_my_menus' );

function custom_customize_register( $wp_customize )
{
	$wp_customize->add_section( 'sidebar' , array(
	    'title'      => __('Sidebar','sidebar'),
	    'priority'   => 30
	) );
	$wp_customize->add_section( 'header' , array(
	    'title'      => __('Header','header'),
	    'priority'   => 30
	) );
	$wp_customize->add_section( 'body' , array(
	    'title'      => __('Body','body'),
	    'priority'   => 30
	) );
	$wp_customize->add_section( 'content' , array(
	    'title'      => __('Content','content'),
	    'priority'   => 30
	) );
	$wp_customize->add_section( 'footer' , array(
	    'title'      => __('Footer','footer'),
	    'priority'   => 30
	) );
	$wp_customize->add_setting( 'body_background_image' , array(
		'default'     => '0',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'body_background_image', array(
	'label'        => __( 'Body Background Image', 'body_background_image' ),
	'section'    => 'body',
	'settings'   => 'body_background_image',
	) ) );
	$wp_customize->add_setting( 'body_background_color' , array(
		'default'     => '#000000',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'body_background_color', array(
	'label'        => __( 'Body Background Color', 'body_background_color' ),
	'section'    => 'body',
	'settings'   => 'body_background_color',
	) ) );
	$wp_customize->add_setting( 'header_background_image' , array(
		'default'     => '0',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'header_background_image', array(
	'label'        => __( 'Header Background Image', 'header_background_image' ),
	'section'    => 'header',
	'settings'   => 'header_background_image',
	) ) );
	$wp_customize->add_setting( 'header_background_color' , array(
		'default'     => '#000000',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_background_color', array(
	'label'        => __( 'Header Background Color', 'header_background_color' ),
	'section'    => 'header',
	'settings'   => 'header_background_color',
	) ) );
	$wp_customize->add_setting( 'header_text_color' , array(
		'default'     => '#FFFFFF',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_text_color', array(
	'label'        => __( 'Header Text Color', 'header_text_color' ),
	'section'    => 'header',
	'settings'   => 'header_text_color',
	) ) );
	$wp_customize->add_setting( 'header_link_color' , array(
		'default'     => '#52cfdd',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_link_color', array(
	'label'        => __( 'Header Link Color', 'header_link_color' ),
	'section'    => 'header',
	'settings'   => 'header_link_color',
	) ) );
	$wp_customize->add_setting( 'footer_background_image' , array(
		'default'     => '0',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'footer_background_image', array(
	'label'        => __( 'Footer Background Image', 'footer_background_image' ),
	'section'    => 'footer',
	'settings'   => 'footer_background_image',
	) ) );
	$wp_customize->add_setting( 'footer_background_color' , array(
		'default'     => '#000000',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_background_color', array(
	'label'        => __( 'Footer Background Color', 'footer_background_color' ),
	'section'    => 'footer',
	'settings'   => 'footer_background_color',
	) ) );
	$wp_customize->add_setting( 'footer_text_color' , array(
		'default'     => '#FFFFFF',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_text_color', array(
	'label'        => __( 'Footer Text Color', 'footer_text_color' ),
	'section'    => 'footer',
	'settings'   => 'footer_text_color',
	) ) );
	$wp_customize->add_setting( 'footer_link_color' , array(
		'default'     => '#52cfdd',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_link_color', array(
	'label'        => __( 'Footer Link Color', 'footer_link_color' ),
	'section'    => 'footer',
	'settings'   => 'footer_link_color',
	) ) );
	$wp_customize->add_setting( 'content_background_image' , array(
		'default'     => '0',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'content_background_image', array(
	'label'        => __( 'Content Background Image', 'content_background_image' ),
	'section'    => 'content',
	'settings'   => 'content_background_image',
	) ) );
	$wp_customize->add_setting( 'content_background_color' , array(
		'default'     => '#FFFFFF',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'content_background_color', array(
	'label'        => __( 'Content Background Color', 'content_background_color' ),
	'section'    => 'content',
	'settings'   => 'content_background_color',
	) ) );
	$wp_customize->add_setting( 'content_text_color' , array(
		'default'     => '#000000',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'content_text_color', array(
	'label'        => __( 'Content Text Color', 'content_text_color' ),
	'section'    => 'content',
	'settings'   => 'content_text_color',
	) ) );
	$wp_customize->add_setting( 'content_link_color' , array(
		'default'     => '#52cfdd',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'content_link_color', array(
	'label'        => __( 'Content Link Color', 'content_link_color' ),
	'section'    => 'content',
	'settings'   => 'content_link_color',
	) ) );
	$wp_customize->add_setting( 'sidebar_on_post_list' , array(
		'default'     => '0',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sidebar_on_post_list', array(
	'label'        => __( 'Sidebar on Post List', 'sidebar_on_post_list' ),
	'section'    => 'sidebar',
	'settings'   => 'sidebar_on_post_list',
	'type'           => 'radio',
	'choices'        => array(
			'0'   => __( 'None' ),
			'left'  => __( 'Left' ),
			'right'  => __( 'Right' )
	)
	) ) );
	$wp_customize->add_setting( 'sidebar_on_post' , array(
		'default'     => '0',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sidebar_on_post', array(
	'label'        => __( 'Sidebar on Post', 'sidebar_on_post' ),
	'section'    => 'sidebar',
	'settings'   => 'sidebar_on_post',
	'type'           => 'radio',
	'choices'        => array(
			'0'   => __( 'None' ),
			'left'  => __( 'Left' ),
			'right'  => __( 'Right' )
	)
	) ) );
	$wp_customize->add_setting( 'sidebar_on_page' , array(
		'default'     => '0',
		'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sidebar_on_page', array(
	'label'        => __( 'Sidebar on Page', 'sidebar_on_page' ),
	'section'    => 'sidebar',
	'settings'   => 'sidebar_on_page',
	'type'           => 'radio',
	'choices'        => array(
			'0'   => __( 'None' ),
			'left'  => __( 'Left' ),
			'right'  => __( 'Right' )
	)
	) ) );
}
add_action( 'customize_register', 'custom_customize_register' );

function custom_customize_css()
{
?>
	<style type="text/css">
	/* Custom Settings */
	body {
		background-image: url(<?php echo get_theme_mod('body_background_image', ''); ?>);
		background-color: <?php echo get_theme_mod('body_background_color', "#000000"); ?>;
	}
	header {
		background-image: url(<?php echo get_theme_mod('header_background_image', ''); ?>);
		background-color: <?php echo get_theme_mod('header_background_color', "#000000"); ?>;
		color: <?php echo get_theme_mod('header_text_color', "#FFFFFF"); ?>;
	}
	header nav li.current-menu-item a {
		background-color: <?php echo get_theme_mod('header_link_color', "#52cfdd"); ?>;
		color: <?php echo get_theme_mod('header_text_color', "#FFFFFF"); ?>;
	}
	header a {
		color: <?php echo get_theme_mod('header_link_color', "#52cfdd"); ?>;
	}
	footer {
		background-image: url(<?php echo get_theme_mod('footer_background_image', ''); ?>);
		background-color: <?php echo get_theme_mod('footer_background_color', "#000000"); ?>;
		color: <?php echo get_theme_mod('footer_text_color', "#FFFFFF"); ?>;
	}
	footer a {
		color: <?php echo get_theme_mod('footer_link_color', "#52cfdd"); ?>;
	}
	section {
		background-image: url(<?php echo get_theme_mod('content_background_image', ''); ?>);
		background-color: <?php echo get_theme_mod('content_background_color', "#FFFFFF"); ?>;
		color: <?php echo get_theme_mod('content_text_color', "#000000"); ?>;
	}
	section a {
		color: <?php echo get_theme_mod('content_link_color', "#52cfdd"); ?>;
	}
	td#widget input {
		display: block;
		color: <?php echo get_theme_mod('footer_text_color', "#FFFFFF"); ?>;
		background-color: <?php echo get_theme_mod('footer_background_color', "#000000"); ?>;
		border: 1px solid <?php echo get_theme_mod('footer_text_color', "#FFFFFF"); ?>;
		padding: 5px 10px;
		margin: 5px;
	}
	td#widget input#s {
	}
	td#widget input#searchsubmit {
		color: <?php echo get_theme_mod('footer_link_color', "#52cfdd"); ?>;
		text-decoration: underline;
		margin: 0px 0px 0px 0px;
		border: 0px;
		cursor: pointer;
	}
	td#widget input#searchsubmit:hover {
		text-decoration: none;
	}
	</style>
<?php
}
add_action( 'wp_head', 'custom_customize_css');
?>
