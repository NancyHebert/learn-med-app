<?php
/**
 * Custom functions
 */


function _category_id($category_slug) {
  return get_category(icl_object_id(get_category_by_slug($category_slug)->term_id, 'category', true))->cat_ID;
}


add_filter('roots_wrap_base', 'roots_wrap_base_cpts'); // Add our function to the roots_wrap_base filter

function roots_wrap_base_cpts($templates) {
    global $post, $wp_query;
    $post_slug=$post->post_name; 
    $cpt = get_post_type(); // Get the current post type
    if ($post_slug == 'login' || $post_slug == 'register'
        || $post_slug == 'connexion' || $post_slug == 'inscription'
        || $post_slug == 'lostpassword' || $post_slug == 'reinitaliser-mot-de-passe') {
       array_unshift($templates, 'base-login-register.php'); // Shift the template to the front of the array
    } elseif ($cpt == 'page' && in_category('residency', $post)) {
       array_unshift($templates, 'base-residency-page.php'); // Shift the template to the front of the array
    }  elseif ($cpt == 'page' && in_category('undergraduate-medical-education', $post)) {
       array_unshift($templates, 'base-undergrad-page.php'); // Shift the template to the front of the array
    }  elseif ($cpt == 'page' && in_category('global-health', $post)) {
       array_unshift($templates, 'base-gh-page.php'); // Shift the template to the front of the array
	}elseif ($cpt == 'sfwd-lessons' || $cpt == 'sfwd-topic' || $cpt == 'sfwd-quiz') {
       $course_id = learndash_get_course_id($post->ID);
       // if the course_id is null, it means the course has been disabled (made private or deleted)
       // if the course_id is not null, it might be that the lesson or topic or quiz isn't associated with any course
       if (is_null($course_id)) {
         return_410();
       } else {
         array_unshift($templates, 'base-inside-course.php'); // Shift the template to the front of the array
       }
    } elseif ($cpt == 'sfwd-courses') {
      if ($post->post_status != "publish") {
        return_410();
      } else {
        array_unshift($templates, 'base-' . $cpt . '.php'); // Shift the template to the front of the array
      }
    } elseif ($post_slug == 'references-utilisation-antibiotiques' ) {
       array_unshift($templates, 'base-login-references.php'); // Shift the template to the front of the array{
    } elseif ($cpt) {
       array_unshift($templates, 'base-' . $cpt . '.php'); // Shift the template to the front of the array
    }
    return $templates; // Return our modified array with base-$cpt.php at the front of the queue
}


