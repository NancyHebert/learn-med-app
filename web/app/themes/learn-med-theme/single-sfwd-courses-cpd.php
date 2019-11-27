

<?php
/*
Template Name: coursesListCPD
*/
?>

<?php

  //$cme_text = _e('continuing medical education', 'learn.med');
  //$facdev_text = _e('faculty development', 'learn.med');
  //$medhum_text = _e('medicine and humanity', 'learn.med');

?>

<style>

  .filter-desc-number-container div.col-lg-8, .filter-desc-number-container div.col-md-8, .filter-desc-number-container div.col-sm-12, .filter-desc-number-container div.col-xs-12   {

    padding-left: 1px!important;
    padding-right: 0!important;

  }
</style>

<div class="container">

  <?php while ( have_posts() ) : the_post();  ?>

<p class="homebtn"><a href="<?php echo home_url('/') ?>" role="button" class="" ><span class="fa fa-home"></span> <span class="homebtn-label"><?php _e( 'Home' , 'learn.med'); ?></span></a></p>

  <h2 role="heading" aria-level="2" class="section-header-cpd"><?php the_title(); ?></h2>


  <?php endwhile; ?>


  <!--  En ;  Professorial Development Training = pdt  ,  Continuing Professional Development = cpd  and Medicine and Humanity =  medicine_humanity  /////////////// Fr ;  Formation de développement professorale  = fdp  ;    Formation medicale continue  = fmc   and    Medecine et humanites = humanite_medecine -->
  <!--  En : Catégorie all : cpd_pdt      Fr : fmc_fdp   -->

  <!--  Calcul nombre de postes    -->

  <?php
  $args1 = array(
    'post_type' => 'sfwd-courses',
    'post_status' => 'publish',
    'category_name' => _category_slug('cme'),
    'posts_per_page' => 20,

  );
  ?>

  <?php
  $args2 = array(
    'post_type' => 'sfwd-courses',
    'post_status' => 'publish',
    'category_name' => _category_slug('pdt'),
    'posts_per_page' => 20,
  );
  //echo _category_slug('pdt');
  ?>

  <?php
  $args3 = array(
    'post_type' => 'sfwd-courses',
    'post_status' => 'publish',
    'category_name' => _category_slug('medicine_humanity'),
    'posts_per_page' => 20,
    );
    //echo _category_slug('medicine_humanity');
  ?>



  <?php
    $args4 = array(
    'post_type' => 'sfwd-courses',
    'post_status' => 'publish',
    'category_name' => _category_slug('cpd_pdt'),
    'posts_per_page' => 20,
    );
  ?>

  <?php
    $args5 = array(
    'post_type' => 'Page',
    'post_status' => 'publish',
    'category_name' => _category_slug('program'),
    'posts_per_page' => 20,
    );
  ?>

  <?php
    $args6 = array(
    'post_type' => 'Page',
    'post_status' => 'publish',
    'category_name' => _category_slug('cme'),
    'posts_per_page' => 20,
    );
  ?>

  <?php
    $args7 = array(
    'post_type' => 'Page',
    'post_status' => 'publish',
    'category_name' => _category_slug('pdt'),
    'posts_per_page' => 20,
    );
  ?>

  <?php
    $args8 = array(
    'post_type' => 'Page',
    'post_status' => 'publish',
    'category_name' => _category_slug('medicine_humanity'),
    'posts_per_page' => 20,
    );
  ?>

  <?php
    $args9 = array(
      'post_type' => 'sfwd-courses',
      'post_status' => 'publish',
      'category_name' => _category_slug('ugme'),
      'posts_per_page' => 20,

    );
  ?>

  <?php
    $args10 = array(
    'post_type' => 'Page',
    'post_status' => 'publish',
    'category_name' => _category_slug('empc'),
    'posts_per_page' => 20,
    );
  ?>


  <?php
    $args11 = array(
    'post_type' => 'sfwd-courses',
    'post_status' => 'publish',
    'category_name' => _category_slug('geriatric_medicine'),
    'posts_per_page' => 20,
    );
  ?>

  <?php
    $args13 = array(
    'post_type' => 'Page',
    'post_status' => 'publish',
    'category_name' => _category_slug('geriatric_medicine'),
    'posts_per_page' => 20,
    );
  ?>



  <?php
    $category = $_GET['cate'];



    $course_query_args = array(
    'post_type' => 'sfwd-courses',
    'post_status' => 'publish',
    'category_name' => $category,
    'posts_per_page' => 20,
    );


    $pagedProgram = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $program_query_args = array(
    'post_type' => 'Page',
    'post_status' => 'publish',
    'category__and' => array(_category_id('program'), _category_id($category)),
    'posts_per_page' => 20,
    'posts_per_page' => 2,'paged' => $pagedProgram,
    );


  ?>




    <!--  Fin Calcul nombre de postes    -->


    <!--  Selectionner la catégorie all selon la langue  -->

    <?php
    $blog_language = get_bloginfo('language');
    if(isset($_GET['cate'])) {
      $cat = $_GET['cate'];
      //die($cat);
    }
    else
    if ($blog_language == 'fr-FR')
    {
      $cat = 'fmc_fdp';
      //die($cat);
    }
    else
    if ($blog_language == 'en-US')
    {
      $cat = 'cpd_pdt';
      //die($cat);
    }
    ?>



    <!--  filter Rdio boutton
    <div class="side">-->

    <!-- <div class="scroll-top-wrapper ">
    <span class="scroll-top-inner">
    <p>Select a category</p>
  </span>
