<?php echo get_avatar($comment, $size = '64'); ?>
<div class="media-body">

<p class="username-comment">
    <?php 
      if ($comment->user_id) {
      $user=get_userdata($comment->user_id);
      echo $user->user_firstname.'&nbsp'.$user->user_lastname; } 
    ?>
</p>

<?php edit_comment_link(__('(Edit)', 'roots'), '', ''); ?>
<?php if ($comment->comment_approved == '0') : ?>
<div class="alert alert-info">
<?php _e('Your comment is awaiting moderation.', 'roots'); ?>
</div>
<?php endif; ?>
<div class="alert alert-warning" role="alert">
<?php comment_text(); ?>
</div>
	<time datetime="<?php echo comment_date('c'); ?>"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>"><?php printf(__('%1$s', 'roots'), get_comment_date(), get_comment_time()); ?></a></time>
<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
	<style>
	p {color:black; } 
	.media-body {display: block;overflow: hidden;padding: 1em;background-color: #F2F0EF;border-radius: 10px;}
	p.username-comment{color :#731025;}
	</style>