add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() ) {
  $existing_mimes['vtt'] = 'text/vtt';
  $existing_mimes['pdf'] = 'application/pdf';
  $existing_mimes['doc'] = 'application/msword';
  $existing_mimes['docx'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
  return $existing_mimes;
}







// add_filter('tml_action_links','my_custom_tml_action_links_filter');
// function my_custom_tml_action_links_filter ( $action_links, $args )
// {


//     $args = wp_parse_args( $args, array(
//       'login'        => true,
//       'register'     => true,
//       'lostpassword' => true,
//       'connexion'        => true,
//       'inscription'     => true,
//       'reinitaliser-mot-de-passe' => true
//     ) );

//     $action_links = array();
//     if ( $args['login'] && $this->get_option( 'show_log_link' ) ) {
//       $action_links[] = array(
//         'title' => $this->get_title( 'login' ),
//         'url'   => $this->get_action_url( 'login' )
//       );
//     } else if ( $args['connexion'] && $this->get_option( 'show_log_link' ) ) {

//       $action_links[] = array(
//         'title' => $this->get_title( 'connexion' ),
//         'url'   => $this->get_action_url( 'connexion' )
//       );


//     }

//     if ( $args['register'] && $this->get_option( 'show_reg_link' ) && get_option( 'users_can_register' ) ) {
//       $action_links[] = array(
//         'title' => $this->get_title( 'register' ),
//         'url'   => $this->get_action_url( 'register' )
//       );
//     }else if ( $args['inscription'] && $this->get_option( 'show_reg_link' ) && get_option( 'users_can_register' ) ) {

//       $action_links[] = array(
//         'title' => $this->get_title( 'inscription' ),
//         'url'   => $this->get_action_url( 'inscription' )
//       );
//     }

//     if ( $args['lostpassword'] && $this->get_option( 'show_pass_link' ) ) {
//       $action_links[] = array(
//         'title' => $this->get_title( 'lostpassword' ),
//         'url'   => $this->get_action_url( 'lostpassword' )
//       );
//     } else  if ( $args['reinitaliser-mot-de-passe'] && $this->get_option( 'show_pass_link' ) )  {

//       $action_links[] = array(
//         'title' => $this->get_title( 'reinitaliser-mot-de-passe' ),
//         'url'   => $this->get_action_url( 'reinitaliser-mot-de-passe' )
//       );
//     }

//     return $action_links;
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


//list_course_category_nicenames($course_id),
//somewhere in custom.php, which echos a comma separated list of category nicenames.
//In the inside-course template, since you know what the $course_id is, in the body class, add
// list_course_category_nicenames($course_id);


function get_course_category_nicenames($course_id) {

  //if ( post_type_exists( 'sfwd-courses' ) OR post_type_exists( 'sfwd-lessons' ) OR post_type_exists( 'sfwd-topic' )   ) {

    global $post;
    foreach((get_the_category($course_id)) as $category) {
      // add category slug to the $classes array
      $classes[] = 'category-'.$category->category_nicename;
    }

    //return the $classes array
    return $classes;

 // }

}

// Get AD Attribute passed to function
// This function only looks up the equivilent ADI User Meta Key (adi_*) as
// defined in the user meta settings screen of the ADI Plugin.
//
// Usage [getadi attr='<attribute_name']
function get_adi_attribute( $atts ){
    $a = shortcode_atts( array(
        'attr' => 'cn',
    ), $atts );

    $user_id = get_current_user_id();
    $single = true;
    $key = adi_.$a['attr'];

    $value = get_user_meta( $user_id, $key, $single );

    return $value;
}
add_shortcode( 'getadi', 'get_adi_attribute' );








function get_dept_attribute( $atts ){

  $a = shortcode_atts( array(

      'attr' => '',

  ), $atts );



  $user_id = get_current_user_id();
  $single = true;
  $key = $a['attr'];

  $all_meta_for_user = get_user_meta( $user_id, $key, $single);

  return $all_meta_for_user;





}


add_shortcode( 'getdept', 'get_dept_attribute' );





function get_comp_attribute( $atts ){

  $a = shortcode_atts( array(

      'attr' => '',

  ), $atts );



  $user_id = get_current_user_id();
  $single = true;
  $key = $a['attr'];

  $all_meta_for_user = get_user_meta( $user_id, $key, $single);
  $hospital_usermeta = get_user_meta( $user_id, 'Hospital', $single);
  $other_company_usermeta = get_user_meta( $user_id, 'OtherCompany', $single);

  if ($hospital_usermeta == 'Other') {

    return $other_company_usermeta;

  } else {

    return $all_meta_for_user;
  }




}


add_shortcode( 'getcomp', 'get_comp_attribute' );


// add_action( 'gform_after_submission', 'set_post_content', 10, 2 );

// function set_post_content( $entry, $form ) {

//     //getting post
//     $post = get_post( $entry['post_id'] );

//     //changing post content
//     $post->post_content = 'Blender Version:' . rgar( $entry, '11' );

//     //updating post
//     wp_update_post( $post );
// }


//Add this to the page where certain users can skip (non-clinical) [skip_if_user_can][/skip_if_user_can]
// function name_of_role_or_capability( $atts, $content = null, $is_clinical_usermeta = '') {

//   $a = shortcode_atts( array(

//       'attr' => '',

//   ), $atts );

//   $user_id = get_current_user_id();
//   $single = true;
//   $key = $a['attr'];

//   $non_clinical_usermeta = get_user_meta( $user_id, 'DepartmentAtFacMed', $single);

//   $is_clinical_usermeta = get_user_meta( $user_id, 'departmentIsClinical', $single);

//   $content =  $is_clinical_usermeta;

//   $notice = __("Since you're a non-clinical faculty member, you can skip this step. Just click the Continue button below.", "learn.med" );


//   if ( $content == 'notClinical' ) {

//     return "<div class='note-step-optional'><script>var is_step_optional = 'notClinical';</script>
//     <p class='noticeOptional'>" . $notice . "</p></div>";

//   } else {

//     return "<div class='note-step-optional'><script>var is_step_optional = 'Clinical';</script></div>";

//   }


// }

// add_shortcode( 'skip_if_user_can', 'name_of_role_or_capability' );




function tml_registration_errors( $errors ) {
  if ( empty( $_POST['first_name'] ) )
    //$errors->add( '', _e('<div class="errors">' ) );
    $errors->add( 'empty_first_name',  __('<strong>ERROR</strong>: Please enter your first name.', 'theme-my-login' ) );
    //$errors->add( 'empty_first_name', ' <strong>ERROR</strong>: Please enter your first name.' );
  if ( empty( $_POST['last_name'] ) )
    $errors->add( 'empty_last_name',   __('<strong>ERROR</strong>: Please enter your last name.', 'theme-my-login' )   );
  //'<strong>ERROR</strong>: Please enter your last name.'


  // Check the e-mail address
    $user_email = $_POST['user_email'];
    if ( '' == $user_email )   {
      $errors->add( 'empty_email', __( '<strong>ERROR</strong>: Please type your e-mail address.', 'theme-my-login' )  );
    } elseif ( ! is_email( $user_email ) ) {
      $errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.', 'theme-my-login' ) );
      $user_email = '';
    } elseif ( email_exists( $user_email ) ) {
      $errors->add( 'email_exists', __( '<strong>ERROR</strong>: This email is already registered, please choose another one.', 'theme-my-login' ) );
    }

   // if ( empty( $_POST['address'] ) )
   //  $errors->add( 'empty_address', '<strong>ERROR</strong>: Please enter your address.' );

   // if ( empty( $_POST['city'] ) )
   //  $errors->add( 'empty_city', '<strong>ERROR</strong>: Please enter your city.' );

   // if ( ( !empty( $_POST['Country'] )  && $_POST['Country'] =="Canada" )  && empty( $_POST['province'] ) ) {

   //  $errors->add( 'empty_province', '<strong>ERROR</strong>: Please choose a province from the list.' );


   // }



   // if ( empty( $_POST['province'] ) )
   // $errors->add( 'empty_province', '<strong>ERROR</strong>: Please choose a province from the list.' );

    // if ( empty( $_POST['Country'] ) )
    // $errors->add( 'empty_country', '<strong>ERROR</strong>: Please choose a country from the list.' );

   //  if ( empty( $_POST['postal_code'] ) )
   //  $errors->add( 'empty_postal_code', '<strong>ERROR</strong>: Please enter your postal code.' );

  //if ( empty( $_POST['phone'] ) )
    //$errors->add( 'empty_phone',  __('<strong>ERROR</strong>: Please enter your primary telephone number.', 'theme-my-login' )  );
  //'<strong>ERROR</strong>: Please enter your primary telephone number.'

   if ( empty( $_POST['profession'] ) )
    $errors->add( 'empty_profession', __('<strong>ERROR</strong>: Please choose a profession from the list.', 'theme-my-login' ) );
  //'<strong>ERROR</strong>: Please choose a profession from the list.'

  //  if ( empty( $_POST['grad_year'] ) )
  //   $errors->add( 'empty_grad_year', '<strong>ERROR</strong>: Please add the year you obtained your degree.' );

  // if ( empty( $_POST['year_experience'] ) )
  //   $errors->add( 'empty_year_experience', '<strong>ERROR</strong>: Please add your years of experience.' );

   if ( empty( $_POST['job_title'] ) )
    $errors->add( 'empty_job_title',  __('<strong>ERROR</strong>: Please add your job title.', 'theme-my-login' ) );
  //'<strong>ERROR</strong>: Please add your job title.'
  //$errors->add( '', _e('</div>' ) );
  //if ( empty( $_POST['cpso'] ) )
   // $errors->add( 'empty_cpso', '<strong>ERROR</strong>: Please add your job title.' );
  return $errors;
}

add_filter( 'registration_errors', 'tml_registration_errors' );


function tml_user_register( $user_id ) {
  if ( !empty( $_POST['first_name'] ) )
    update_user_meta( $user_id, 'first_name', $_POST['first_name'] );
  if ( !empty( $_POST['last_name'] ) )
    update_user_meta( $user_id, 'last_name', $_POST['last_name'] );
 //  if ( !empty( $_POST['address'] ) )
 //    update_user_meta( $user_id, 'address', $_POST['address'] );
 //  if ( !empty( $_POST['city'] ) )
 //    update_user_meta( $user_id, 'city', $_POST['city'] );
// if ( !empty( $_POST['Country']) && $_POST['Country'] == "Canada" ) {
//   if ( !empty( $_POST['province'] ) ) {
//     update_user_meta( $user_id, 'province', $_POST['province'] );
//   }
// }
 // if ( !empty( $_POST['province'] ) )
 // update_user_meta( $user_id, 'province', $_POST['province'] );
  // if ( !empty( $_POST['Country'] ) )
  // update_user_meta( $user_id, 'Country', $_POST['Country'] );
 // if ( !empty( $_POST['postal_code'] ) )
 //  update_user_meta( $user_id, 'postal_code', $_POST['postal_code'] );
//if ( !empty( $_POST['phone'] ) )
  //update_user_meta( $user_id, 'phone', $_POST['phone'] );
//if ( !empty( $_POST['phone_other'] ) )
 // update_user_meta( $user_id, 'phone_other', $_POST['phone_other'] );
if ( !empty( $_POST['profession'] ) )
  update_user_meta( $user_id, 'profession', $_POST['profession'] );
// if ( !empty( $_POST['grad_year'] ) )
//   update_user_meta( $user_id, 'grad_year', $_POST['grad_year'] );
// if ( !empty( $_POST['year_experience'] ) )
//   update_user_meta( $user_id, 'year_experience', $_POST['year_experience'] );
if ( !empty( $_POST['job_title'] ) )
  update_user_meta( $user_id, 'job_title', $_POST['job_title'] );
//if ( !empty( $_POST['cpso'] ) )
 // update_user_meta( $user_id, 'cpso', $_POST['cpso'] );

}

add_action( 'user_register', 'tml_user_register' );

remove_filter( 'the_title', 'wptexturize' );

/* Hide the admin bar for all except admin users */

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
  if (!current_user_can('administrator') && !is_admin() && !current_user_can('view_reports')) {
    show_admin_bar(false);
  }
}

