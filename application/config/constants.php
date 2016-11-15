<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('MSG_TYPE_SUCCESS', 'success');
define('MSG_TYPE_ERROR', 'error');
define('MSG_TYPE_WARNING', 'warning');
define('MSG_TYPE_INFO', 'info');

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/* Environment */
define('ENVIRONMENT_FRONTEND', 'frontend');
define('ENVIRONMENT_EDITOR', 'editor');

/* Module Type */
define('MODULE_TYPE_HOME', 1);

/* Module Name */
define('MODULE_NAME_HOME', 'home');
define('MODULE_NAME_GUEST', 'guest');
define('MODULE_NAME_ACCOUNT', 'account');
define('MODULE_NAME_MEMBERSHIP', 'membership');
define('MODULE_NAME_FORUM', 'forum');

define('TEMP_DIR', '__temp');
define('CONTEXT_TEMPLATE_DIR', '/__template/context/');
define('ARCHIVE_DIR', '__archive');

define('ENTITY_NAME_USER', 'user');
define('ENTITY_NAME_USER_TYPE', 'user_type');
define('ENTITY_NAME_USER_SUBSCRIPTION', 'user_subscription');
define('ENTITY_NAME_TRANSACTION', 'transaction');
define('ENTITY_NAME_PROMOCODE', 'promocodes');
define('ENTITY_NAME_DISCOUNT', 'discounts');
define('ENTITY_NAME_INVOICE', 'invoices');

define('ENTITY_NAME_TEMPLATE_TYPE', 'template_types');
define('ENTITY_NAME_TEMPLATE', 'templates');
define('ENTITY_NAME_RESOURCE', 'resources');
define('ENTITY_NAME_FEATURE', 'features');
define('ENTITY_NAME_FEATURE_TEMPLATE', 'features_to_template');
define('ENTITY_NAME_WIDGET_TYPE', 'widget_types');
define('ENTITY_NAME_WIDGET', 'widgets');
define('ENTITY_NAME_FEATURE_WIDGET', 'features_to_widget');

define('ENTITY_NAME_COMMAND_TYPE', 'command_types');
define('ENTITY_NAME_COMMAND', 'commands');
define('ENTITY_NAME_PAGE', 'pages');
define('ENTITY_NAME_CMS', 'cms');

define('ENTITY_NAME_FORUM_CATEGORY', 'forum_categories');
define('ENTITY_NAME_FORUM_TOPIC', 'forum_topics');
define('ENTITY_NAME_FORUM_POST', 'forum_posts');

define('ENTITY_NAME_FILETREE_STATE', 'filetree_states');

define('ENTITY_NAME_NOTIFICATION', 'notifications');

define('FREE_PLAN_ID', 2);
define('EXPIRATION_CHECK_CYCLE', 2);
define('PAYMENT_DUE_LIMIT', 3);

define('TRIAL_NOT_TAKEN', 0);
define('TRIAL_BEING_TAKEN', 1);
define('TRIAL_TAKEN', 2);

define('BT_USED', 1);

define('PER_PAGE', 10);

define('USER_STATUS_PENDING', 'pending');
define('USER_STATUS_DENIED', 'denied');
define('USER_STATUS_ACTIVE', 'active');
define('USER_STATUS_SUSPENDED', 'suspended');
define('USER_STATUS_BLOCKED', 'blocked');

define('SUSPENDED_REASON_PAYMENT_DUE', 'payment_due');
define('SUSPENDED_REASON_TOO_MANY_FILES', 'too_many_files');
define('SUSPENDED_REASON_TOO_MUCH_SPACES', 'too_much_spaces');

/* End of file constants.php */
/* Location: ./application/config/constants.php */