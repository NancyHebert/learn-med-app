<?php if (
    (!current_user_can('administrator') || !is_user_logged_in())
    && defined('GA_TRACKING_ID') && !empty(GA_TRACKING_ID)
  ) : ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo GA_TRACKING_ID ?>', '<?php echo (defined('COOKIE_DOMAIN') && !empty(COOKIE_DOMAIN))? COOKIE_DOMAIN: 'auto'; ?>');
  ga('send', 'pageview');
</script>
<?php endif; ?>