</div> -->






  <!-- start course and program list div  -->
  <div class="coursesList2  ">

     <div class="row filter-desc-number-container col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" >


            <h3 role="heading" class="filter-desc facdev-color" aria-level="3">
                <?php

                  $theCat = $_GET['cate'];

                  switch ($theCat) {
                      case pdt:
                          _e('Faculty Development', 'learn.med');
                          break;
                      case fdp:
                          _e('Faculty Development', 'learn.med');
                          break;
                      case cme:
                          _e('Continuing Medical Education', 'learn.med');
                          break;
                      case fmc:
                          _e('Continuing Medical Education', 'learn.med');
                          break;
                      case medicine_humanity:
                          _e('Medicine and Humanity', 'learn.med');
                          break;
                      case humanite_medecine:
                          _e('Medicine and Humanity', 'learn.med');
                          break;
                      case ugme:
                          _e('Undergraduate Medical Education', 'learn.med');
                          break;
                      case empc:
                          _e('Études médicales de premier cycle', 'learn.med');
                          break;
                      case geriatric_medicine:
                          _e('Geriatric Medicine', 'learn.med');
                          break;
                      case medecine_geriatrique:
                          _e('Médecine gériatrique', 'learn.med');
                          break;
                      default:
                          _e('All Categories', 'learn.med');



                  }



                ?>

           </h3>

          </div>
          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
            <span class="filter-number"><strong>[

              <!-- Displays the programs and courses number -->
              <?php  echo count( get_posts( $program_query_args ) ); _e(' ] program and [ ', 'learn.med');

              $myCat = $_GET['cate'];

                  switch ($myCat) {
                      case pdt:
                          echo count( get_posts( $args2 ) ); _e(' ] courses', 'learn.med');
                          break;
                      case fdp:
                          echo count( get_posts( $args2 ) ); _e(' ] courses', 'learn.med');
                          break;
                      case cme:
                         echo count( get_posts( $args1 ) ); _e(' ] courses', 'learn.med');
                         //var_dump(get_posts( $args1 ) );
                          break;
                      case fmc:
                          echo count( get_posts( $args1 ) ); _e(' ] courses', 'learn.med');
                          break;
                      case medicine_humanity:
                         echo count( get_posts( $args3 ) ); _e(' ] courses', 'learn.med');
                          break;
                      case humanite_medecine:
                          echo count( get_posts( $args3 ) ); _e(' ] courses', 'learn.med');
                          break;
                      case ugme:
                          echo count( get_posts( $args9 ) ); _e(' ] courses', 'learn.med');
                          break;
                      case empc:
                          echo count( get_posts( $args9 ) ); _e(' ] courses', 'learn.med');
                          break;
                      case geriatric_medicine:
                          echo count( get_posts( $args11 ) ); _e(' ] courses', 'learn.med');
                          break;
                      case medecine_geriatrique:
                          echo count( get_posts( $args11 ) ); _e(' ] courses', 'learn.med');
                          break;
                      default:
                         echo count( get_posts( $args4 ) ); _e(' ] courses', 'learn.med');
                  }


              ?></strong></span>

          </div>
        </div>



      <br clear="all">

        <p class="showCategory "><a role="button" href="#category-filter" class="btn btn-gray btn-md visible-xs visible-sm"><?php _e( 'Scroll to switch to a different category' , 'learn.med'); ?>
            <span class="fa fa-chevron-down"></span></a></p>



      <!-- class="col-lg-5 col-md-5 col-sm-12 col-xs-12" -->
        <section id="courses-list"  class="col-lg-10 col-md-10 col-sm-12 col-xs-12">



        <?php


        $my_query = new WP_Query( $program_query_args );
        //$theUrl = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if ( $my_query->have_posts() ) {//AND $_GET['cate'] == 'cpd_pdt' OR $_GET['cate'] == 'fmc_fdp' OR !strpos($theUrl,'cate')  ) { ?>
        <h3 role="heading" aria-level="3" ><?php _e("Program List", 'learn.med'); ?></h3>

        <?php }  ?>



       <?php


        while ( $my_query->have_posts() ) : $my_query->the_post();

        ?>

        <a class="btnViewModule" href="<?php the_permalink(); ?> <?php //if ( $blog_language == 'fr-FR' ) {echo '/programme-dorientation-des-nouveaux-membres-du-corps-professoral/';} else { echo '/orientation-new-faculty/'; } ?>" target="_blank">

          <article class="program-list-course">
            <dl>
              <dt class="course-list-title"><?php echo the_title();?></dt>
              <dd class="entry-content"><p><?php echo the_excerpt(); ?></p></dd>

              <button role="button" class="btn btn-cpd  btn-lg  "> <?php _e("View the requirements", 'learn.med'); ?>  </button>
           <!-- entry-call-to-action -->
            </dl>

          </article>

        </a>
      <?php  endwhile;  //}  ?>


      <?php

      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

      $args = array('post_type' => 'sfwd-courses', 'category_name' => $cat, 'posts_per_page' => 5,'paged' => $paged,'post_status' => 'publish');
      $the_query = new WP_Query( $args );


      if ( $the_query->have_posts() ) { ?>

         <h3 role="heading" aria-level="3" class="courseTitle"><?php _e("Course List", 'learn.med'); ?></h3>


        <?php } else { ?>

           <h3 role="heading" aria-level="3" class="courseTitle"><?php _e("Course List", 'learn.med'); ?></h3>


         <p><?php _e("There are no courses available for this category at this time. Come back often as may have some in the future.", 'learn.med'); ?></p>

        <?php }  ?>

      <?php

        while ( $the_query->have_posts() ) : $the_query->the_post();

       //echo $cat;
        $category = get_the_category();
        echo '<div class="'.$category[0]->cat_name.'">';

        ?>
          <article class="courses-list-course" <?php post_class(); ?> >
            <dl>
              <dt class="course-list-title"><?php the_title(); ?></dt>
              <dd class="entry-content"><?php the_excerpt(); ?> </dd>
              <p class="callToAction"><a role="button" class="btn btn-cpd  btn-lg" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><?php _e("Learn more about this module", 'learn.med'); ?></a></p>
           <!-- entry-call-to-action -->

           <!-- <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php _e("Learn more about this module", 'learn.med'); ?></a> -->
            </dl>

          </article>




          <?php echo '</div>' ?>

           <?php endwhile; ?>

    </section>



    <section id="category-filter" class="col-lg-2 col-md-2 col-sm-12 col-xs-12">

      <div class="row ">



        <h4 role="heading" aria-level="4"><?php _e('Select a category below to narrow your course & module choices ', 'learn.med');?> </h4>
        <p class="filterArrowDownIcon"><span class="fa fa-arrow-down"></span></p>



          <ul id="choicesNav" class="navbar navbar-default" role="navigation">

            <input type="hidden" name="cate">


            <a  href="<?php _e('/continuing-professional-development/?cate=cpd_pdt','learn.med');?>" <?php if ($_GET['cate'] == 'cpd_pdt' ) {echo 'class="active"';} ?><?php if ($_GET['cate'] == 'fmc_fdp') {echo 'class="active"';} ?> <?php if ($_GET['cate'] == '') {echo 'class="active"';} ?> >
              <li role="listitem" >
                <span class="fa fa-check fa-fw" <?php if ($_GET['cate'] == 'cpd_pdt' OR $_GET['cate'] == 'fmc_fdp')   {echo 'style="visibility: hidden;"';} else { echo 'style="visibility: hidden;"'; } ?>> </span><?php _e("All categories ", 'learn.med'); ?>&nbsp;(<?php echo count( get_posts( $args4 ) );?>)
              </li>
            </a>

            <a href="<?php _e('/continuing-professional-development/?cate=cme','learn.med');?>"<?php if ($_GET['cate'] == 'cme' ) {echo 'class="active"';} ?> <?php if ($_GET['cate'] == 'fmc') {echo 'class="active"';} ?>  >
              <li role="listitem" >
                <span class="fa fa-check fa-fw " <?php if ($_GET['cate'] == 'fmc' OR $_GET['cate'] == 'cme' ) {echo 'style="visibility: hidden;"';} else { echo 'style="visibility: hidden;"'; }  ?>  ></span>
               <?php _e("Continuing Medical Education", 'learn.med'); ?>&nbsp;(<?php echo count( get_posts( $args1 ) );?>)
              </li>
            </a>



            <a  href="<?php _e('/continuing-professional-development/?cate=pdt','learn.med');?>" <?php if ($_GET['cate'] == 'pdt' ) {echo 'class="active"';} ?> <?php if ($_GET['cate'] == 'fdp') {echo 'class="active"';} ?> >
              <li role="listitem" >
                <span class="fa fa-check fa-fw" <?php if ($_GET['cate'] == 'fdp' OR $_GET['cate'] == 'pdt')  {echo 'style="visibility: hidden;"';} else { echo 'style="visibility: hidden;"'; }  ?>  ></span><?php _e("Faculty Development", 'learn.med'); ?>&nbsp;(<?php echo count( get_posts( $args2 ) );?>)
              </li>
            </a>


            <a  href="<?php _e('/continuing-professional-development/?cate=medicine_humanity','learn.med');?>" <?php if ($_GET['cate'] == 'humanite_medecine' ) {echo 'class="active"';} ?><?php if ($_GET['cate'] == 'medicine_humanity') {echo 'class="active"';} ?> >
              <li role="listitem" >
                <span class="fa fa-check fa-fw" <?php if ($_GET['cate'] == 'humanite_medecine' OR $_GET['cate'] == 'medicine_humanity')   {echo 'style="visibility: hidden;"';} else { echo 'style="visibility: hidden;"'; } ?>> </span><?php _e("Medicine and Humanity", 'learn.med'); ?>&nbsp;(<?php echo count( get_posts( $args3 ) );?>)
              </li>
            </a>



            <a  href="<?php _e('/continuing-professional-development/?cate=ugme','learn.med');?>" <?php if ($_GET['cate'] == 'empc' ) {echo 'class="active"';} ?><?php if ($_GET['cate'] == 'ugme') {echo 'class="active"';} ?> >
                <li role="listitem" >
                  <span class="fa fa-check fa-fw" <?php if ($_GET['cate'] == 'empc' OR $_GET['cate'] == 'ugme')   { echo 'style="visibility: hidden;"'; } else { echo 'style="visibility: hidden;"'; } ?>> </span><?php _e("Undergraduate Medical Education", 'learn.med'); ?>&nbsp;(<?php echo count( get_posts( $args9 ) );?>)
                </li>
            </a>


            <a href="<?php _e('/continuing-professional-development/?cate=geriatric_medicine','learn.med');?>" <?php if ($_GET['cate'] == 'medecine_geriatrique' ) {echo 'class="active"';} ?><?php if ($_GET['cate'] == 'geriatric_medicine') {echo 'class="active"';} ?> >
              <li role="listitem" >
                <span class="fa fa-check fa-fw" <?php if ($_GET['cate'] == 'medecine_geriatrique' OR $_GET['cate'] == 'geriatric_medicine')   { echo 'style="visibility: hidden;"'; } else { echo 'style="visibility: hidden;"'; } ?>> </span><?php _e("Geriatric Medicine", 'learn.med'); ?>&nbsp;(<?php echo count( get_posts( $args11 ) );?>)
              </li>
            </a>




          </ul>


