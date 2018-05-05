<?php

namespace App\Model\Table;

use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
class BillingsTable extends AppTable
{

    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('t_billing_inf');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_account_id'
        ]);
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->notEmpty('user_account_id', __('{0} is required', __('account_id')))
            ->add('user_account_id', 'unique', [
                'rule'      => [$this, 'checkAccountID'],
                'message'   => __('UserId does not exist')
            ])
            ->numeric('user_account_id', __('{0} field must be a number', __('account_id')))
            ->notEmpty('billing_start_at', __('{0} is required', __('billing_start_at')))
            ->notEmpty('billing_end_at', __('{0} is required', __('billing_end_at')))
            ->notEmpty('billing_update_by', __('{0} is required', __('billing_update_by')))
            ->numeric('billing_update_by', __('{0} field must be a number', __('billing_update_by')));

        return $validator;
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    public function validationSubmitLog(Validator $validator) {
        $validator->notEmpty('billing_type', __('{0} is required', __('billing_type')))
            ->add('billing_type', 'inlist', [
                'rule'      => ['inList', [
                    BILLING_TYPE_1MONTH,
                    BILLING_TYPE_3MONTH,
                    BILLING_TYPE_6MONTH,
                    BILLING_TYPE_12MONTH
                ]],
                'message'   => __('{0} is incorrect', __('billing_type'))
            ])
            ->notEmpty('billing_start_at', __('{0} is required', __('billing_start_at')))
            ->notEmpty('billing_end_at', __('{0} is required', __('billing_end_at')))
            ->notEmpty('transaction_date', __('{0} is required', __('transaction_date')))
            ->notEmpty('transaction_identifier', __('{0} is required', __('transaction_identifier')))
            ->add('transaction_identifier', 'unique', [
                'rule'      => 'validateUnique',
                'provider'  => 'table',
                'message'   => __('This {0} is already registered. Please try again.', __('transaction_identifier'))
            ])
            ->notEmpty('receipt_data', __('{0} is required', __('receipt_data')))
            ->add('receipt_data', 'incorrect', [
                'rule'      => [$this, 'checkReceiptData'],
                'message'   => __('{0} is incorrect', __('receipt_data'))
            ])
            ->notEmpty('product_id', __('{0} is required', __('product_id')));

        return $validator;
    }

    public function checkAccountID($check, array $context) {
        if(isset($context['data']['user_account_id']) && isset($context['data']['user_account_id'])) {
            $tblUserAccounts = TableRegistry::get('UserAccounts');
            $userConditions = [
                'id' => $context['data']['user_account_id']
            ];
            $user = $tblUserAccounts->find('all', [
                'conditions' => $userConditions
            ])->first();
            return !empty($user);
        }
        return true;
    }

    /**
     * @param $check
     * @param array $context
     * @return bool
     */
    public function checkReceiptData($check, array $context) {
        if(isset($context['data']['receipt_data']) && isset($context['data']['transaction_identifier'])) {
            $curlopUrl = URL_VERIFY_RECEIPT;

            $objCurl = curl_init();

            curl_setopt($objCurl, CURLOPT_POSTFIELDS, json_encode(array('receipt-data' => $context['data']['receipt_data'])));
            curl_setopt($objCurl,CURLOPT_HEADER, false);
            $userAgent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36";
            curl_setopt($objCurl, CURLOPT_USERAGENT, $userAgent);
            curl_setopt_array($objCurl, array(
                CURLOPT_URL             => $curlopUrl,
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_MAXREDIRS       => 10,
                CURLOPT_TIMEOUT         => 120,
                CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST   => 'POST',
                CURLOPT_SSL_VERIFYPEER  => 0
            ));

            $response = curl_exec($objCurl);
            $response = json_decode($response, true);

            if(isset($response['status']) && $response['status'] == 0) {
                if($response['receipt']['in_app'] && is_array($response['receipt']['in_app'])) {
                    foreach($response['receipt']['in_app'] as $receipt) {
                        if($receipt['transaction_id'] == $context['data']['transaction_identifier']) {
                            return true;
                        }
                    }
                }
            }

            return false;
        }

        return true;
    }
}