/* Check if the user can access the LearnDash lesson, topic, quiz or certificate */

function check_access_inside_learndash_course() {
  global $post;
  $cpt = get_post_type(); // Get the current post type

  if ( $cpt == 'sfwd-lessons' || $cpt == 'sfwd-topic' || $cpt == 'sfwd-quiz' || $cpt == 'sfwd-certificate' ) {

    if ( !is_user_logged_in() ) {
      auth_redirect();
      exit;
    }

    $roles = get_post_meta( learndash_get_course_id($post->ID), '_members_access_role', false );
    $user_has_access = false;

    if ( !empty( $roles ) && is_array( $roles ) ) {
      foreach( $roles as $role ) {
        if ( !is_feed() && ( current_user_can( $role ) || current_user_can( 'restrict_content' ) ) ) {
          $user_has_access = true;
          break;
        }
      }
    } else {
      $user_has_access = true;
    }

    if (!$user_has_access) {
      $redirect_url = get_permalink(learndash_get_course_id($post->ID));
      if($redirect_url) wp_redirect(clean_url($redirect_url), 301);
      get_header();
    }

  }

}

add_action( 'template_redirect', 'check_access_inside_learndash_course' );





/* Redirect to login if on restricted page */

function require_login_on_restricted_pages() {
  global $post;
  $cpt = get_post_type(); // Get the current post type

  if ( !is_user_logged_in() && ( $cpt == 'sfwd-courses' || $cpt == 'page' ) ) {

    $roles = get_post_meta( $post->ID, '_members_access_role', false );

    if ( !empty( $roles ) && is_array( $roles ) ) {
      auth_redirect();
    }

  }

}

add_action( 'template_redirect', 'require_login_on_restricted_pages' );

// Usage:
// get_id_by_slug('any-page-slug');
// See https://gist.github.com/davidpaulsson/9224518

function get_id_by_slug($slug, $post_type = "page") {
	$object = get_page_by_path($slug, OBJECT, $post_type);
	if ($object) {
		return $object->ID;
	} else {
		return null;
	}
}

// get a slug from an id
// See http://devsforrest.com/4/wordpress/wordpress-get-slug-by-id-function

function the_slug($id) {
  $post_data = get_post($id, ARRAY_A);
  $slug = $post_data['post_name'];
  return $slug;
}

// get the slug in the current language

function _slug($slug, $post_type = "page", $code = null ) {
  $id = get_id_by_slug($slug, $post_type);
  $id_in_current_lang = icl_object_id($id, $post_type, true, $code);
  $slug_in_current_lang = the_slug($id_in_current_lang);
  return $slug_in_current_lang;
}

function _category_slug($category_slug) {
  return get_category(icl_object_id(get_category_by_slug($category_slug)->term_id, 'category', true))->slug;
}


// override the quizinfo shortcode with our own to solve the time bug

require_once locate_template('/lib/custom_quizinfo_shortcode.php');

// Add a shortcode for generating storyline links with the status label
require_once locate_template('/lib/module_launch_with_status_label_shortcode.php');

// Add a shortcode for allowing to trigger completion of a learndash module preemptively
require_once locate_template('/lib/learndash_trigger_completion_shortcode.php');

// Add a shortcode for allowing to send completion of a learndash module to mcpost
require_once locate_template('/lib/mcpost_send_mistreatment_completion_shortcode.php');

// Add a shortcode for displaying a message that the visitor doesn't have the rights to view the page.
require_once locate_template('/lib/shortcode_display_access_required_message.php');

// Enable sending quiz-tool-lite responses to the LRS
require_once locate_template('/lib/xapi_quiz_tool_lite.php');

// Enable sending wp_pro_quiz responses to the LRS
require_once locate_template('/lib/xapi_wp_pro_quiz.php');


// Enable completing the lesson when we are on the last topic and redirecting to the next lesson or course description if on the last lesson and topics
require_once locate_template('/lib/learndash_custom.php');


add_shortcode( 'addCountries', 'countries_list_shortcode_handler' );