<!-- <a href='<?php _e('/continuing-professional-development/?cate=cpd_pdt','learn.med');?>' <?php if ($_GET['cate'] == 'cpd_pdt' || $_GET['cate'] == 'fmc_fdp' || !isset($_GET['cate'])) {echo 'class="active"';} ?> >
<li role="listitem" ><span class="fa fa-check fa-fw" <?php if ($_GET['cate'] == 'fmc_fdp' || $_GET['cate'] == 'cpd_pdt' || !isset($_GET['cate']) ) {echo 'style="visibility: visible;"';} else { echo 'style="visibility: hidden;"'; }  ?>  ></span><?php _e("All categories", 'learn.med'); ?>&nbsp;(<?php echo count( get_posts( $args4 ) )?>)
</li>
</a> -->


     <!--      <span class="filter-clear"><a href="<?php _e('/continuing-professional-development/?cate=cpd_pdt','learn.med');?>">
            <span class="fa fa-undo"></span>
            <span class="filter-clear-link">
              <?php //_e( 'See programs and courses in ' , 'learn.med'); ?>
              <?php _e("All categories ", 'learn.med');
            //if ($_GET['cate'] == 'fmc_fdp' || $_GET['cate'] == 'cpd_pdt' || !isset($_GET['cate']) )
            //{
              echo '(' . count( get_posts( $args4 ) ) . ')';
            //} ?>

            </span></a></span>
     -->

      </div>

    </section>

  </div><!-- end course and program list div -->



