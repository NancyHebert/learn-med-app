<?php get_template_part('templates/content', 'single-sfwd'); ?>



<?php if ( comments_open() ) : ?>
   		<?php comments_template('/templates/comments.php'); ?>
   		    
   	 <?php else : // comments are closed ?>
   		
   		<hr>
   <?php endif; ?>	