function countries_list_shortcode_handler( $atts, $content = null ) {
    $a = shortcode_atts( array(
      "GB" => "United Kingdom",
      "US" => "United States",
      "AF" => "Afghanistan",
      "AL" => "Albania",
      "DZ" => "Algeria",
      "AS" => "American Samoa",
      "AD" => "Andorra",
      "AO" => "Angola",
      "AI" => "Anguilla",
      "AQ" => "Antarctica",
      "AG" => "Antigua And Barbuda",
      "AR" => "Argentina",
      "AM" => "Armenia",
      "AW" => "Aruba",
      "AU" => "Australia",
      "AT" => "Austria",
      "AZ" => "Azerbaijan",
      "BS" => "Bahamas",
      "BH" => "Bahrain",
      "BD" => "Bangladesh",
      "BB" => "Barbados",
      "BY" => "Belarus",
      "BE" => "Belgium",
      "BZ" => "Belize",
      "BJ" => "Benin",
      "BM" => "Bermuda",
      "BT" => "Bhutan",
      "BO" => "Bolivia",
      "BA" => "Bosnia And Herzegowina",
      "BW" => "Botswana",
      "BV" => "Bouvet Island",
      "BR" => "Brazil",
      "IO" => "British Indian Ocean Territory",
      "BN" => "Brunei Darussalam",
      "BG" => "Bulgaria",
      "BF" => "Burkina Faso",
      "BI" => "Burundi",
      "KH" => "Cambodia",
      "CM" => "Cameroon",
      "CA" => "Canada",
      "CV" => "Cape Verde",
      "KY" => "Cayman Islands",
      "CF" => "Central African Republic",
      "TD" => "Chad",
      "CL" => "Chile",
      "CN" => "China",
      "CX" => "Christmas Island",
      "CC" => "Cocos (Keeling) Islands",
      "CO" => "Colombia",
      "KM" => "Comoros",
      "CG" => "Congo",
      "CD" => "Congo, The Democratic Republic Of The",
      "CK" => "Cook Islands",
      "CR" => "Costa Rica",
      "CI" => "Cote D'Ivoire",
      "HR" => "Croatia (Local Name: Hrvatska)",
      "CU" => "Cuba",
      "CY" => "Cyprus",
      "CZ" => "Czech Republic",
      "DK" => "Denmark",
      "DJ" => "Djibouti",
      "DM" => "Dominica",
      "DO" => "Dominican Republic",
      "TP" => "East Timor",
      "EC" => "Ecuador",
      "EG" => "Egypt",
      "SV" => "El Salvador",
      "GQ" => "Equatorial Guinea",
      "ER" => "Eritrea",
      "EE" => "Estonia",
      "ET" => "Ethiopia",
      "FK" => "Falkland Islands (Malvinas)",
      "FO" => "Faroe Islands",
      "FJ" => "Fiji",
      "FI" => "Finland",
      "FR" => "France",
      "FX" => "France, Metropolitan",
      "GF" => "French Guiana",
      "PF" => "French Polynesia",
      "TF" => "French Southern Territories",
      "GA" => "Gabon",
      "GM" => "Gambia",
      "GE" => "Georgia",
      "DE" => "Germany",
      "GH" => "Ghana",
      "GI" => "Gibraltar",
      "GR" => "Greece",
      "GL" => "Greenland",
      "GD" => "Grenada",
      "GP" => "Guadeloupe",
      "GU" => "Guam",
      "GT" => "Guatemala",
      "GN" => "Guinea",
      "GW" => "Guinea-Bissau",
      "GY" => "Guyana",
      "HT" => "Haiti",
      "HM" => "Heard And Mc Donald Islands",
      "VA" => "Holy See (Vatican City State)",
      "HN" => "Honduras",
      "HK" => "Hong Kong",
      "HU" => "Hungary",
      "IS" => "Iceland",
      "IN" => "India",
      "ID" => "Indonesia",
      "IR" => "Iran (Islamic Republic Of)",
      "IQ" => "Iraq",
      "IE" => "Ireland",
      "IL" => "Israel",
      "IT" => "Italy",
      "JM" => "Jamaica",
      "JP" => "Japan",
      "JO" => "Jordan",
      "KZ" => "Kazakhstan",
      "KE" => "Kenya",
      "KI" => "Kiribati",
      "KP" => "Korea, Democratic People's Republic Of",
      "KR" => "Korea, Republic Of",
      "KW" => "Kuwait",
      "KG" => "Kyrgyzstan",
      "LA" => "Lao People's Democratic Republic",
      "LV" => "Latvia",
      "LB" => "Lebanon",
      "LS" => "Lesotho",
      "LR" => "Liberia",
      "LY" => "Libyan Arab Jamahiriya",
      "LI" => "Liechtenstein",
      "LT" => "Lithuania",
      "LU" => "Luxembourg",
      "MO" => "Macau",
      "MK" => "Macedonia, Former Yugoslav Republic Of",
      "MG" => "Madagascar",
      "MW" => "Malawi",
      "MY" => "Malaysia",
      "MV" => "Maldives",
      "ML" => "Mali",
      "MT" => "Malta",
      "MH" => "Marshall Islands",
      "MQ" => "Martinique",
      "MR" => "Mauritania",
      "MU" => "Mauritius",
      "YT" => "Mayotte",
      "MX" => "Mexico",
      "FM" => "Micronesia, Federated States Of",
      "MD" => "Moldova, Republic Of",
      "MC" => "Monaco",
      "MN" => "Mongolia",
      "MS" => "Montserrat",
      "MA" => "Morocco",
      "MZ" => "Mozambique",
      "MM" => "Myanmar",
      "NA" => "Namibia",
      "NR" => "Nauru",
      "NP" => "Nepal",
      "NL" => "Netherlands",
      "AN" => "Netherlands Antilles",
      "NC" => "New Caledonia",
      "NZ" => "New Zealand",
      "NI" => "Nicaragua",
      "NE" => "Niger",
      "NG" => "Nigeria",
      "NU" => "Niue",
      "NF" => "Norfolk Island",
      "MP" => "Northern Mariana Islands",
      "NO" => "Norway",
      "OM" => "Oman",
      "PK" => "Pakistan",
      "PW" => "Palau",
      "PA" => "Panama",
      "PG" => "Papua New Guinea",
      "PY" => "Paraguay",
      "PE" => "Peru",
      "PH" => "Philippines",
      "PN" => "Pitcairn",
      "PL" => "Poland",
      "PT" => "Portugal",
      "PR" => "Puerto Rico",
      "QA" => "Qatar",
      "RE" => "Reunion",
      "RO" => "Romania",
      "RU" => "Russian Federation",
      "RW" => "Rwanda",
      "KN" => "Saint Kitts And Nevis",
      "LC" => "Saint Lucia",
      "VC" => "Saint Vincent And The Grenadines",
      "WS" => "Samoa",
      "SM" => "San Marino",
      "ST" => "Sao Tome And Principe",
      "SA" => "Saudi Arabia",
      "SN" => "Senegal",
      "SC" => "Seychelles",
      "SL" => "Sierra Leone",
      "SG" => "Singapore",
      "SK" => "Slovakia (Slovak Republic)",
      "SI" => "Slovenia",
      "SB" => "Solomon Islands",
      "SO" => "Somalia",
      "ZA" => "South Africa",
      "GS" => "South Georgia, South Sandwich Islands",
      "ES" => "Spain",
      "LK" => "Sri Lanka",
      "SH" => "St. Helena",
      "PM" => "St. Pierre And Miquelon",
      "SD" => "Sudan",
      "SR" => "Suriname",
      "SJ" => "Svalbard And Jan Mayen Islands",
      "SZ" => "Swaziland",
      "SE" => "Sweden",
      "CH" => "Switzerland",
      "SY" => "Syrian Arab Republic",
      "TW" => "Taiwan",
      "TJ" => "Tajikistan",
      "TZ" => "Tanzania, United Republic Of",
      "TH" => "Thailand",
      "TG" => "Togo",
      "TK" => "Tokelau",
      "TO" => "Tonga",
      "TT" => "Trinidad And Tobago",
      "TN" => "Tunisia",
      "TR" => "Turkey",
      "TM" => "Turkmenistan",
      "TC" => "Turks And Caicos Islands",
      "TV" => "Tuvalu",
      "UG" => "Uganda",
      "UA" => "Ukraine",
      "AE" => "United Arab Emirates",
      "UM" => "United States Minor Outlying Islands",
      "UY" => "Uruguay",
      "UZ" => "Uzbekistan",
      "VU" => "Vanuatu",
      "VE" => "Venezuela",
      "VN" => "Viet Nam",
      "VG" => "Virgin Islands (British)",
      "VI" => "Virgin Islands (U.S.)",
      "WF" => "Wallis And Futuna Islands",
      "EH" => "Western Sahara",
      "YE" => "Yemen",
      "YU" => "Yugoslavia",
      "ZM" => "Zambia",
      "ZW" => "Zimbabwe"

    ), $atts );
}