<!-- script fixer la navigation avec un scroll >50   -->
<script>

$(function(){ // document ready


  $('section#courses-list').children('div').each(function(event) {
    $(this).wrap('<a class="btnViewModule" role="button" href=""></a>');
    var btnLink = $(this).find('p.callToAction').find('a').attr('href');
    $(this).closest('a.btnViewModule' ).attr('href', btnLink);
  });


  $('div#topPage').unwrap();
  $('div.navigation').unwrap();
  $('div#topPage').removeClass('col-xs-12 col-sm-12 col-md-12 col-lg-12');







  $(".CPD").click(function(){
    window.location=$(this).find("a.entry-call-to-action").attr("href");
    return true;
  });

  if( $(window).width() > 769  ) {
    $('div.choix').addClass('col-xs-12 col-sm-12 col-md-5 col-lg-5 pull-right');
    //$('main.main section#courses-list').removeClass('col-sm-8 col-xs-12 col-sm-12 col-md-10 col-lg-10');
    //$('main.main section#courses-list').addClass('col-xs-12 col-sm-12 col-md-6 col-lg-6');
    $('.choix').removeClass('fixed');
    $('div#topPage').show();
    $('.choix').show();
  } else {
    // $("h3.category").prependTo(".choix");
    //$('section#courses-list > div.choix').addClass('col-xs-12 col-sm-12 col-md-4 col-lg-4');
    //$('section#courses-list > div.allCourse').addClass('col-xs-12 col-sm-12 col-md-7 col-lg-7');
    $('body.page-template-single-sfwd-courses-cpd-php div.topNavigation ul.topRightNav').removeClass('pull-right');
    $('div#topPage').show();
    $('.choix').show();
  }


  if ( $(window).width() > 992 ) {
    $('div#topPage').hide();
  }

});

