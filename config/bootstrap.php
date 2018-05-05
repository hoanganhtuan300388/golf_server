<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/*
 * Configure paths required to find CakePHP + general filepath constants
 */
require __DIR__ . '/paths.php';

/*
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Database\Type;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Utility\Inflector;
use Cake\Utility\Security;

/**
 * Uncomment block of code below if you want to use `.env` file during development.
 * You should copy `config/.env.default to `config/.env` and set/modify the
 * variables as required.
 */
// if (!env('APP_NAME') && file_exists(CONFIG . '.env')) {
//     $dotenv = new \josegonzalez\Dotenv\Loader([CONFIG . '.env']);
//     $dotenv->parse()
//         ->putenv()
//         ->toEnv()
//         ->toServer();
// }

/*
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

/*
 * Load an environment local configuration file.
 * You can use a file like app_local.php to provide local overrides to your
 * shared configuration.
 */
//Configure::load('app_local', 'default');

/*
 * When debug = true the metadata cache should only last
 * for a short time.
 */
if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_core_.duration', '+2 minutes');
}

/*
 * Set server timezone to UTC. You can change it to another timezone of your
 * choice but using UTC makes time calculations / conversions easier.
 * Check http://php.net/manual/en/timezones.php for list of valid timezone strings.
 */
date_default_timezone_set('Asia/Tokyo');

/*
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/*
 * Register application error and exception handlers.
 */
$isCli = PHP_SAPI === 'cli';
if ($isCli) {
    (new ConsoleErrorHandler(Configure::read('Error')))->register();
} else {
    (new ErrorHandler(Configure::read('Error')))->register();
}

/*
 * Include the CLI bootstrap overrides.
 */
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}

/*
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 *
 * If you define fullBaseUrl in your config file you can remove this.
 */
if (!Configure::read('App.fullBaseUrl')) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
    }
    unset($httpHost, $s);
}

Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
Email::setConfigTransport(Configure::consume('EmailTransport'));
Email::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

/*
 * The default crypto extension in 3.0 is OpenSSL.
 * If you are migrating from 2.x uncomment this code to
 * use a more compatible Mcrypt based implementation
 */
//Security::engine(new \Cake\Utility\Crypto\Mcrypt());

/*
 * Setup detectors for mobile and tablet.
 */
ServerRequest::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isMobile();
});
ServerRequest::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isTablet();
});

/*
 * Enable immutable time objects in the ORM.
 *
 * You can enable default locale format parsing by adding calls
 * to `useLocaleParser()`. This enables the automatic conversion of
 * locale specific date formats. For details see
 * @link https://book.cakephp.org/3.0/en/core-libraries/internationalization-and-localization.html#parsing-localized-datetime-data
 */
Type::build('time')
    ->useImmutable();
Type::build('date')
    ->useImmutable();
Type::build('datetime')
    ->useImmutable();
Type::build('timestamp')
    ->useImmutable();

/*
 * Custom Inflector rules, can be set to correctly pluralize or singularize
 * table, model, controller names or whatever other string is passed to the
 * inflection functions.
 */
//Inflector::rules('plural', ['/^(inflect)or$/i' => '\1ables']);
//Inflector::rules('irregular', ['red' => 'redlings']);
//Inflector::rules('uninflected', ['dontinflectme']);
//Inflector::rules('transliteration', ['/å/' => 'aa']);

/*
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on Plugin to use more
 * advanced ways of loading plugins
 *
 * Plugin::loadAll(); // Loads all plugins at once
 * Plugin::load('Migrations'); //Loads a single plugin named Migrations
 *
 */
Plugin::loadAll([
    'Api' => ['bootstrap' => true, 'routes' => true, 'autoload' => true]
]);

/*
 * Only try to load DebugKit in development mode
 * Debug Kit should not be installed on a production system
 */
if (Configure::read('debug')) {
    Plugin::load('DebugKit', ['bootstrap' => true]);
}
define('LIMIT_VALUE', 10);
// Label config
$config['users'] = [
    'sex' => [null => '', '1' => __('male'), '2' => __('female')],
    'right_left_hander' => [null => '', 1 => __('right'), 2 => __('left')],
];
$config['billings'] = [
    'billing_type'      => [null => '', 0 => '', 1 => __('1 Month'), 2 => __('3 Month'), 3 => __('6 Month'), 4 => __('1 Year')],
    'device_OS'         => [null=>'', 1 => __('iOS'), 2 => __('Android'), 3 => __('他')],
    'addmonths'         => ['1' => '1', '2' => '3', '3' => '6', '4' => '12'],
    'combined_object_flg' => ['1' => __('Sum covered'), '0' => __('Sum not covered')],
];
$config['notices'] = [
    'type'                => ['1' => __('通常'), '2' => __('強制更新'), '3' => __('保守')],
    'status'              => ['1' => __('Enabled'), '0' => __('Disabled')],
];
$config['golf_update'] = [
    'status'              => ['1' => '承認待ち', '2' => '承認済み', '3' => __('On hold'), '4' => __('Dismissal')],
];
$config['golf'] = [
    'service_status' => ['1' => __('公開中'), '2' => __('閉鎖中'), '3' => __('破棄済')],
    'status' => [ '0' => __('公開中'),'1' => __('削除済')],
];
$config['help'] = [
    'public_flg' => [1 => __('Enabled'), 0 => __('Disabled')]
];
Configure::write('config',$config);

define('FORMAT_DATE_001', 'Y-m-d H:i:s');
define('FORMAT_DATE_002', 'Y-m-d');
define('LAST_VERSION_ACTIVE', 1);
define('DELETE_FLG_ACTIVE', 0);
define('RESTORE_TYPE_CHANGE_DEVICE', 1);
define('RESTORE_TYPE_CHANGE_MAIL', 2);
define('RESTORE_TYPE_FORGOT_PASSWORD', 3);
define('RESTORE_TYPE_ACCOUNT_REGISTER', 4);
define('RESTORE_STATUS_ISSUED', 0);
define('RESTORE_STATUS_RESTORED', 1);
define('RESTORE_STATUS_INVALID', 2);
define('ROUND_TYPE_HALF', 1);
define('ROUND_TYPE_FULL', 2);
define('DELETE_FLG_DEACTIVE', 1);
define('MAINTENANCE_STATUS_DISABLE', 0);
define('MAINTENANCE_STATUS_ENABLE', 1);
define('BILLING_TYPE_1MONTH', 1);
define('BILLING_TYPE_3MONTH', 2);
define('BILLING_TYPE_6MONTH', 3);
define('BILLING_TYPE_12MONTH', 4);
define('FORCED_STATUS_DISABLE', 0);
define('FORCED_STATUS_ENABLE', 1);
define('DISTANCE_KM', 500);
define('APP_EMAIL', 'hoanganhtuan.30388@gmail.com');
define('DEVICE_TYPE_IOS', 1);
define('DEVICE_TYPE_ANDROID', 2);
define('DEVICE_TYPE_OTHER', 3);
define('URL_VERIFY_RECEIPT', 'https://sandbox.itunes.apple.com/verifyReceipt');
