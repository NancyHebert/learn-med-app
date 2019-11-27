<div class="help-block popover bottom" tabindex="-1">
  <div class="arrow"></div>
  <div class="popover-content">
    <p>
      <?php _e("It looks like the email you entered already exists in our system. You can use it to login.", 'learn.med'); ?>
      <a href="#" class="hidden check-email"><?php _e("Check again", 'learn.med'); ?></a>
    </p>
    <div class="actions">
      <a href="<?php echo site_url(_slug('login', 'page')); ?>/?login_as=external&email={{ email_uri_escaped }}" class="btn btn-md btn-success"><?php _e("Go to login using {{ email }}", 'learn.med'); ?> &rsaquo;</a>
      <a href="<?php echo site_url(_slug('lostpassword', 'page')); ?>/?email={{ email_uri_escaped }}" class="btn btn-link"><?php _e("Forgot your password?", 'learn.med'); ?></a>
    </div>
  </div>
</div>