$(document).ready(function(){
  // build a variable to target the #menu div

  // if ( $('sectionProgramlist').length != true){

  //   console.log('program list does not exist');
  //   $('section#courses-list').removeClass('col-lg-5 col-md-5 col-sm-12 col-xs-12').addClass('col-lg-10 col-md-10 col-sm-12 col-xs-12');

  // } else {

  //   console.log('program list exists');
  //   $('section#courses-list').removeClass('col-lg-10 col-md-10 col-sm-12 col-xs-12').addClass('col-lg-5 col-md-5 col-sm-12 col-xs-12');

  // }


  function scrollNav() {
    $('.showCategory a, #choicesNav a li').click(function(){
      //Toggle Class
      $(".active").removeClass("active");
      $(this).closest('li').addClass("active");
      var theClass = $(this).attr("class");
      $('.'+theClass).parent('li').addClass('active');
      //Animate
      $('html, body').stop().animate({
          scrollTop: $( $(this).attr('href') ).offset().top - 160
      }, 400);
      return false;
    });
    $('.scrollTop a').scrollTop();
  }
  scrollNav();


  $('#choicesNav a').on('click', function(){
    $(this).find('li span').removeClass('fa fa-square').addClass('fa fa-check fa-fw');
    $(this).siblings().find('li span').removeClass('fa fa-check fa-fw').addClass('fa fa-square');
  });

  $("#details").hide();

  var menu = $('#details');

  // bind a click function to the menu-trigger
  $('.btn-details').click(function(event){
    event.preventDefault();
        event.stopPropagation();
    // if the menu is visible slide it up
    if (menu.is(":visible"))
    {
      menu.slideUp(400);
    }
    // otherwise, slide the menu down
    else
    {
      menu.slideDown(400);
    }
  });

    // $(document).not('#details, .btn-details').click(function(event) {
    // event.preventDefault();
    // if (menu.is(":visible"))
    // {
    //         menu.slideUp(400);
    // }
});