// Override Storyline launch page
require_once locate_template('/lib/storyline-override-launch-html.php');


add_filter('esu_add_extra_form_fields_after','new_add_country_select');

function new_add_country_select(){
    $_countries = array(
      "GB" => "United Kingdom",
      "US" => "United States",
      "AF" => "Afghanistan",
      "AL" => "Albania",
      "DZ" => "Algeria",
      "AS" => "American Samoa",
      "AD" => "Andorra",
      "AO" => "Angola",
      "AI" => "Anguilla",
      "AQ" => "Antarctica",
      "AG" => "Antigua And Barbuda",
      "AR" => "Argentina",
      "AM" => "Armenia",
      "AW" => "Aruba",
      "AU" => "Australia",
      "AT" => "Austria",
      "AZ" => "Azerbaijan",
      "BS" => "Bahamas",
      "BH" => "Bahrain",
      "BD" => "Bangladesh",
      "BB" => "Barbados",
      "BY" => "Belarus",
      "BE" => "Belgium",
      "BZ" => "Belize",
      "BJ" => "Benin",
      "BM" => "Bermuda",
      "BT" => "Bhutan",
      "BO" => "Bolivia",
      "BA" => "Bosnia And Herzegowina",
      "BW" => "Botswana",
      "BV" => "Bouvet Island",
      "BR" => "Brazil",
      "IO" => "British Indian Ocean Territory",
      "BN" => "Brunei Darussalam",
      "BG" => "Bulgaria",
      "BF" => "Burkina Faso",
      "BI" => "Burundi",
      "KH" => "Cambodia",
      "CM" => "Cameroon",
      "CA" => "Canada",
      "CV" => "Cape Verde",
      "KY" => "Cayman Islands",
      "CF" => "Central African Republic",
      "TD" => "Chad",
      "CL" => "Chile",
      "CN" => "China",
      "CX" => "Christmas Island",
      "CC" => "Cocos (Keeling) Islands",
      "CO" => "Colombia",
      "KM" => "Comoros",
      "CG" => "Congo",
      "CD" => "Congo, The Democratic Republic Of The",
      "CK" => "Cook Islands",
      "CR" => "Costa Rica",
      "CI" => "Cote D'Ivoire",
      "HR" => "Croatia (Local Name: Hrvatska)",
      "CU" => "Cuba",
      "CY" => "Cyprus",
      "CZ" => "Czech Republic",
      "DK" => "Denmark",
      "DJ" => "Djibouti",
      "DM" => "Dominica",
      "DO" => "Dominican Republic",
      "TP" => "East Timor",
      "EC" => "Ecuador",
      "EG" => "Egypt",
      "SV" => "El Salvador",
      "GQ" => "Equatorial Guinea",
      "ER" => "Eritrea",
      "EE" => "Estonia",
      "ET" => "Ethiopia",
      "FK" => "Falkland Islands (Malvinas)",
      "FO" => "Faroe Islands",
      "FJ" => "Fiji",
      "FI" => "Finland",
      "FR" => "France",
      "FX" => "France, Metropolitan",
      "GF" => "French Guiana",
      "PF" => "French Polynesia",
      "TF" => "French Southern Territories",
      "GA" => "Gabon",
      "GM" => "Gambia",
      "GE" => "Georgia",
      "DE" => "Germany",
      "GH" => "Ghana",
      "GI" => "Gibraltar",
      "GR" => "Greece",
      "GL" => "Greenland",
      "GD" => "Grenada",
      "GP" => "Guadeloupe",
      "GU" => "Guam",
      "GT" => "Guatemala",
      "GN" => "Guinea",
      "GW" => "Guinea-Bissau",
      "GY" => "Guyana",
      "HT" => "Haiti",
      "HM" => "Heard And Mc Donald Islands",
      "VA" => "Holy See (Vatican City State)",
      "HN" => "Honduras",
      "HK" => "Hong Kong",
      "HU" => "Hungary",
      "IS" => "Iceland",
      "IN" => "India",
      "ID" => "Indonesia",
      "IR" => "Iran (Islamic Republic Of)",
      "IQ" => "Iraq",
      "IE" => "Ireland",
      "IL" => "Israel",
      "IT" => "Italy",
      "JM" => "Jamaica",
      "JP" => "Japan",
      "JO" => "Jordan",
      "KZ" => "Kazakhstan",
      "KE" => "Kenya",
      "KI" => "Kiribati",
      "KP" => "Korea, Democratic People's Republic Of",
      "KR" => "Korea, Republic Of",
      "KW" => "Kuwait",
      "KG" => "Kyrgyzstan",
      "LA" => "Lao People's Democratic Republic",
      "LV" => "Latvia",
      "LB" => "Lebanon",
      "LS" => "Lesotho",
      "LR" => "Liberia",
      "LY" => "Libyan Arab Jamahiriya",
      "LI" => "Liechtenstein",
      "LT" => "Lithuania",
      "LU" => "Luxembourg",
      "MO" => "Macau",
      "MK" => "Macedonia, Former Yugoslav Republic Of",
      "MG" => "Madagascar",
      "MW" => "Malawi",
      "MY" => "Malaysia",
      "MV" => "Maldives",
      "ML" => "Mali",
      "MT" => "Malta",
      "MH" => "Marshall Islands",
      "MQ" => "Martinique",
      "MR" => "Mauritania",
      "MU" => "Mauritius",
      "YT" => "Mayotte",
      "MX" => "Mexico",
      "FM" => "Micronesia, Federated States Of",
      "MD" => "Moldova, Republic Of",
      "MC" => "Monaco",
      "MN" => "Mongolia",
      "MS" => "Montserrat",
      "MA" => "Morocco",
      "MZ" => "Mozambique",
      "MM" => "Myanmar",
      "NA" => "Namibia",
      "NR" => "Nauru",
      "NP" => "Nepal",
      "NL" => "Netherlands",
      "AN" => "Netherlands Antilles",
      "NC" => "New Caledonia",
      "NZ" => "New Zealand",
      "NI" => "Nicaragua",
      "NE" => "Niger",
      "NG" => "Nigeria",
      "NU" => "Niue",
      "NF" => "Norfolk Island",
      "MP" => "Northern Mariana Islands",
      "NO" => "Norway",
      "OM" => "Oman",
      "PK" => "Pakistan",
      "PW" => "Palau",
      "PA" => "Panama",
      "PG" => "Papua New Guinea",
      "PY" => "Paraguay",
      "PE" => "Peru",
      "PH" => "Philippines",
      "PN" => "Pitcairn",
      "PL" => "Poland",
      "PT" => "Portugal",
      "PR" => "Puerto Rico",
      "QA" => "Qatar",
      "RE" => "Reunion",
      "RO" => "Romania",
      "RU" => "Russian Federation",
      "RW" => "Rwanda",
      "KN" => "Saint Kitts And Nevis",
      "LC" => "Saint Lucia",
      "VC" => "Saint Vincent And The Grenadines",
      "WS" => "Samoa",
      "SM" => "San Marino",
      "ST" => "Sao Tome And Principe",
      "SA" => "Saudi Arabia",
      "SN" => "Senegal",
      "SC" => "Seychelles",
      "SL" => "Sierra Leone",
      "SG" => "Singapore",
      "SK" => "Slovakia (Slovak Republic)",
      "SI" => "Slovenia",
      "SB" => "Solomon Islands",
      "SO" => "Somalia",
      "ZA" => "South Africa",
      "GS" => "South Georgia, South Sandwich Islands",
      "ES" => "Spain",
      "LK" => "Sri Lanka",
      "SH" => "St. Helena",
      "PM" => "St. Pierre And Miquelon",
      "SD" => "Sudan",
      "SR" => "Suriname",
      "SJ" => "Svalbard And Jan Mayen Islands",
      "SZ" => "Swaziland",
      "SE" => "Sweden",
      "CH" => "Switzerland",
      "SY" => "Syrian Arab Republic",
      "TW" => "Taiwan",
      "TJ" => "Tajikistan",
      "TZ" => "Tanzania, United Republic Of",
      "TH" => "Thailand",
      "TG" => "Togo",
      "TK" => "Tokelau",
      "TO" => "Tonga",
      "TT" => "Trinidad And Tobago",
      "TN" => "Tunisia",
      "TR" => "Turkey",
      "TM" => "Turkmenistan",
      "TC" => "Turks And Caicos Islands",
      "TV" => "Tuvalu",
      "UG" => "Uganda",
      "UA" => "Ukraine",
      "AE" => "United Arab Emirates",
      "UM" => "United States Minor Outlying Islands",
      "UY" => "Uruguay",
      "UZ" => "Uzbekistan",
      "VU" => "Vanuatu",
      "VE" => "Venezuela",
      "VN" => "Viet Nam",
      "VG" => "Virgin Islands (British)",
      "VI" => "Virgin Islands (U.S.)",
      "WF" => "Wallis And Futuna Islands",
      "EH" => "Western Sahara",
      "YE" => "Yemen",
      "YU" => "Yugoslavia",
      "ZM" => "Zambia",
      "ZW" => "Zimbabwe"
    );

   /* Default country is CA (Canada) set as an example */
   $formArray['countries_list'] = array(
      'name'    => __('country', 'my_lang'),
      'validate'=> 'esu-required',
      'id'      => 'countries_list',
      'class'   => 'esu-country-select',
      'type'    => 'select',
      'options' => $_countries,
      'default' => 'CA'
    );
    return array( $formArray['countries_list'] );
    }

