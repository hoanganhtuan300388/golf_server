<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/27/2017
 * Time: 10:42 AM
 */
namespace Api\Controller;

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class PurchaseController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->Billings     = TableRegistry::get('Billings');
        $this->UserAccounts = TableRegistry::get('UserAccounts');
    }

    /**
     * API IF024 課金開始ログ通信
     */
    public function submitStartLog() {
        if($this->request->is('post')) {
            //get param
            $output         = $this->getBaseOutputParams();
            $params         = $this->getParams();

            $billingData    = [
                'user_account_id'   => $this->account['id'],
                'billing_type'      => isset($params['billing_type']) ? $params['billing_type'] : null,
                'billing_start_at'  => $this->getCurrentDate(),
                'device_os'         => DEVICE_TYPE_IOS
            ];

            $billingEntity  = $this->Billings->newEntity($billingData, ['validate' => 'SubmitLog']);

            if($this->Billings->save($billingEntity)) {
                $output['status']   = API_CODE_OK;
                $output['message']  = API_CODE_MSG_SUCCESS;
                $output['data']     = [
                    'billing_id' => $billingEntity->id
                ];
                $this->renderJson($output);
            } else {
                $this->sendError(__('{0} registration failed', __('billing')), API_CODE_100, API_HTTP_CODE_200, $billingEntity->errors());
            }
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * API IF025 課金終了ログ通信
     */
    public function submitFinishLog() {
        if($this->request->is('post')) {
            //get param
            $output     = $this->getBaseOutputParams();
            $params     = $this->getParams();
            $billing    = null;

            if(!empty($params['billing_id'])) {
                $billing = $this->Billings->find('all', [
                    'conditions' => ['id' => $params['billing_id']]
                ])->first();
            }

            if(!empty($billing) && $billing['user_account_id'] != $this->account['id']) {
                $this->sendError(__('{0} is incorrect', __('id')), API_CODE_NG, API_HTTP_CODE_200);
            }

            $conn = ConnectionManager::get('default');
            $conn->begin();
            $saveStatus     = false;

            $billingEndAt   = $this->getCurrentDate();

            $billingData    = [
                'billing_type'              => isset($params['billing_type']) ? $params['billing_type'] : null,
                'billing_end_at'            => $billingEndAt,
                'transaction_date'          => isset($params['transaction_date']) ? $params['transaction_date'] : null,
                'transaction_identifier'    => isset($params['transaction_identifier']) ? $params['transaction_identifier'] : null,
                'receipt_data'              => isset($params['receipt_data']) ? $params['receipt_data'] : null,
                'product_id'                => isset($params['product_id']) ? $params['product_id'] : null,
            ];

            if(empty($billing)) {
                $billingData['billing_start_at'] = $this->getCurrentDate();
                $billingData['device_os']        = DEVICE_TYPE_IOS;
                $billingEntity = $this->Billings->newEntity($billingData, ['validate' => 'SubmitLog']);
            } else {
                $billingEntity = $this->Billings->patchEntity($billing, $billingData, ['validate' => 'SubmitLog']);
            }

            if ($this->Billings->save($billingEntity)) {
                if(!empty($this->account['premium_end_at']) && strtotime($this->account['premium_end_at']) > strtotime($billingEndAt)) {
                    $premiumEndAtCurrent = $this->account['premium_end_at'];
                } else {
                    $premiumEndAtCurrent = $billingEndAt;
                }

                $premiumEndAtUpdate = self::__getPremiumEndAtUpdate($premiumEndAtCurrent, $params['billing_type']);

                $userEntity         = $this->UserAccounts->patchEntity(
                    $this->account,
                    ['premium_end_at' => $premiumEndAtUpdate]
                );

                if($this->UserAccounts->save($userEntity)) {
                    $saveStatus = true;
                } else {
                    $this->sendError(__('{0} {1} update failed', __('user'), $this->account['id']), API_CODE_100, API_HTTP_CODE_200, $userEntity->errors());
                }
            } else {
                $this->sendError(__('{0} {1} update failed', __('billing'), isset($params['billing_id']) ? $params['billing_id'] : ''), API_CODE_100, API_HTTP_CODE_200, $billingEntity->errors());
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
            $this->methodNotAllow();
        }
    }

    /**
     * API IF027 課金復元
     */
    public function restore() {
        if($this->request->is('post')) {
            //get param
            $output = $this->getBaseOutputParams();
            $params = $this->getParams();

            if(empty($params['receipts'])) {
                $this->_errors['receipts'] = __('{0} is required', __('receipts'));
            }

            if(!empty($this->_errors)) {
                $this->sendError(API_CODE_100_MSG, API_CODE_NG, API_HTTP_CODE_200, $this->_errors);
            }

            $conn = ConnectionManager::get('default');
            $conn->begin();
            $saveStatus = true;

            foreach($params['receipts'] as $key => $receipt) {
                if(!empty($receipt['transaction_date'])
                    && !empty($receipt['transaction_identifier'])
                    && !empty($receipt['receipt_data'])
                    && !empty($receipt['product_id'])
                    && !empty($receipt['billing_type'])
                ) {
                    $conditions = [
                        'transaction_date'          => $receipt['transaction_date'],
                        'transaction_identifier'    => $receipt['transaction_identifier'],
                        'product_id'                => $receipt['product_id']
                    ];

                    $billing = $this->Billings->find('all', [
                        'conditions' => $conditions
                    ])->first();

                    if (empty($billing)) {
                        $billingData = array_merge($conditions, [
                            'billing_type'      => $receipt['billing_type'],
                            'user_account_id'   => $this->account['id'],
                            'receipt_data'      => $receipt['receipt_data'],
                            'billing_start_at'  => $this->getCurrentDate(),
                            'billing_end_at'    => $this->getCurrentDate(),
                        ]);

                        $billingEntity = $this->Billings->newEntity($billingData, ['validate' => 'SubmitLog']);

                        if ($this->Billings->save($billingEntity)) {
                            $user = $this->UserAccounts->find()->where(['id' => $this->account['id']])->first();

                            if (empty($user['premium_end_at'])) {
                                $premiumEndAtCurrent = $billingData['billing_end_at'];
                            } else {
                                $premiumEndAtCurrent = $user['premium_end_at'];
                            }

                            $premiumEndAtUpdate = self::__getPremiumEndAtUpdate($premiumEndAtCurrent, $receipt['billing_type']);

                            $userEntity = $this->UserAccounts->patchEntity(
                                $user,
                                ['premium_end_at' => $premiumEndAtUpdate]
                            );

                            if (!$this->UserAccounts->save($userEntity)) {
                                $saveStatus = false;
                                $this->sendError(__('{0} {1} update failed', __('user'), $this->account['id']), API_CODE_100, API_HTTP_CODE_200, $userEntity->errors());
                            }
                        } else {
                            $saveStatus = false;
                            $this->sendError(__('{0} {1} update failed', __('billing'), $receipt['transaction_identifier']), API_CODE_100, API_HTTP_CODE_200, $billingEntity->errors());
                        }
                    }
                }
            }

            if($saveStatus == true) {
                $conn->commit();

                $user = $this->UserAccounts->find()->where(['id' => $this->account['id']])->first();

                $today = date(FORMAT_DATE_002);
                //set user_type
                if(!empty($user['premium_end_at']) && strtotime($user['premium_end_at']) >= strtotime($today)) {
                    $data['expiration_date']    = $user['premium_end_at'];
                    $data['user_type']          = USER_TYPE_PREMIUM;
                } else {
                    $data['expiration_date']    = __('空き');
                    $data['user_type']          = USER_TYPE_FREE;
                }

                $output['status']   = API_CODE_OK;
                $output['message']  = API_CODE_MSG_SUCCESS;
                $output['data']     = $data;
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
     * @param null $date
     * @param null $type
     * @return false|null|string
     */
    private function __getPremiumEndAtUpdate($date = null, $type = null) {
        switch ($type) {
            case BILLING_TYPE_1MONTH:
                return date(FORMAT_DATE_002, strtotime($date . "+1 month"));
                break;
            case BILLING_TYPE_3MONTH:
                return date(FORMAT_DATE_002, strtotime($date . "+3 month"));
                break;
            case BILLING_TYPE_6MONTH:
                return date(FORMAT_DATE_002, strtotime($date . "+6 month"));
                break;
            case BILLING_TYPE_12MONTH:
                return date(FORMAT_DATE_002, strtotime($date . "+12 month"));
                break;
            default:
                return null;
                break;
        }
    }
}