</script>


        <?php wp_reset_postdata(); ?>



        <!-- END COURS LIST -->

        <div id="topPage" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
          <p> <a href="#top" class="arrow_box"><!-- <span class="fa fa-chevron-up"></span> -->  Top of the page</a></p>
        </div>


        <!-- Pagination -->
        <?php

        if( $the_query->max_num_pages <= 1 )
        return ;
        $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
        $max   = intval( $the_query->max_num_pages );
        /** Add current page to the array */
        if ( $paged >= 1 )
        $links[] = $paged;
        /** Add the pages around the current page to the array */
        if ( $paged >= 3 ) {
          $links[] = $paged - 1;
          $links[] = $paged - 2;
        }
        if ( ( $paged + 2 ) <= $max ) {
          $links[] = $paged + 2;
          $links[] = $paged + 1;
        }
        echo '<div class="navigation"><ul role="list">' . "\n";
          /** Previous Post Link */
          if ( get_previous_posts_link() )
          printf( '<li role="listitem">%s</li>' . "\n", get_previous_posts_link() );
          /** Link to first page, plus ellipses if necessary */
          if ( ! in_array( 1, $links ) ) {
            $class = 1 == $paged ? ' class="active"' : '';
            printf( '<li role="listitem" %s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
            if ( ! in_array( 2, $links ) )
            echo '<li role="listitem">…</li>';
          }
          /** Link to current page, plus 2 pages in either direction if necessary */
          sort( $links );
          foreach ( (array) $links as $link ) {
            $class = $paged == $link ? ' class="active"' : '';
            printf( '<li role="listitem" %s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
          }
          /** Link to last page, plus ellipses if necessary */
          if ( ! in_array( $max, $links ) ) {
            if ( ! in_array( $max - 1, $links ) )
            echo '<li role="listitem">…</li>' . "\n";

            $class = $paged == $max ? ' class="active"' : '';
            printf( '<li role="lisitem"%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
          }
          /** Next Post Link */
          if ( get_next_posts_link() )
          printf( '<li role="listitem">%s</li>' . "\n", get_next_posts_link() );

          echo '</ul></div>' . "\n";
          ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
          ?>

        </section>

      </div>