//function my_tml_email_login( $tml ) {

  //if ( 'register' == $tml->request_action ) {
    //if ( 'POST' == $_SERVER['REQUEST_METHOD'] )
     // $_POST['user_login'] = $_POST['user_email'];
  //}
//}
//add_action( 'tml_request', 'my_tml_email_login' );


// generate login
function my_tml_email_login( $tml ) {

  if ( 'register' == $tml->request_action ) {
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] )
      $login_rand = generate_rand_username($_POST['user_email'], $_POST['first_name'], $_POST['last_name']);
      $_POST['user_login'] = $login_rand ;
  }
}
add_action( 'tml_request', 'my_tml_email_login' );

function generate_rand_username($email, $first_name, $last_name) {
  return md5($email.date("YmdHis").$first_name.$last_name);
}

/////// in the login and register Redirect the user to home page if is logged.

add_action('wp','login_redirect');

function login_redirect(){
  global $post;
  $post_slug=$post->post_name;
  if ($post_slug == 'login' || $post_slug == 'register'
        || $post_slug == 'connexion' || $post_slug == 'inscription')  {
    if (is_user_logged_in() ) {
      $requested_redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
      if ('' != $requested_redirect_to) {
        wp_redirect ( $requested_redirect_to );
        exit;
      } else {
        wp_redirect ( home_url("/") );
        exit;
      }
    }
  }
}

////// override learndash redirection if the user is inside the cours and not logged  //////  origin function  sfwd_lms.php ligne 2566

function learndash_process_course_join_custom(){
    if(!isset($_POST['course_join']) || !isset($_POST['course_id']))
      return;

    $user_id = get_current_user_id();
    if(empty($user_id)) {

      /////  ORIGINE REDIRECTION wp_redirect(wp_login_url()); sfwd_lms.php ligne 2572

        $mylocale = get_bloginfo('language');
        if($mylocale == 'en-US') {
            wp_redirect('/login/');
        }
        elseif($mylocale == 'fr-FR'){
          wp_redirect('/connexion/');
        }
      exit;
    }

    $course_id = $_POST['course_id'];
    $meta = get_post_meta( $course_id, '_sfwd-courses', true );

    if(!empty($meta['sfwd-courses_course_join']) && empty($meta['sfwd-courses_course_price']))
      ld_update_course_access($user_id, $course_id);
  }

  function override_learndash_process_course_join() {

   remove_action("wp", "learndash_process_course_join");

   add_action("wp", "learndash_process_course_join_custom");
}

add_action( 'after_setup_theme', 'override_learndash_process_course_join' );


//[launchPage courslink="https://learn.med.uottawa.ca/orientation-new-faculty/"]
//[launchPage courslink="https://apprendre.med.uottawa.ca/programme-dorientation-des-nouveaux-membres-du-corps-professoral/"]

function add_launch_page_link_shortcode( $atts ) {

   extract( shortcode_atts(
    array(
      'courslink' => '',
    ), $atts )
  );

  $v1 = $courslink;

  $launchLink = $v1 ;

  //return '<a class="btn btnLaunchLink" target="_blank" href="'.$launchLink.'"><span class="fa fa-chevron-left"></span> '.__("Return to the orientation program requirements page", "learndash").' </a>';

  return '<a class="theLaunch" target="_blank" href="'.$launchLink.'"><span class="fa fa-chevron-left"></span> <span class="homebtn-label">'.__("Return to the orientation program requirements page", "learndash").'</span></a> ';

}

add_shortcode( 'launchPage', 'add_launch_page_link_shortcode' );


