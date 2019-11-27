
<section id="credits">

<div class="container">





 <?php

global $wp_query;
$postid = $wp_query->post->ID;

$AllTheCredits = get_post_meta($post->ID, 'wpcf-the_credits', true);
$peerReview = get_post_meta($post->ID, 'wpcf-peer_review', true);
$creator = get_post_meta($post->ID, 'creator', true);
$subjectExpertName = get_post_meta($post->ID, 'subject_matter_expert_name', true);
$subjectExpertAffiliation = get_post_meta($post->ID, 'subject_matter_expert_affiliation', true);
$partner_logos = get_post_meta($post->ID, 'partner_logos', true);
$editorialCommittee = get_post_meta($post->ID, 'editorial_committee', true);
$instructionalDesign = get_post_meta($post->ID, 'instructional_design', true);
$programmingDesign = get_post_meta($post->ID, 'programming__graphic_design', true);
$videoCredits = get_post_meta($post->ID, 'video_credits', true);

$experts_interviewed_in_video = get_post_meta($post->ID, 'experts_interviewed_in_video', true);
$video_production = get_post_meta($post->ID, 'video_production', true);
$organizational_partners_for_video_production = get_post_meta($post->ID, 'organizational_partners_for_video_production', true);
$the_primary_care_education_planning_committee_for_retinal_assessment = get_post_meta($post->ID, 'the_primary_care_education_planning_committee_for_retinal_assessment', true);
$design_and_development_of_the_online_program_ = get_post_meta($post->ID, 'design_and_development_of_the_online_program_', true);
$special_thank_you = get_post_meta($post->ID, 'special_thank_you', true);
$image = get_field('partner_logos');



?>


<?php if ($AllTheCredits != "") { ?>

	<h3 role="heading" aria-level="3"><?php _e('Credits', 'learn.med'); ?></h3>

	<?php echo get_post_meta($postid, 'wpcf-the_credits', true);?>


<?php } else {



	if( $creator == '' && $subjectExpertName == '' && $subjectExpertAffiliation == '' && $editorialCommittee == '' && $peerReview == '' && $instructionalDesign == '' &&  $programmingDesign == '' && $videoCredits == '') { ?>

		<!-- if separate credit fields are empty, don't show anything -->

	<?php } else { ?>

			<h3 role="heading" aria-level="3"><?php _e('Credits', 'learn.med'); ?></h3>
				
			<?php if($creator != '')  { ?>


				<p><strong><?php _e('Created by:', 'learn.med'); ?></strong><?php echo get_post_meta($postid, 'creator', true);?></p>


			<?php } ?>


			<?php if( $subjectExpertName != ''  && $subjectExpertAffiliation == '') { ?>

				
				<p>
					<strong><?php _e('Subject Matter Expert:', 'learn.med'); ?></strong>
					<?php echo get_post_meta($postid, 'subject_matter_expert_name', true);?>
				</p>


			<?php } else if( $subjectExpertName != '' && $subjectExpertAffiliation != '') { ?>


				<p><strong><?php _e('Subject Matter Expert:', 'learn.med'); ?></strong>
				<?php echo get_post_meta($postid, 'subject_matter_expert_name', true);?>
				<?php echo ", ";?>
				<?php echo get_post_meta($postid, 'subject_matter_expert_affiliation', true);?></p>


			<?php } ?>



			<?php if($partner_logos != '')  { ?>


				<img src="<?php echo get_post_meta($postid, 'partner_logos', true);?>" alt="<?php _e('Eastern Ontario Health Unit and University of Ottawa’s Office of Continuing Professional Development', 'learn.med'); ?>" />


			<?php } ?>



			<?php if ($editorialCommittee != '') { ?>

				<p><strong><?php _e('Editorial Committee:', 'learn.med'); ?></strong>

				<?php echo get_post_meta($postid, 'editorial_committee', true);?>

				</p>

			<?php } else if($peerReview != '') { ?>

				<p><strong><?php _e('Peer Review:', 'learn.med'); ?></strong>

				<?php echo get_post_meta($postid, 'wpcf-peer_review', true);?>

				</p>

			<?php } ?>




			<?php if($instructionalDesign != '') { ?>

				<p><strong><?php _e('Instructional Design:', 'learn.med'); ?></strong> <?php echo get_post_meta($postid, 'instructional_design', true);?></p>

			<?php } ?>



			<?php if($programmingDesign != '') { ?>

				<p><strong><?php _e('Programming &amp; Graphic Design:', 'learn.med'); ?></strong> <?php echo get_post_meta($postid, 'programming__graphic_design', true);?></p>

			<?php } ?>



			<?php if($experts_interviewed_in_video != '') { ?>

				<p><strong><?php _e('Experts Interviewed in Video: ', 'learn.med'); ?></strong> <?php echo get_post_meta($postid, 'experts_interviewed_in_video', true);?></p>

			<?php } ?>



			<?php if($video_production != '') { ?>

				<p><strong><?php _e('Video production: ', 'learn.med'); ?></strong> <?php echo get_post_meta($postid, 'video_production', true);?></p>

			<?php } ?>



			<?php if($organizational_partners_for_video_production != '') { ?>

				<p><strong><?php _e('Organizational Partners for Video Production: ', 'learn.med'); ?></strong> <?php echo get_post_meta($postid, 'organizational_partners_for_video_production', true);?></p>

			<?php } ?>



			<?php if($the_primary_care_education_planning_committee_for_retinal_assessment != '') { ?>

				<p><strong><?php _e('The Primary Care Education Planning Committee for Retinal Assessment: ', 'learn.med'); ?></strong> <?php echo get_post_meta($postid, 'the_primary_care_education_planning_committee_for_retinal_assessment', true);?></p>

			<?php } ?>



			<?php if($design_and_development_of_the_online_program_ != '') { ?>

				<p><strong><?php _e('Design and Development of the Online Program: ', 'learn.med'); ?></strong>
				<?php echo get_post_meta($postid, 'design_and_development_of_the_online_program_', true);?></p>

			<?php } ?>


			<?php if($special_thank_you != '') { ?>

				<p><strong><?php _e('Special thank you: ', 'learn.med'); ?></strong>
				<?php echo get_post_meta($postid, 'special_thank_you', true);?></p>

			<?php } ?>





		

	<?php } ?>





<?php } wp_reset_query(); ?>






