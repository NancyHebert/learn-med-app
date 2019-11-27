<?php
$root_dir = dirname(__DIR__);
$webroot_dir = $root_dir . '/web';

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
$dotenv = new Dotenv\Dotenv($root_dir);
if (file_exists($root_dir . '/.env')) {
  $dotenv->load();
  $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD', 'WP_HOME', 'WP_SITEURL']);
}

/**
 * Set up our global environment constant and load its config first
 * Default: development
 */
define('WP_ENV', getenv('WP_ENV') ?: 'development');

$env_config = __DIR__ . '/environments/' . WP_ENV . '.php';

if (file_exists($env_config)) {
  require_once $env_config;
}

/**
 * Define all the URLs for the different environments to enable
 * wp-stage-switcher to work in both languages
 */

$envs = array(
  'en' => array(
    'development' => 'learn.med.uottawa.dev',
    'staging'     => 'staging.learn.med.uottawa.ca',
    'production'  => 'learn.med.uottawa.ca'
  ),
  'fr' => array(
    'development' => 'apprendre.med.uottawa.dev',
    'staging'     => 'staging.apprendre.med.uottawa.ca',
    'production'  => 'apprendre.med.uottawa.ca'
  )
);

/**
 * URLs
 */
define('WP_HOME', getenv('WP_HOME'));
define('WP_SITEURL', getenv('WP_SITEURL'));

/**
 * Enforce HTTPS
 * (only required in prod and staging until we provision using learn-med-stack)
 */
if (
     ( !isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "" )
     && 'cli' !== PHP_SAPI // not accessed through wp-cli
   ) {
  $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  header("Location: $redirect"); exit;
}

/**
 * Custom Content Directory
 */
define('CONTENT_DIR', '/app');
define('WP_CONTENT_DIR', $webroot_dir . CONTENT_DIR);
define('WP_CONTENT_URL', WP_HOME . CONTENT_DIR);

/**
 * DB settings
 */
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
$table_prefix = getenv('DB_PREFIX') ?: 'wp_';

/**
 * Authentication Unique Keys and Salts
 */
define('AUTH_KEY', getenv('AUTH_KEY'));
define('SECURE_AUTH_KEY', getenv('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY', getenv('LOGGED_IN_KEY'));
define('NONCE_KEY', getenv('NONCE_KEY'));
define('AUTH_SALT', getenv('AUTH_SALT'));
define('SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT', getenv('LOGGED_IN_SALT'));
define('NONCE_SALT', getenv('NONCE_SALT'));

/**
 * Set a custom domain for the cookie (defaults to full domain)
 */
define('COOKIE_DOMAIN', getenv('COOKIE_DOMAIN'));

/**
 * Define the domains on which we can trust passing our LRS credentials via this Regex
 */
define('XAPI_TRUST_DOMAINS_REGEX', getenv('XAPI_TRUST_DOMAINS_REGEX'));

/**
 * Set a the tracking id for google-analytics
 */
define('GA_TRACKING_ID',         getenv('GA_TRACKING_ID') ? getenv('GA_TRACKING_ID') : '');

/**
 * Custom Settings
 */
define('AUTOMATIC_UPDATER_DISABLED', true);
define('DISABLE_WP_CRON', true);
define('DISALLOW_FILE_EDIT', true);

/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
  define('ABSPATH', $webroot_dir . '/wp/');
}
