<?php

namespace App\Controller;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class BillingsController extends AdminAppController
{
    public function initialize() {
        parent::initialize();
        $this->UserAccounts              = TableRegistry::get('UserAccounts');
    }
    public function index() {
        $this->set('title', __('Billing log list'));

        $query      = $this->request->getQuery();
        $conditions = [];

        if(!empty($query['user_account_id'])) {
            $conditions[]['Billings.user_account_id'] = $query['user_account_id'];
            $this->request->data['search']['user_account_id'] = $query['user_account_id'];
        }
        if(!empty($query['billing_type'])) {
            $conditions[]['billing_type'] = $query['billing_type'];
            $this->request->data['search']['billing_type'] = $query['billing_type'];
        }
        if(!empty($query['billing_before_date'])) {
            $conditions[]['billing_start_at >'] = $query['billing_before_date'];
            $this->request->data['search']['billing_before_date'] = $query['billing_before_date'];
        }
        if(!empty($query['billing_after_date'])) {
            $conditions[]['billing_start_at <'] = $query['billing_after_date'];
            $this->request->data['search']['billing_after_date'] = $query['billing_after_date'];
        }
        if(isset($query['billing_update_flg'])) {
            $conditions[]['billing_update_flg'] = $query['billing_update_flg'];
            $this->request->data['search']['billing_update_flg'] = $query['billing_update_flg'];
        }

        $this->request->data['display']['limit'] = !empty($query['limit']) ? $query['limit'] : LIMIT_VALUE;

        $fields = ['Billings.id', 'Billings.user_account_id', 'Billings.billing_start_at', 'Billings.billing_type', 'Billings.billing_end_at', 'Billings.billing_update_by', 'Users.player_name', 'Billings.device_os', 'Billings.delete_flg'];

        $this->paginate = [
            'fields'        => $fields,
            'conditions'    => $conditions,
            'limit'         => LIMIT_VALUE,
            'order'         => ['id' => 'desc'],
            'contain'       => ['Users']
        ];

        $labelBill = Configure::read('config.billings');

        $this->set(['billings' => $this->paginate($this->Billings), 'labelBill' => $labelBill]);
    }

    public function show($id = null) {
        $this->set('title', __('Billing detailed information'));

        $billing                        = $this->Billings->findById($id)->FirstOrFail();
        $labelBill                      = Configure::read('config.billings');
        $billing['billing_end_date']    = $this->convertDate($billing['billing_end_at']. "+". $labelBill['addmonths'][$billing['billing_type']]. "months");

        if($billing['combined_object_flg'] == 0) {
            $billing['billing_end_date'] = $this->convertDate($billing['billing_end_at']);
        }

        if(!isset($billing['billing_end_at']) || $billing['billing_end_at']->isPast()) {
            $billing['status'] = __("Not Purchase");
        } else {
            $billing['status'] = __("Purcharsed");
        }

        $this->set(['billing' => $billing, 'labelBill' => $labelBill]);
    }

    public function add() {
        $this->set('title', __('Create billing log'));

        $billing = $this->Billings->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $billing = $this->Billings->patchEntity($billing, $data);
            if ($this->Billings->save($billing)) {
                //update premium_end_date
                $this->_update_premium_date($data['user_account_id'], $data['billing_type']);
                $this->Flash->success(__('Registration succeeded'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Registration failed'));
            }
        }

        $this->set(['billing' => $billing]);
    }
    public function _update_premium_date($user_account_id, $billing_type, $old_billing_type = ''){
        $arrType = ['1' => 1, '2' => 3, '3' => 6, '4' => 12];
        $user = $this->UserAccounts->get($user_account_id);
        $premium_end_at = date('Y-m-d');
        if($user->premium_end_at == null || $user->premium_end_at == ''){
            $end_at = date('Y-m-d', strtotime("+".$arrType[$billing_type]." months", strtotime($premium_end_at)));
        }else{
            //edit
            if($old_billing_type != ''){
                $end_at = date('Y-m-d', strtotime("+".$arrType[$billing_type]." months", strtotime($user->premium_end_at)));
                $end_at = date('Y-m-d', strtotime("-".$arrType[$old_billing_type]." months", strtotime($end_at)));
            }
            //add
            else{
                if (strtotime($premium_end_at) > strtotime($user->premium_end_at)) {
                    $end_at = $premium_end_at;
                } else {
                    $end_at = $user->premium_end_at;
                }
                $end_at = date('Y-m-d', strtotime("+".$arrType[$billing_type]." months", strtotime($end_at)));
            }
        }
        $user->premium_end_at = $end_at;
        if(!$this->UserAccounts->save($user)){
            $this->Flash->error(__('Registration failed'));
            return $this->redirect(['action' => 'index']);
        }
    }
    public function edit($id = null) {
        $this->set('title', __('管理者詳細情報'));
        $labelBill = Configure::read('config.billings');
        if (!$id){
            $this->response = $this->redirect(['action' => 'index']) ;
            $this->Flash->error(__('Not Found Bill'));
        }
        $billing = $this->Billings->findById($id)->firstOrFail();
        if (empty($billing)){
            $this->response = $this->redirect(['action' => 'index']) ;
            $this->Flash->error(__('Not found this Bill'));
        }
        if(!isset($billing['billing_update_by'])) {
            $this->redirect(['action' => 'show',$id]);
        }
        if(!isset($billing['billing_end_at']) || $billing['billing_end_at']->isPast()) {
            $billing['status'] = __("Not Purchase");
        } else {
            $billing['status'] = __("Purcharsed");
        }
        $billing['billing_end_date'] = $this->convertDate($billing['billing_end_at']. "+". $labelBill['addmonths'][$billing['billing_type']]. "months");
        if($billing['combined_object_flg'] == 0) {
            $billing['billing_end_date'] = $this->convertDate($billing['billing_end_at']);
        }
        $old_billing_type = $billing->billing_type;
        if($this->request->is(['post', 'put'])) {
            $data = $this->request->getData();
            $billing = $this->Billings->patchEntity($billing, $data);
            $billing->billing_update_at = $this->getCurrentDate();
            $billing->billing_update_flg = 1;
            if($this->Billings->save($billing)) {
                //update premium_end_date
                $this->_update_premium_date($data['user_account_id'], $data['billing_type'], $old_billing_type);
                $this->Flash->success(__('Bill has been saved'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('なら項目の下にエラーメッセージを表示する'));
            }
        }
        $this->set(['billing' => $billing, 'labelBill' => $labelBill]);
    }

    public function delete($id = null) {
        $bill = $this->Billings->get($id);
        $bill->delete_flg = 1;
        if($this->Billings->save($bill)){
            $this->Flash->success(__('The Bill has been deleted'));
            return $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->error(__('The Bill could not be deleted'));
        }
    }

}