//[surveyPopup]
function add_survey_popup_shortcode( ) {

  global $post;
  global $current_user;


  $user = $current_user;
  $user_login = $current_user->user_login;
  $display_name = $current_user->display_name;
  $user_email = $current_user->user_email;
  $course_id = learndash_get_course_id($post->ID);
  $course_status = learndash_course_status($course_id, null);

  $lessons = learndash_get_lesson_list($course_id);
  $course_link = get_permalink($course_id);


  // $pattern = '/^https://apprendre.med.uottawa.dev/cours/';
  // preg_match($pattern, $course_link, $matches, PREG_OFFSET_CAPTURE, 3);



  //$course_title =  preg_match("/^https:\//\apprendre.med.uottawa.dev\/cours\/\//i", $course_link);






  // var_dump('<pre>');
  // //var_dump($lessons);
  // var_dump($course_title);
  // // var_dump('</pre>');
  // // var_dump('<pre>');
  // // var_dump('course id');
  // // var_dump($course_id);
  // // var_dump('course status');
  // // var_dump($course_status);
  // var_dump('</pre>');

  @http_response_code($status_code);
  @header('Content-Type: application/json; charset=' . get_option('blog_charset'), true);
     echo "<script>var course_info = " . json_encode(array('courseID' => $course_id, 'courseStatus'=>$course_status, 'userLogin' => $user_login, 'userEmail' => $user_email, 'courseLink' => $course_link), JSON_UNESCAPED_UNICODE) . ";</script>";


  return '<div id="surveyPopupModal" class="modal fade" role="dialog"> <div class="modal-dialog">
  <div class="modal-content">  <div class="modal-header"><button type="button" class="btn btn-default pull-right" role="button"  data-dismiss="modal"><span class="fa fa-times"></span> ' .__("Close", "learndash"). ' </button><h4 class="modal-title"></h4>
  </div> <div class="modal-body"> <h1 role="heading" aria-level="1">' .__("Leaving so soon?", "learndash"). '</h1>
  <p class="lead">' .__("We noticed you have not completed the module.", "learndash"). ' <strong>' .__("If you did not mean to leave,", "learndash"). '
  <a href="#" class="return" data-dismiss="modal"><span class="fontAwesomeIcons fa fa-undo"></span>' .__("return to the module", "learndash"). '</a>.</strong></p>
  <div id="notice" > <h2 role="heading" aria-level="2">' .__("Before you leave", "learndash"). ' </h2>
  <p class="lead">' .__("<strong>We</strong> would like to <strong>suggest</strong> taking a few minutes <strong>to fill out a quick survey</strong> to help improve the learning platform.", 'learn.med') . ' <strong>' .__("Your feedback is important to us.", "learndash"). '</strong></p>
  <p class="centered row"> <button id="btn-survey" class="btn btn-red btn-lg col-xs-12">
  <span>' .__("Sure, I would like to help", "learndash"). '</span> <br>' .__("Take me to the survey", "learndash"). ' </button> </p>
  </div> </div>
  <div class="modal-footer"> <a id="btn-notInterested" role="link" ><span>' .__("I am not interested.", "learndash"). '</span> ' .__("Do not ask me again.", "learndash"). '</a> <!--<button type="button" class="btn btn-default" role="button"  data-dismiss="modal">
  <span class="fa fa-times"></span> ' .__("Close", "learndash"). '</button>--> </div> </div> </div> </div>';


}

add_shortcode( 'surveyPopup', 'add_survey_popup_shortcode' );




///// generate certificats shortcode  with 2 Attributes courslink  and  quizid .
////  The certificat is generated with the function  learndash_certificate_details  2419 - sfwd_lms.php
// In local test : [certificats courslink="https://learn.med.uottawa.dev/certificates/preventing-diabetic-retinopathy-eye-care-patients-diabetes/" quizid="921"].






function custom_certificat_shortcode( $atts ) {

  // Attributes



  extract( shortcode_atts(
    array(
      'courslink' => '',
      'quizid' => '',
    ), $atts )
  );
$user_id = get_current_user_id();


$v1 =  $courslink."?quiz=".$quizid."&print=" ;






$v1 =  $courslink."?quiz=" .$quizid."&print=" ;

$v2 =  wp_create_nonce( $quizid . $user_id);


$certificateLink = $v1.$v2 ;

  // return Code

return '<a class="btn btnCertificate" target="_blank" href="'.$certificateLink.'"><span class="fa fa-certificate"></span> '.__("Print your certificate!", "learndash").' </a>';


}

add_shortcode( 'certificats', 'custom_certificat_shortcode' );


//[set sesssion completed link="" completed=""].

// function set_session_completed( $link, $completed ){

//   extract( shortcode_atts(
//     array(
//       'link' => '',
//       'completed' => '',
//     ), $atts )
//   );






// }

// add_shortcode( 'setCompleted', 'set_session_completed' );





//[feedbackBox]
function custom_feedback_box( ) {

// return Code

return '<textarea rows="4" style="width: 98%" id="feedback" placeholder="Write your feedback here"></textarea>';


}

add_shortcode( 'feedbackBox', 'custom_feedback_box' );





// grassblade_debug('grassblade_learndash_course_completed');
//   //grassblade_debug($data);

//   $grassblade_tincan_endpoint = get_option( 'grassblade_tincan_endpoint' );
//   $grassblade_tincan_user = get_option('grassblade_tincan_user');
//   $grassblade_tincan_password = get_option('grassblade_tincan_password');
//   $grassblade_tincan_track_guest = get_option('grassblade_tincan_track_guest');
//   $xapi = new NSS_XAPI($grassblade_tincan_endpoint, $grassblade_tincan_user, $grassblade_tincan_password);
//   $actor = grassblade_getactor($grassblade_tincan_track_guest);

//   if(empty($actor))
//   {
//     grassblade_debug("No Actor. Shutting Down.");
//     return;
//   }
//   $course = $data['course'];
//   $progress = $data['progress'];
//   $course_title = $course->post_title;

//   //Lesson Completed
//   $xapi->set_verb('completed');
//   $xapi->set_actor_by_object($actor);
//   $xapi->set_parent($course_url, $course_title, $course_title, 'http://adlnet.gov/expapi/activities/course','Activity');
//   $xapi->set_grouping($course_url, $course_title, $course_title, 'http://adlnet.gov/expapi/activities/course','Activity');
//   $xapi->set_object($course_url, $course_title, $course_title, 'http://adlnet.gov/expapi/activities/course','Activity');
//   $result = array(
//         'completion' => true
//         );
//   $xapi->set_result_by_object($result);
//   $statement = $xapi->build_statement();
//   grassblade_debug($statement);
//   $xapi->new_statement();
//   foreach($xapi->statements as $statement)
//   {
//     $ret = $xapi->SendStatements(array($statement));
//   }






// function learndash_generate_certificate_course_completed($atts){
//   extract(shortcode_atts(array(
//   'course_id' => 0,
//   'user_id' => 0,
//'course_progress' => "",
//   'array' => false
//   ), $atts));

//   if(empty($user_id))
//   {
//     $current_user = wp_get_current_user();
//     $user_id = $current_user->ID;
//   }

//   if(empty($course_id))
//   $course_id = learndash_get_course_id();

