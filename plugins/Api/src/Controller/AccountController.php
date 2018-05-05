<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/27/2017
 * Time: 9:53 AM
 */
namespace Api\Controller;

use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\Datasource\ConnectionManager;

class AccountController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->UserAccounts = TableRegistry::get('UserAccounts');
        $this->Restores     = TableRegistry::get('Restores');
    }

    /**
     * API IF002 ユーザアカウント状態確認
     */
    public function checkState() {
        if($this->request->is('get')) {
            $output = $this->getBaseOutputParams();

            if(empty($this->sessionId)) {
                $this->sendError(API_CODE_120_MSG, API_CODE_120, API_HTTP_CODE_200);
            }

            $data = [
                'expiration_date'           => __('空き'),
                'days_left'                 => 0,
                'user_type'                 => USER_TYPE_FREE,
                'account_registed_status'   => USER_UNREGISTED,
                'profile_setting_status'    => USER_PROFILE_UNREGISTED,
                'email_registed_status'     => 0
            ];

            $account = $this->UserAccounts->find('all', [
                'conditions'    => ['session_id' => $this->sessionId],
                'contain'       => false
            ])->first();

            if(!empty($account)) {
                $today = strtotime(date(FORMAT_DATE_002));
                //set user_type
                if(!empty($account['premium_end_at'])) {
                    $endAt = strtotime($account['premium_end_at']);
                    if($endAt >= $today) {
                        $strTime = $endAt - $today;
                        $data['expiration_date']    = $account['premium_end_at'];
                        $data['days_left']          = floor($strTime/3600/24);
                        $data['user_type']          = USER_TYPE_PREMIUM;
                    }
                }

                //set account_registed_status
                if(!empty($account['email']) || !empty($account['facebook_id']) || !empty($account['twitter_id'])) {
                    $data['account_registed_status'] = USER_REGISTED;
                }

                //set profile_setting_status
                if(!empty($account['player_name'] || !empty($account['sex']))
                    || !empty($account['birthday']) || !empty($account['height'])
                    || !empty($account['weight']) || !empty($account['right_left_hander'])
                    || !empty($account['target_score'])) {
                    $data['profile_setting_status'] = USER_PROFILE_REGISTED;
                }

                //set status = 1 if user registed email
                if(!empty($account['email'])) {
                    $data['email_registed_status'] = 1;
                }
            }

            $output['status']   = API_CODE_OK;
            $output['message']  = API_CODE_MSG_SUCCESS;
            $output['data']     = $data;
            $this->renderJson($output);
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * API IF004 アカウント情報チェック
     */
    public function restoreByInfo() {
        if($this->request->is('post')) {
            //get param
            $output         = $this->getBaseOutputParams();
            $params         = $this->getParams();
            $userAccount    = $this->__checkLoginFrom($params);
            //$picPath        = Router::url('/', true) . 'files/images/accounts/';

            if(empty($userAccount)) {
                $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $this->_errors);
            }

            $output['status']   = API_CODE_OK;
            $output['message']  = API_CODE_MSG_SUCCESS;
            $output['data']     = [
                'AID'               => $userAccount['id'],
                'SID'               => $userAccount['session_id'],
                'player_name'       => $userAccount['player_name'],
                'sex'               => $userAccount['sex'],
                'birthday'          => $userAccount['birthday'],
                'height'            => $userAccount['height'],
                'weight'            => $userAccount['weight'],
                'right_left_hander' => $userAccount['right_left_hander'],
                //'profile_picture'   => !empty($userAccount['profile_picture']) ? $picPath . $userAccount['profile_picture'] : null,
                'target_score'      => $userAccount['target_score'],
                'distance_setting'  => $userAccount['distance_setting'],
                'clubs'             => $userAccount['user_clubs']
            ];
            $this->renderJson($output);
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * API IF007 アカウント登録
     */
    public function register() {
        if($this->request->is('post')) {
            //get param
            $output                     = $this->getBaseOutputParams();
            $params                     = $this->getParams();
            $data                       = [];
            
            $data['email']              = isset($params['email']) ? $params['email'] : null;
            $data['email_confirm_code'] = isset($params['email_confirm_code']) ? $params['email_confirm_code'] : null;
            $data['password']           = isset($params['email']) && !empty($params['password']) ? $params['password'] : null;
            $data['facebook_id']        = isset($params['facebook_id']) ? $params['facebook_id'] : null;
            $data['twitter_id']         = isset($params['twitter_id']) ? $params['twitter_id'] : null;
            $data['player_name']        = isset($params['player_name']) ? $params['player_name'] : null;
            $data['sex']                = isset($params['sex']) ? $params['sex'] : null;
            $data['birthday']           = isset($params['birthday']) ? $params['birthday'] : null;
            $data['height']             = isset($params['height']) ? $params['height'] : null;
            $data['weight']             = isset($params['weight']) ? $params['weight'] : null;
            $data['right_left_hander']  = isset($params['right_left_hander']) ? $params['right_left_hander'] : null;
            $data['target_score']       = isset($params['target_score']) ? $params['target_score'] : null;
            $data['distance_setting']   = isset($params['distance_setting']) ? $params['distance_setting'] : null;
            //$data['profile_picture']    = isset($_FILES['profile_picture']) ? $_FILES['profile_picture'] : null;

            if(empty($this->sessionId)) {
                $data['session_id'] = $this->_createSessionId();
                $account            = $this->UserAccounts->newEntity();
            } else {
                $data['session_id'] = $this->sessionId;
                $account            = $this->UserAccounts->find('all')->where(['session_id' => $data['session_id']])->first();
            }

            $conn = ConnectionManager::get('default');
            $conn->begin();
            $saveStatus = true;

            $accountEntity = $this->UserAccounts->patchEntity($account, $data);

            if(!$accountEntity->errors()) {
                if(!empty($accountEntity->password)) {
                    $accountEntity->password = $this->UserAccounts->setPassword($accountEntity->password);
                }

                $this->UserAccounts->save($accountEntity);

                if(!empty($data['email']) && !empty($data['email_confirm_code'])) {
                    $this->Restores->updateAll(
                        [
                            'user_account_id' => $accountEntity->id,
                            'status' => RESTORE_STATUS_RESTORED
                        ],
                        [
                            'restore_code' => $data['email_confirm_code'],
                            'restore_type' => RESTORE_TYPE_ACCOUNT_REGISTER,
                            'status' => RESTORE_STATUS_ISSUED
                        ]
                    );
                }
            } else {
                $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $accountEntity->errors());
            }

            if($saveStatus == true) {
                $conn->commit();
                $output['status']   = API_CODE_OK;
                $output['message']  = API_CODE_MSG_SUCCESS;
                $output['data']     = [
                    'AID'   => $accountEntity->id,
                    'SID'   => $data['session_id']
                ];
            } else {
                $conn->rollback();
                $output['status']   = API_CODE_NG;
                $output['message']  = API_CODE_MSG_FAIL;
            }

            $this->renderJson($output);
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * API IF008 パスワード更新
     */
    public function changePassword() {
        if($this->request->is('post')) {
            //get param
            $output     = $this->getBaseOutputParams();
            $params     = $this->getParams();
            $account    = null;

            $data   = [
                'new_password'      => isset($params['new_password']) ? $params['new_password'] : null,
                'confirm_password'  => isset($params['confirm_password']) ? $params['confirm_password'] : null
            ];

            if(isset($params['old_password'])) {
                if(!empty($this->sessionId)) {
                    $account = $this->UserAccounts->find('all', [
                        'conditions'    => ['session_id' => $this->sessionId]
                    ])->first();

                    if(!empty($account)) {
                        if(!empty($params['old_password']) && $this->UserAccounts->setpassword($params['old_password']) != $account['password']) {
                            $this->_errors['old_password'] = __('The number of digits of the password is incorrect. Please try again.');
                        }

                        if (!empty($this->_errors)) {
                            $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $this->_errors);
                        }
                    } else {
                        $this->sendError(API_CODE_120_MSG, API_CODE_120, API_HTTP_CODE_200);
                    }
                } else {
                    $this->sendError(API_CODE_120_MSG, API_CODE_120, API_HTTP_CODE_200);
                }

                $data['old_password'] = $params['old_password'];
            } elseif(isset($params['pw_change_authen_code'])) {
                $data['pw_change_authen_code'] = $params['pw_change_authen_code'];
            } else {
                $data['pw_change_authen_code'] = null;
            }


            $accountEntity = $this->UserAccounts->newEntity($data, ['validate' => 'ChangePassword']);

            if(!$accountEntity->errors()) {
                $conn = ConnectionManager::get('default');
                $conn->begin();
                $saveStatus = true;

                if(!empty($account)) {
                    $accountEntity = $this->UserAccounts->patchEntity($account, $data, ['validate' => false]);

                    if(!$this->UserAccounts->save($accountEntity)) {
                        $saveStatus = false;
                    }
                } else {
                    $restore = $this->Restores->find('all', [
                        'conditions' => [
                            'restore_code'  => $params['pw_change_authen_code'],
                            'restore_type'  => RESTORE_TYPE_FORGOT_PASSWORD,
                            'status'        => RESTORE_STATUS_ISSUED,
                            'restore_at >=' => $this->getCurrentDate()
                        ]
                    ])->first();

                    $user = $this->UserAccounts->find('all', [
                        'conditions' => [
                            'id'  => $restore['user_account_id']
                        ]
                    ])->first();

                    if(!empty($user)) {
                        $accountEntity = $this->UserAccounts->patchEntity($user, $data, ['validate' => false]);

                        if($this->UserAccounts->save($accountEntity)) {
                            $this->Restores->updateAll(
                                ['status' => RESTORE_STATUS_RESTORED],
                                ['id' => $restore['id']]
                            );
                        } else {
                            $saveStatus = false;
                        }
                    } else {
                        $saveStatus = false;
                    }
                }

                if($saveStatus == true) {
                    $conn->commit();
                    $output['status']   = API_CODE_OK;
                    $output['message']  = API_CODE_MSG_SUCCESS;
                } else {
                    $conn->rollback();
                    $output['status']   = API_CODE_NG;
                    $output['message']  = API_CODE_MSG_FAIL;
                }

                $this->renderJson($output);
            } else {
                $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $accountEntity->errors());
            }
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * API IF009 パスワード更新コード発行
     */
    public function createPasswordAuthenCode() {
        if($this->request->is('post')) {
            //get param
            $output = $this->getBaseOutputParams();
            $params = $this->getParams();

            $data = [
                'email' => isset($params['email']) ? $params['email'] : null
            ];

            $accountEntity = $this->UserAccounts->newEntity($data, ['validate' => 'CreatePasswordAuthenCode']);

            if(!$accountEntity->errors()) {
                $conn = ConnectionManager::get('default');
                $conn->begin();
                $saveStatus = true;

                $account = $this->UserAccounts->find('all', [
                    'conditions' => [
                        'email' =>  $params['email']
                    ]
                ])->first();

                $currentDate    = $this->getCurrentDate();
                $restoreAt      = date(FORMAT_DATE_001, strtotime($currentDate . "+1 days"));
                $restoreCode    = $this->randomNumber(6);

                $dataRestore = [
                    'user_account_id'   => $account['id'],
                    'restore_code'      => $restoreCode,
                    'restore_type'      => RESTORE_TYPE_FORGOT_PASSWORD,
                    'status'            => RESTORE_STATUS_ISSUED,
                    'restore_at'        => $restoreAt
                ];

                $restoreEntity = $this->Restores->newEntity($dataRestore);

                if ($this->Restores->save($restoreEntity)) {
                    $this->Restores->updateAll(
                        [
                            'status' => RESTORE_STATUS_INVALID
                        ],
                        [
                            'id !='             => $restoreEntity->id,
                            'user_account_id'   => $account['id'],
                            'restore_type'      => RESTORE_TYPE_FORGOT_PASSWORD,
                            'status'            => RESTORE_STATUS_ISSUED
                        ]
                    );

                    if(!empty($account['player_name'])) {
                        $playerName = $account['player_name'];
                    } else {
                        $playerName = strstr($account['restore_email'], '@', true);
                    }

                    $email = new Email();
                    $email->transport('golf_app');

                    try {
                        $email->template('forgot_password', 'default')
                            ->from(APP_EMAIL, __('Email from title'))
                            ->to($account['email'])
                            ->subject(__('Notification of reset authentication code {0}', $currentDate))
                            ->emailFormat('html')
                            ->viewVars([
                                'player_name'   => $playerName,
                                'restore_code'  => $dataRestore['restore_code'],
                                'restore_at'    => $restoreAt,
                                'current_date'  => $currentDate
                            ])
                            ->send();
                    } catch (Exception $e) {
                        $saveStatus = false;
                    }
                } else {
                    $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $restoreEntity->errors());
                }

                if($saveStatus == true) {
                    $conn->commit();
                    $output['status']   = API_CODE_OK;
                    $output['message']  = API_CODE_MSG_SUCCESS;
                } else {
                    $conn->rollback();
                    $output['status']   = API_CODE_NG;
                    $output['message']  = API_CODE_MSG_FAIL;
                }

                $this->renderJson($output);
            } else {
                $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $accountEntity->errors());
            }
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * API IF010 パスワード更新コード確認
     */
    public function checkPasswordAuthenCode() {
        if($this->request->is('post')) {
            //get param
            $output = $this->getBaseOutputParams();
            $params = $this->getParams();

            if (empty($params['pw_change_authen_code'])) {
                $this->_errors['pw_change_authen_code'] = __('{0} is required', __('pw_change_authen_code'));
            }

            if (!empty($this->_errors)) {
                $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $this->_errors);
            }

            $apiCode    = API_CODE_NG;
            $message    = API_CODE_MSG_FAIL;

            $restore    = $this->Restores->find('all', [
                'conditions' => [
                    'restore_code'  => $params['pw_change_authen_code'],
                    'restore_type'  => RESTORE_TYPE_FORGOT_PASSWORD,
                    'restore_at >=' => $this->getCurrentDate()
                ]
            ])->first();

            if(!empty($restore)) {
                if($restore['status'] == RESTORE_STATUS_ISSUED) {
                    $apiCode = API_CODE_OK;
                    $message = API_CODE_MSG_SUCCESS;
                } else if($restore['status'] == RESTORE_STATUS_RESTORED) {
                    $message = __('The status has been restored');
                } else if($restore['status'] == RESTORE_STATUS_INVALID) {
                    $message = __('The status is invalid');
                } else {
                    $message = __('The status is incorrect');
                }
            }

            $output['status']   = $apiCode;
            $output['message']  = $message;
            $this->renderJson($output);
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * API IF013 ユーザ情報更新
     */
    public function updateProfile() {
        if($this->request->is('post')) {
            //get param
            $output         = $this->getBaseOutputParams();
            $params         = $this->getParams();

            $data           = [];

            if(isset($params['player_name'])) { $data['player_name'] = $params['player_name']; };
            if(isset($params['sex'])) { $data['sex'] = $params['sex']; };
            if(isset($params['birthday'])) { $data['birthday'] = date(FORMAT_DATE_002, strtotime($params['birthday'])); };
            if(isset($params['height'])) { $data['height'] = $params['height']; };
            if(isset($params['weight'])) { $data['weight'] = $params['weight']; };
            if(isset($params['right_left_hander'])) { $data['right_left_hander'] = $params['right_left_hander']; };
            //if(isset($_FILES['profile_picture'])) { $data['profile_picture'] = $_FILES['profile_picture']; };
            if(isset($params['target_score'])) { $data['target_score'] = $params['target_score']; };
            if(isset($params['distance_setting'])) { $data['distance_setting'] = $params['distance_setting']; };

            $accountEntity  = $this->UserAccounts->patchEntity($this->account, $data);

            if ($this->UserAccounts->save($accountEntity)) {
                $account = $this->UserAccounts->find('all', [
                    'fields' => [
                        'AID' => 'id',
                        'SID' => 'session_id',
                        'player_name',
                        'sex',
                        'birthday',
                        'height',
                        'weight',
                        'right_left_hander',
                        //'profile_picture',
                        'target_score',
                        'distance_setting'
                    ],
                    'conditions' => ['id' => $this->account['id']]
                ])->first();
                //$pathPic = Router::url('/', true) . 'files/images/accounts/';
                //$account['profile_picture'] = !empty($account['profile_picture']) ? $pathPic . $account['profile_picture'] : '';

                $output['status']   = API_CODE_OK;
                $output['message']  = API_CODE_MSG_SUCCESS;
                $output['data']     = $account;
                $this->renderJson($output);
            } else {
                $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $accountEntity->errors());
            }
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * API IF019 メール変更
     */
    public function updateEmail() {
        if($this->request->is('post')) {
            //get param
            $output = $this->getBaseOutputParams();
            $params = $this->getParams();

            if (empty($params['password'])) {
                $this->_errors['password'] = __('{0} is required', __('password'));
            }

            if(!empty($params['password']) && $this->UserAccounts->setpassword($params['password']) != $this->account['password']) {
                $this->_errors['password'] = __('{0} is incorrect', __('password'));
            }

            if (!empty($this->_errors)) {
                $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $this->_errors);
            }

            $data = [
                'password'              => isset($params['password']) ? $this->UserAccounts->setpassword($params['password']) : null,
                'new_mail'              => isset($params['new_mail']) ? $params['new_mail'] : null,
                'email_confirm_code'    => isset($params['email_confirm_code']) ? $params['email_confirm_code'] : null,
            ];

            $conn = ConnectionManager::get('default');
            $conn->begin();
            $saveStatus = true;

            $accountEntity  = $this->UserAccounts->patchEntity($this->account, $data, ['validate' => 'ChangeMail']);

            if(!$accountEntity->errors()) {
                $accountEntity->email = $accountEntity->new_mail;

                if ($this->UserAccounts->save($accountEntity)) {
                    $this->Restores->updateAll(
                        [
                            'status' => RESTORE_STATUS_RESTORED
                        ],
                        [
                            'restore_code'      => $data['email_confirm_code'],
                            'restore_email'     => $data['new_mail'],
                            'user_account_id'   => $this->account['id'],
                            'restore_type'      => RESTORE_TYPE_CHANGE_MAIL,
                            'status'            => RESTORE_STATUS_ISSUED
                        ]
                    );
                } else {
                    $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $accountEntity->errors());
                }

                if($saveStatus == true) {
                    $conn->commit();
                    $output['status']   = API_CODE_OK;
                    $output['message']  = API_CODE_MSG_SUCCESS;
                } else {
                    $conn->rollback();
                    $output['status']   = API_CODE_NG;
                    $output['message']  = API_CODE_MSG_FAIL;
                }

                $this->renderJson($output);
            } else {
                $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $accountEntity->errors());
            }
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * API IF029 メール確認認証コード発行
     */
    public function createEmailCode() {
        if($this->request->is('post')) {
            //get param
            $output = $this->getBaseOutputParams();
            $params = $this->getParams();

            if (empty($params['restore_email'])) {
                $this->_errors['restore_email'] = __('{0} is required', __('restore_email'));
            }

            if (!empty($this->_errors)) {
                $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $this->_errors);
            }

            $currentDate    = $this->getCurrentDate();
            $restoreAt      = date(FORMAT_DATE_001, strtotime($currentDate . "+1 days"));
            $restoreCode    = $this->randomNumber(6);
            $account        = null;

            $dataRestore    = [
                'restore_code'      => $restoreCode,
                'restore_email'     => $params['restore_email'],
                'status'            => RESTORE_STATUS_ISSUED,
                'restore_at'        => $restoreAt
            ];

            if(!empty($this->sessionId)) {
                $account = $this->UserAccounts->find('all', [
                    'conditions'    => ['session_id' => $this->sessionId]
                ])->first();

                if(!empty($account)) {
                    if(!empty($account['email'])) {
                        $dataRestore['restore_type'] = RESTORE_TYPE_CHANGE_MAIL;
                        $dataRestore['user_account_id'] = $account['id'];
                    } else {
                        $dataRestore['restore_type'] = RESTORE_TYPE_ACCOUNT_REGISTER;
                        $dataRestore['user_account_id'] = $account['id'];
                    }
                } else {
                    $this->sendError(API_CODE_120_MSG, API_CODE_120, API_HTTP_CODE_200);
                }
            } else {
                $dataRestore['restore_type'] = RESTORE_TYPE_ACCOUNT_REGISTER;
            }

            $restoreEntity = $this->Restores->newEntity($dataRestore);

            if ($this->Restores->save($restoreEntity)) {
                $playerName = strstr($params['restore_email'], '@', true);

                if(!empty($account)) {
                    $playerName = $account['player_name'];

                    $this->Restores->updateAll(
                        [
                            'status' => RESTORE_STATUS_INVALID
                        ],
                        [
                            'id !='             => $restoreEntity->id,
                            'user_account_id'   => $account['id'],
                            'restore_type'      => RESTORE_TYPE_CHANGE_MAIL,
                            'status'            => RESTORE_STATUS_ISSUED
                        ]
                    );
                }

                try {
                    $email = new Email();
                    $email->transport('golf_app');

                    $email->template('create_user', 'default')
                        ->from(APP_EMAIL, __('Email from title'))
                        ->to($params['restore_email'])
                        ->subject(__('Contact confirmation of e-mail confirmation code {0}', $currentDate))
                        ->emailFormat('html')
                        ->viewVars([
                            'player_name'   => $playerName,
                            'restore_code'  => $dataRestore['restore_code'],
                            'restore_at'    => $restoreAt,
                            'current_date'  => $currentDate
                        ])
                        ->send();

                    $output['status']   = API_CODE_OK;
                    $output['message']  = API_CODE_MSG_SUCCESS;
                } catch (Exception $e) {
                    $output['status']   = API_CODE_NG;
                    $output['message']  = $e->getMessage();
                }

                $this->renderJson($output);
            } else {
                $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $restoreEntity->errors());
            }
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * @param array $params
     * @return null
     */
    private function __checkLoginFrom($params = []) {
        $result     = null;
        $conditions = [];

        if(!empty($params['facebook_id'])) {
            $conditions['facebook_id'] = $params['facebook_id'];
        }

        if(!empty($params['twitter_id'])) {
            $conditions['twitter_id'] = $params['twitter_id'];
        }

        if(!empty($params['email']) && !empty($params['password'])) {
            $conditions['email']    = $params['email'];
            $conditions['password'] = $this->UserAccounts->setPassword($params['password']);
        }

        if(!empty($conditions)) {
            $result = $this->UserAccounts->getUserInfo($conditions);

            if(!empty($result)) {
                $result->session_id = $this->sha1Encode(json_encode([
                    'id'                => $result->id,
                    'last_login_time'   => Time::now()
                ]));

                $this->UserAccounts->save($result);
            } else {
                $this->sendError(__('Please enter the account information correctly.'), API_CODE_100, API_HTTP_CODE_200);
            }
        }

        return $result;
    }

}