<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/26/2017
 * Time: 10:46 AM
 */
namespace Api\Controller;

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;
use Cake\Routing\Router;

class DeviceController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->UserAccounts = TableRegistry::get('UserAccounts');
        $this->Restores     = TableRegistry::get('Restores');
    }

    /**
     * API IF020 機種変更コード発行
     */
    public function createAuthenCode() {
        if($this->request->is('post')) {
            //get param
            $output         = $this->getBaseOutputParams();

            $restoreAt      = date(FORMAT_DATE_001, strtotime($this->getCurrentDate() . "+7 days"));
            $restoreCode    = $this->randomNumber(6);

            $data           = [
                'user_account_id'   => $this->account['id'],
                'restore_code'      => $restoreCode,
                'restore_type'      => RESTORE_TYPE_CHANGE_DEVICE,
                'status'            => RESTORE_STATUS_ISSUED,
                'restore_at'        => $restoreAt
            ];

            $conn = ConnectionManager::get('default');
            $conn->begin();
            $saveStatus = true;

            $restoreEntity = $this->Restores->newEntity($data);

            if ($this->Restores->save($restoreEntity)) {
                $this->Restores->updateAll(
                    [
                        'status' => RESTORE_STATUS_INVALID
                    ],
                    [
                        'id !='             => $restoreEntity->id,
                        'user_account_id'   => $this->account['id'],
                        'restore_type'      => RESTORE_TYPE_CHANGE_DEVICE,
                        'status'            => RESTORE_STATUS_ISSUED
                    ]
                );
            } else {
                $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $restoreEntity->errors());
            }

            if($saveStatus == true) {
                $conn->commit();
                $output['status']   = API_CODE_OK;
                $output['message']  = API_CODE_MSG_SUCCESS;
                $output['data']     = [
                    'restore_code'  => $restoreCode,
                    'expried_date'  => $restoreAt
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
     * API IF022 機種変更コード確認
     */
    public function change() {
        if($this->request->is('post')) {
            //get param
            $output = $this->getBaseOutputParams();
            $params = $this->getParams();

            if (empty($params['restore_code'])) {
                $this->_errors['restore_code'] = __('{0} is required', __('restore_code'));
            }

            if (!empty($this->_errors)) {
                $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $this->_errors);
            }

            $apiCode    = API_CODE_NG;
            $message    = API_CODE_MSG_FAIL;
            $data       = null;
            $rechange   = !empty($params['rechange']) && $params['rechange'] == true ? true : false;

            if($rechange == false) {
                $conn = ConnectionManager::get('default');
                $conn->begin();
                $saveStatus = false;

                $restore = $this->Restores->find('all', [
                    'conditions' => [
                        'restore_code'  => $params['restore_code'],
                        'restore_type'  => RESTORE_TYPE_CHANGE_DEVICE
                    ]
                ])->first();

                if (!empty($restore)) {
                    if ($restore['status'] == RESTORE_STATUS_ISSUED) {
                        if(empty($restore['restore_at']) || strtotime($restore['restore_at']) < strtotime($this->getCurrentDate())) {
                            $message = __('The restoration code has expired');
                        } else {
                            if ($this->Restores->updateAll(['status' => RESTORE_STATUS_RESTORED], ['id' => $restore['id']])) {
                                $sessionId = $this->sha1Encode(json_encode([
                                    'id' => $restore['user_account_id'],
                                    'last_login_time' => Time::now()
                                ]));

                                if ($this->UserAccounts->updateAll(['session_id' => $sessionId], ['id' => $restore['user_account_id']])) {
                                    $apiCode = API_CODE_OK;
                                    $message = API_CODE_MSG_SUCCESS;
                                    $saveStatus = true;

                                    $data = $this->UserAccounts->getUserInfo(['id' => $restore['user_account_id']]);
                                }
                            }
                        }
                    } else if ($restore['status'] == RESTORE_STATUS_RESTORED) {
                        $message = __('The status has been restored');
                    } else if ($restore['status'] == RESTORE_STATUS_INVALID) {
                        $message = __('The status is invalid');
                    } else {
                        $message = __('The restore code is incorrect. Please try again.');
                    }
                } else {
                    $message = __('The restore code is incorrect. Please try again.');
                }

                if($saveStatus == true) {
                    $conn->commit();
                } else {
                    $conn->rollback();
                }
            } else {
                $restore = $this->Restores->find('all', [
                    'conditions' => [
                        'restore_code'  => $params['restore_code'],
                        'restore_type'  => RESTORE_TYPE_CHANGE_DEVICE,
                        'restore_at >=' => $this->getCurrentDate(),
                        'status'        => RESTORE_STATUS_RESTORED
                    ]
                ])->first();

                if (!empty($restore)) {
                    $apiCode    = API_CODE_OK;
                    $message    = API_CODE_MSG_SUCCESS;
                    $data       = $this->UserAccounts->getUserInfo([
                        'id' => $restore['user_account_id']
                    ]);
                } else {
                    $message = __('There is no restore code');
                }
            }

            if(!empty($data)) {
                //$picPath        = Router::url('/', true) . 'files/images/accounts/';

                $data = [
                    'AID'               => $data['id'],
                    'SID'               => $data['session_id'],
                    'player_name'       => $data['player_name'],
                    'sex'               => $data['sex'],
                    'birthday'          => $data['birthday'],
                    'height'            => $data['height'],
                    'weight'            => $data['weight'],
                    'right_left_hander' => $data['right_left_hander'],
                    //'profile_picture'   => !empty($data['profile_picture']) ? $picPath . $data['profile_picture'] : null,
                    'target_score'      => $data['target_score'],
                    'distance_setting'  => $data['distance_setting'],
                    'clubs'             => $data['user_clubs']
                ];
            }

            $output['status']   = $apiCode;
            $output['message']  = $message;
            $output['data']     = $data;
            $this->renderJson($output);
        } else {
            $this->methodNotAllow();
        }
    }

}