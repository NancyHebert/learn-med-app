<?php
/*
Template Name: undergraduate
*/
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

  <h2 role="heading" aria-level="2" class="section-header-undergrad"><?php the_title(); ?></h2>

  <p>Please be patient while the list of modules and programs for Undergraduate Medical Education fully loads; it may take a minute for the full list to display. Note that once the page has loaded you should not have any performance issues while completing the modules.</p>


  <?php endwhile; ?>


  <!--  Calcul nombre de postes    -->

  <?php
  // $args1 = array(
  //   'post_type' => 'sfwd-courses',
  //   'post_status' => 'publish',
  //   'category_name' => _category_slug('first_year'),
  //   'posts_per_page' => 20,

  // );
  ?>

  <?php
  // $args2 = array(
  //   'post_type' => 'sfwd-courses',
  //   'post_status' => 'publish',
  //   'category_name' => _category_slug('second_year'),
  //   'posts_per_page' => 20,
  // );
  //echo _category_slug('pdt');
  ?>

  <?php
  // $args3 = array(
  //   'post_type' => 'sfwd-courses',
  //   'post_status' => 'publish',
  //   'category_name' => _category_slug('third_year'),
  //   'posts_per_page' => 20,
  //   );
    //echo _category_slug('medicine_humanity');
  ?>

  <?php
    // $args4 = array(
    // 'post_type' => 'sfwd-courses',
    // 'post_status' => 'publish',
    // 'category_name' => _category_slug('fourth_year'),
    // 'posts_per_page' => 20,
    // );
  ?>

  <?php
    $args5 = array(
    'post_type' => 'page',
    'post_status' => 'publish',
    'category_name' => _category_slug('ugme-students'),
    'posts_per_page' => 20,
    );
  ?>

  <?php
    // $args6 = array(
    // 'post_type' => 'Page',
    // 'post_status' => 'publish',
    // 'category_name' => _category_slug('program'),
    // 'posts_per_page' => 20,
    // );
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
      $cat = 'empc-etudiants';
      //die($cat);
    }
    else
    if ($blog_language == 'en-US')
    {
      $cat = 'ugme-students';
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
  <div class="coursesList2">

      <br clear="all">

        <p class="showCategory "><a role="button" href="#category-filter" class="btn btn-gray btn-md visible-xs visible-sm"><?php _e( 'Scroll to switch to a different category' , 'learn.med'); ?>
            <span class="fa fa-chevron-down"></span></a></p>



      


            <?php $args = array( 'post_type' => 'page', 'category_name' => 'ugme-students', 'posts_per_page' => 10 );
                  $loop = new WP_Query( $args );

            $args2 = array( 'post_type' => 'sfwd-courses', 'category_name' => 'ugme-students', 'posts_per_page' => 10 );
            $loop2 = new WP_Query( $args2 );



            while ( $loop->have_posts() ) : $loop->the_post(); ?>
              <article id="page" >
                <dl <?php post_class(); ?>>

                <dt class="course-list-title "><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></dt>
                <dd class="entry-content"><?php the_excerpt(); ?> </dd>

                </dl>
              </article>


            <?php endwhile;

             while ($loop2->have_posts() ) : $loop2->the_post(); ?>
              <article id="page" >

                 <dl <?php post_class(); ?>>

                  <dt class="course-list-title "><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></dt>
                  <dd class="entry-content"><?php the_excerpt(); ?> </dd>

                  </dl>
              </article>
  
            <?php endwhile; ?>

              


    
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
