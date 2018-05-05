<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/21/2017
 * Time: 2:15 PM
 */
use Cake\Core\Configure;

define('LIMIT_DEFAULT_50', 50);
define('PAGE_DEFAULT', 1);
define('FIELD_SEARCH_GPS', 1);
define('FIELD_SEARCH_AREA', 2);
define('FIELD_SEARCH_HISTORY', 3);

define('API_CODE_OK', 1);
define('API_CODE_NG', 0);
define('API_TIME_OUT', 1800);
define('API_HTTP_CODE_200', 200);
define('API_HTTP_CODE_400', 400);
define('API_CODE_400', 400);
define('API_CODE_401', 401);
define('API_CODE_404', 404);
define('API_CODE_405', 405);
define('API_CODE_503', 503);
define('API_CODE_505', 505);
define('API_CODE_MSG_SUCCESS', __('SUCCESS'));
define('API_CODE_MSG_FAIL', __('FAIL'));

//パラメータエラー
define('API_CODE_100', 100);
define('API_CODE_100_MSG', __('パラメータエラー'));
//該当情報なし
define('API_CODE_101', 101);
define('API_CODE_101_MSG', __('該当情報なし'));
//セクション無効
define('API_CODE_102', 102);
define('API_CODE_102_MSG', __('セクション無効'));
//状態不整合
define('API_CODE_103', 103);
define('API_CODE_103_MSG', __('状態不整合'));
//リクエスト上限数超過
define('API_CODE_110', 110);
define('API_CODE_110_MSG', __('リクエスト上限数超過'));
//タイムアウト
define('API_CODE_111', 111);
define('API_CODE_111_MSG', __('タイムアウト'));
//アカウント存在なし
define('API_CODE_120', 120);
define('API_CODE_120_MSG', __('アカウント存在なし'));
//プレミアム契約なし
define('API_CODE_121', 121);
define('API_CODE_121_MSG', __('プレミアム契約なし'));
//強制更新
define('API_CODE_130', 130);
define('API_CODE_130_MSG', __('強制更新'));
//保守
define('API_CODE_131', 131);
define('API_CODE_131_MSG', __('保守'));
//内部処理エラー
define('API_CODE_900', 900);
define('API_CODE_900_MSG', __('内部処理エラー'));

define('MAX_FILE_IMAGE_SIZE', 1.5);
define('API_MSG_TIMEOUT', __('Token timeout expired'));
define('API_MSG_INCORRECT_INPUT', __('Invalid token or parameter'));
define('API_MSG_VALIDATION_ERROR', __('ERROR'));
define('USER_TYPE_FREE', 0);
define('USER_TYPE_PREMIUM', 1);
define('USER_UNREGISTED', 0);
define('USER_REGISTED', 1);
define('USER_PROFILE_UNREGISTED', 0);
define('USER_PROFILE_REGISTED', 1);

Configure::write('Api.PluginName', 'Api');
Configure::write('Api.token_default', 'wvniR2G0FgaC9mis2guDoUubW8HfLkwDEbBqBwrS');