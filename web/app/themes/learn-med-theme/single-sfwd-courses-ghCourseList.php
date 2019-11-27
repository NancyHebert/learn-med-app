<?php
/*
Template Name: coursesListGH
*/
?>


<?php while ( have_posts() ) : the_post();  ?>



<h2 role="heading" aria-level="2"><?php the_title(); ?></h2>



<p class="entry-content">
	<?php
	if(members_can_current_user_view_post(get_the_ID())) {
		the_content();
	}
	?>
</p>
<?php endwhile; ?>


<section id="courses-list" class="col-md-12">

<?php if (is_current_user_logged_in_as_external()): ?>

<article id="page" >

	<div class="courses-list-warning">
		<p>
			<span class="fa fa-info-circle fa-fw"> </span>
			<?php _e("It looks like you're not logged in using a <em>Faculty of Medicine / One45</em> account, which is needed to login as a resident.", "learn.med"); ?>
			<a href="<?php echo wp_logout_url('/' . _slug('login') . '/?login_as=affiliated' ); ?>">
				<?php _e("Logout and log back in with your <em>Faculty of Medicine / One45</em> account", "learn.med"); ?><span class="fa fa-chevron-right fa-fw">
			</a>
		</p>

		<p>
			<?php _e("If you don't have a <em>Faculty of Medicine / One45</em> account (you're a preceptor, for example),", "learn.med"); ?>
			<a href="<?php _e("http://www.med.uottawa.ca/medtech/help/", "learn.med"); ?>">
				<?php _e("request access to the modules", "learn.med"); ?><span class="fa fa-chevron-right fa-fw"> </span>
			</a>
		</p>
	</div>

</article>

<?php elseif (!is_current_user_logged_in_as_affiliated_and_can_view_post(get_the_ID())): ?>

<article id="page" >

	<div class="courses-list-warning">
		<p>
			<span class="fa fa-info-circle fa-fw"> </span>
			<?php _e("It looks like you don't have access to the Postgraduate Medical Education modules.", "learn.med"); ?>
		</p>
		<p>
			<strong><?php _e("Are you a preceptor or a resident? If so,", "learn.med"); ?>
				<a href="<?php _e("http://www.med.uottawa.ca/medtech/help/", "learn.med"); ?>">
					<?php _e("request access to the modules", "learn.med"); ?><span class="fa fa-chevron-right fa-fw"> </span></a>
			</strong>
		</p>
	</div>

</article>

<?php else: ?>

<h3 role="heading" aria-level="3"><?php _e("List of modules and programs", 'learn.med'); ?></h3>

  <article id="page" >

<?php $args = array( 'post_type' => 'sfwd', 'category_name' => 'ugme-students', 'posts_per_page' => 10 );
      $loop = new WP_Query( $args );

      $args2 = array( 'post_type' => 'sfwd-courses', 'category_name' => 'ugme-students', 'posts_per_page' => 10 );
      $loop2 = new WP_Query( $args2 );



      while ( $loop->have_posts() ) : $loop->the_post(); ?>

    <dl <?php post_class(); ?>>

      <dt class="course-list-title "><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></dt>
      <dd class="entry-content"><?php the_excerpt(); ?> </dd>

  	</dl>


<?php endwhile;

 while ($loop2->have_posts() ) : $loop2->the_post(); ?>


     <dl <?php post_class(); ?>>

      <dt class="course-list-title "><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></dt>
      <dd class="entry-content"><?php the_excerpt(); ?> </dd>

    </dl>


<?php endwhile; ?>

  </article>

<?php endif; ?>

</section>