</div>

</section>


<footer role="contentinfo">






<div id="main-footer" class="container-fluid" >

<div class="container">
	<section class="col-md-3">

	<a class="logo-wrapper" href="http://www.med.uottawa.ca" rel="external" target="_blank" title="<?php _e("Return to the Faculty of Medicine website", 'learn.med'); ?>" >
	       <img src="<?php echo get_template_directory_uri(); ?>/assets/img/FacMedHorWhite.png" width="220" height="94" alt="<?php _e("University of Ottawa - Faculty of Medicine", 'learn.med'); ?>" description="<?php _e("University of Ottawa - Faculty of Medicine", 'learn.med'); ?>" />
	     </a>

	</section>




<section class="col-md-5">
<h3 role="heading" aria-level="3"><?php _e('Experiencing Technical Difficulties?', 'learn.med'); ?></h3>
<p><strong><?php _e('No Problem!', 'learn.med'); ?></strong> <a class="help" href="<?php _e('http://www.med.uottawa.ca/medtech/help/', 'learn.med'); ?>" role="link" rel="external" target="_blank"><?php _e('Contact the Experts at Medtech.', 'learn.med'); ?></a>
<?php _e('Your issues will be addressed within 48 hours.', 'learn.med'); ?></p>

</section>


<!-- <section class="col-md-2">
<p>Be Social</p>


	AddThis Button BEGIN

<div class="addthis_toolbox addthis_default_style ">

<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a></li>
<a class="addthis_button_pinterest_pinit" pi:pinit:layout="horizontal"></a>
<a class="addthis_counter addthis_pill_style"></a>



</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-53319a6456c62d5a"></script>


</section>-->


	<!--<section class="col-md-4" id="links">
		<h3 role=​"heading">Faculty Resources</h3>​

		<ul role="list">
		        <li role="listitem"><a href="http://www.med.uottawa.ca/Students/MD/eng/index.html" role="link" rel="external" target="_blank">MD Program</a></li>
		        <li role="listitem"><a href="http://www.one45.com/calendar/ottawa/calendar.php" role="link" rel="external" target="_blank">Timetable</a></li>
		        <li role="listitem"><a href="http://uottawa.ca.libguides.com/Medicine-en" role="link" rel="external" target="_blank">Health Sciences Library</a></li>
		        <li role="listitem"><a href="https://ianatomie.wikispaces.com/" role="link" rel="external" target="_blank">Medswiki</a></li>
		        <li role="listitem"><a href="http://www.youtube.com/user/uottawa2010#p/u" role="link" rel="external" target="_blank">iuOttawa</a></li>
		</ul>

	</section>


	<section class="col-md-4" id="links">
		<h3 role=​"heading">Other links</h3>​

		<ul role="list">
		        <li role="listitem"><a href="#" role="link" rel="external" >link</a></li>
		        <li role="listitem"><a href="#" role="link" rel="external" >link</a></li>
		        <li role="listitem"><a href="#" role="link" rel="external" >link</a></li>
		        <li role="listitem"><a href="#" role="link" rel="external" >link</a></li>
		        <li role="listitem"><a href="#" role="link" rel="external" >link</a></li>
		        </ul>

	</section>-->


	 <!-- <section class="col-md-2" id="social">

	<h3 role=​"heading">​Be Social​</h3>​



	</section> -->


</div>

</div>




  </footer>



<div class="content-info container">
 <hr>

<div class="row">
    <div class="col-lg-12">
      <?php dynamic_sidebar('sidebar-footer'); ?>
      <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
    </div>
  </div>










</div>

<?php wp_footer(); ?>
