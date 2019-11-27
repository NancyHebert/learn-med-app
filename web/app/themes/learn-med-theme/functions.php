<?php
/**
 * Roots includes
 */



require_once locate_template('/lib/utils.php');           // Utility functions
require_once locate_template('/lib/init.php');            // Initial theme setup and constants
require_once locate_template('/lib/wrapper.php');         // Theme wrapper class
require_once locate_template('/lib/sidebar.php');         // Sidebar class
require_once locate_template('/lib/config.php');          // Configuration
require_once locate_template('/lib/activation.php');      // Theme activation
require_once locate_template('/lib/titles.php');          // Page titles
require_once locate_template('/lib/cleanup.php');         // Cleanup
require_once locate_template('/lib/nav.php');             // Custom nav modifications
require_once locate_template('/lib/gallery.php');         // Custom [gallery] modifications
require_once locate_template('/lib/comments.php');        // Custom comments modifications
require_once locate_template('/lib/relative-urls.php');   // Root relative URLs
require_once locate_template('/lib/widgets.php');         // Sidebars and widgets
require_once locate_template('/lib/scripts.php');         // Scripts and stylesheets
require_once locate_template('/lib/custom.php');          // Custom functions



// add_action( 'gform_after_submission', 'set_post_content', 10, 2 );
// function set_post_content( $entry, $form ) {

//     //getting post
//     $post = get_post( $entry['post_id'] );
//     $user_id = get_current_user_id();
//     //$clinical = 'false';

//       //Work through each item in the field choices array
//       foreach($form["fields"][0]["choices"] as $fieldName  => $fieldVal) { 

//           var_dump('<pre>');
//             var_dump($fieldName);
//             var_dump($fieldVal);
//           var_dump('</pre>');


//           //find isSelected

//           //if isSelected variable is true
//             //set user meta to isClinical = true
//             //$is_clinical = 'true';
//             //add_user_meta( $user_id, 'isClinical', $is_clinical);
//           //else user meta to isClinical = false


//       }

//       return $form;

//       //check the index against a list that are NOT clinical

//     grassblade_debug('gform_after_submission: $entry $post');
//     grassblade_debug($entry);
//     //grassblade_debug('gform_after_submission: $form $post');
//     //grassblade_debug($form);

// }


// add_filter('body_class','add_category_to_single');
// function add_category_to_single($classes) {
//   if (post_type_exists( 'sfwd-courses' )  ) { //is_single() 
//     global $post;
//     foreach((get_the_category($post->ID)) as $category) {
//       echo $category->cat_name . ' ';
//       // add category slug to the $classes array
//       $classes[] = 'category-'.$category->slug;
//     }
//   }
//   // return the $classes array
//   return $classes;
// }


function enable_more_buttons($toolbars) {
  $toolbars[] = 'hr';
  $toolbars[] = 'sub';
  $toolbars[] = 'br';
  $toolbars[] = 'p';
  $toolbars[] = 'sup';
  $toolbars[] = 'fontselect';
  $toolbars[] = 'fontsizeselect';
  $toolbars[] = 'cleanup';
  $toolbars[] = 'styleselect';
  return $toolbars;
}
add_filter("mce_buttons_3", "enable_more_buttons");


if( !function_exists('_add_my_quicktags') ){
function _add_my_quicktags()
{ ?>

<script type="text/javascript">
QTags.addButton( 'p', 'p', '<p>', '</p>' );
QTags.addButton( 'br', 'br', '<br>', '<br>' );
QTags.addButton( 'h3', 'H3', '<h3>', '</h3>' );
QTags.addButton( 'h4', 'H4', '<h4>', '</h4>' );
</script>

<?php }
add_action('admin_print_footer_scripts', '_add_my_quicktags');
}

add_filter("jpeg_quality", create_function('', 'return 100;'));





function the_breadcrumb() {
    global $post;
    echo '<ul id="breadcrumbs">';
    if (!is_home()) {
        echo '<li><a href="';
        echo get_option('home');
        echo '">';
        echo 'Home';
        echo '</a></li><li class="separator">  </li>';
        if (is_category() || is_single()) {
            echo '<li>';
            the_category(' </li><li class="separator">  </li><li> ');
            if (is_single()) {
                echo '</li><li class="separator">  </li><li>';
                the_title();
                echo '</li>';
            }
        } elseif (is_page()) {
            if($post->post_parent){
                $anc = get_post_ancestors( $post->ID );
                $title = get_the_title();
                foreach ( $anc as $ancestor ) {
                    $output = '<li><a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a></li> <li class="separator">/</li>';
                }
                echo $output;
                echo '<strong title="'.$title.'"> '.$title.'</strong>';
            } else {
                echo '<li><strong> '.get_the_title().'</strong></li>';
            }
        }
    }
    elseif (is_tag()) {single_tag_title();}
    elseif (is_day()) {echo"<li>Archive for "; the_time('F jS, Y'); echo'</li>';}
    elseif (is_month()) {echo"<li>Archive for "; the_time('F, Y'); echo'</li>';}
    elseif (is_year()) {echo"<li>Archive for "; the_time('Y'); echo'</li>';}
    elseif (is_author()) {echo"<li>Author Archive"; echo'</li>';}
    elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<li>Blog Archives"; echo'</li>';}
    elseif (is_search()) {echo"<li>Search Results"; echo'</li>';}
    echo '</ul>';
}


//code to fix issue with Gravity form disappearing when using conditional logic.
// ADD JQUERY
function jquery_method() {
  wp_deregister_script( 'jquery' );
  wp_register_script(   'jquery'
      , 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', false, false);

  wp_enqueue_script('jquery');
}

if( !is_admin() ) {
  add_action('init', 'jquery_method');
}



//code to change the number of lines that display for the_excerpt(). Change this line to addmore characters $excerpt_length = 15;
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'custom_trim_excerpt');

function custom_trim_excerpt($text) { // Fakes an excerpt if needed

  global $post;

  if ( '' == $text ) {

    $text = get_the_content('');
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]>', $text);
    $text = strip_tags($text);
    $excerpt_length = 15;
    $words = explode(' ', $text, $excerpt_length + 1);
    if (count($words) > $excerpt_length) {
    array_pop($words);
    array_push($words, '...');
    $text = implode(' ', $words);
    }

  }

  return $text;
}





 function learndash_certificate_details_filter_new($post_id, $user_id = null) {
    $user_id = !empty($user_id)? $user_id:get_current_user_id();

    $certificateLink = '';
    $post = get_post($post_id);
    $meta = get_post_meta( $post_id, '_sfwd-course' );
    $course_progress = get_user_meta($user_id, '_sfwd-course_progress', true);


    if ( is_array( $meta ) && !empty( $meta ) ) {
      $meta = $meta[0];
      if ( is_array( $meta ) && ( !empty( $meta['_sfwd-course_progress'] ) ) )
        $certificate_post = $meta['sfwd-quiz_certificate'];

    }

    if ( !empty( $certificate_post ) )
      $certificateLink = get_permalink( $certificate_post );

    if ( !empty( $certificateLink ) )
    {
      $certificateLink .= (strpos("a".$certificateLink,"?"))? "&":"?";
      $certificateLink .= "course={$post->ID}&print=" . wp_create_nonce( $post->ID . $user_id);
    }
    return array('certificateLink' => $certificateLink);
    //, 'certificate_threshold' => $certificate_threshold

}

add_filter('learndash_certificate_details', 'learndash_certificate_details_filter',10,3);




function removeTitle($title) {
  $title = '';
  return $title;
}
add_filter("tml_title", "removeTitle");



?>