//   if(empty($course_id))
//   return "";

//   $course_progress = get_user_meta($user_id, '_sfwd-course_progress', true);

//   $percentage = 0;
//   $message = '';

//   if(!empty($course_progress) && !empty($course_progress[$course_id]) && !empty($course_progress[$course_id]['total']))
//   {
//     $completed = intVal($course_progress[$course_id]['completed']);
//     $total = intVal($course_progress[$course_id]['total']);

//     if($completed == $total - 1)
//     {
//       learndash_update_completion($user_id);
//       $course_progress = get_user_meta($user_id, '_sfwd-course_progress', true);
//       $completed = intVal($course_progress[$course_id]['completed']);
//       $total = intVal($course_progress[$course_id]['total']);
//     }

//     $percentage = intVal($completed*100/$total);
//     $percentage = ($percentage > 100)? 100:$percentage;
//     $message = $completed." out of ".$total." steps completed";
//   }

//   if($array) {
//     return array("percentage" => @$percentage, "completed" => @$completed, "total" => @$total);
//   }

//   // return SFWD_LMS::get_template('course_progress_widget', array(
//   //       'message' => @$message,
//   //       'percentage' => @$percentage,
//   //       'completed' => @$completed,
//   //       'total'   => @$total
//   //     ));

//  // return '<a class="btn btnCertificate" target="_blank" href="'.$certificateLink.'"><span class="fa fa-certificate"></span> '.__("Print your certificate!", "learndash").' </a>';

// return '<form action="http://mycertificateserver.com.au/CertificateBuilder/certificate.php" method="post">
//   <label>Generate your certificate</label>
//   <input name="full_name" value="@X@user.full_name@X@" type="hidden" />
//   <input name="template" value="mytemplatename" type="hidden" />
//   <input value="Submit" type="submit" />
// </form>'

// }

// add_shortcode( 'customCertificats', 'learndash_generate_certificate_course_completed' );





function return_410() {
  define( 'DONOTCACHEPAGE', true );               // WP Super Cache and W3 Total Cache recognise this
  status_header( 410 );

  if( ! locate_template( '410.php', true ) )
    echo 'Sorry, the page you requested has been permanently removed.';
  exit;
}


function is_current_user_logged_in_as_external() {
  return is_user_logged_in() && "" == get_user_meta(wp_get_current_user()->ID, 'adi_samaccountname', true);
}

function is_current_user_logged_in_as_affiliated_and_can_view_post($ID) {
  return is_user_logged_in() && "" != get_user_meta(wp_get_current_user()->ID, 'adi_samaccountname', true) && members_can_current_user_view_post($ID);
}

function loginout_localize($url, $redirect, $code = null ) {
  global $sitepress;
  $url = $sitepress->convert_url($url, $code);
  $pattern = "/(logout|deconnexion|login|connexion)/";
  preg_match($pattern, $url, $matches);

  $url = preg_replace($pattern, _slug($matches[0], 'page', $code), $url);
  if ( $redirect ) {
    $url = add_query_arg( 'redirect_to', urlencode( $redirect ), $url );
  }
  return $url;
}

add_filter('login_url', 'loginout_localize', 10, 2);
add_filter('logout_url', 'loginout_localize', 10, 2);

/**
* Handles logout redirection
*
* Overrides the logout_redirect function as part of theme_my_login plugin
*
* @param string $default_redirect_to Default redirect
* @param string $redirect_to Requested redirect
* @param WP_User|WP_Error WP_User if user logged in, WP_Error otherwise
* @return string New redirect
*/
function logout_redirect( $default_redirect_to, $redirect_to, $user ) {
  $theme_my_login = Theme_My_Login::get_object();

  $default_redirect_to = icl_get_home_url();

  // Determine the correct referer
  if ( ! $http_referer = wp_get_original_referer() )
  $http_referer = wp_get_referer();

  // Remove some arguments that may be present and shouldn't be
  $http_referer = remove_query_arg( array( 'instance', 'action', 'checkemail', 'error', 'loggedout', 'registered', 'redirect_to', 'updated', 'key', '_wpnonce' ), $http_referer );

  // Make sure $user object exists and is a WP_User instance
  if ( ! is_wp_error( $user ) && is_a( $user, 'WP_User' ) ) {
    if ( is_multisite() && empty( $user->roles ) ) {
      $user->roles = array( 'subscriber' );
    }

    $user_role = reset( $user->roles );

    $redirection = $theme_my_login->get_option( $user_role, array() );

    if ( 'referer' == $redirection['logout_type'] ) {
      // Send 'em back to the referer
      $redirect_to = $http_referer;
    } elseif ( 'custom' == $redirection['logout_type'] ) {
      // Send 'em to the specified URL
      $redirect_to = $redirection['logout_url'];

      // Allow a few user specific variables
      $redirect_to = Theme_My_Login_Common::replace_vars( $redirect_to, $user->ID, array(
        '%user_id%' => $user->ID
      ) );
    }
  }

  // Make sure $redirect_to isn't empty or pointing to an admin URL (causing an endless loop)
  if ( empty( $redirect_to ) || false !== strpos( $redirect_to, 'wp-admin' ) )
    $redirect_to = $default_redirect_to;

  return $redirect_to;
}

add_filter('logout_redirect', 'logout_redirect', 10, 3);



add_action('wp_loaded', 'load_wp_stage_switcher');

function load_wp_stage_switcher() {
  global $envs;
  if (defined('ICL_LANGUAGE_CODE') && isset($envs[ICL_LANGUAGE_CODE])) {
    define('ENVIRONMENTS', serialize($envs[ICL_LANGUAGE_CODE]));
  }
}

add_filter('tml_replace_vars', 'new_user_notification_message_add_loginurl_with_redirect', 10, 2);

function new_user_notification_message_add_loginurl_with_redirect( $replacements, $user_id ) {
  $user_info = get_userdata($user_id);

  $email = $user_info->user_email;
  $redirect = $_REQUEST['return_to'] ?: null;

  $loginurl = icl_get_home_url() . 'login/';

  $loginurl_with_redirect = array();
  $loginurl_with_redirect["en"] = loginout_localize($loginurl, $redirect, 'en');
  $loginurl_with_redirect["fr"] = loginout_localize($loginurl, $redirect, 'fr');

  foreach($loginurl_with_redirect as $lang_code => $url) {
    $url = add_query_arg( 'login_as', urlencode( 'external' ), $url );
    $url = add_query_arg( 'email', urlencode( $email ), $url );
    $loginurl_with_redirect[$lang_code] = $url;
  }

  $replacements['%loginurl_en_with_redirect%'] = $loginurl_with_redirect["en"];
  $replacements['%loginurl_fr_with_redirect%'] = $loginurl_with_redirect["fr"];

  return $replacements;
}

require_once locate_template('/lib/parse_xapi_statements.php');

?>
