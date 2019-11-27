<div class="help-block popover bottom" tabindex="-1">
  <div class="arrow"></div>
  <div class="popover-content">
    <p>
      <?php _e("It looks like the email you entered is already tied to an account in our system.", 'learn.med'); ?>
      <a href="#" class="hidden check-email"><?php _e("Check again", 'learn.med'); ?></a>
    </p>
    <div class="actions">
      <a href="<?php echo site_url(_slug('login', 'page')); ?>/?login_as=affiliated" class="btn btn-md btn-success"><?php _e("Go to login", 'learn.med'); ?> &rsaquo;</a>
      <a href="<?php _e("https://app.med.uottawa.ca/PasswordReset/", 'learn.med'); ?>?email={{ email_uri_escaped }}" class="btn btn-link"><?php _e("Forgot your username or password?", 'learn.med'); ?></a>
    </div>
  </div>
</div>
