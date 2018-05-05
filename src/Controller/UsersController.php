<?php

namespace App\Controller;
use Cake\Core\Configure;
use Cake\I18n\Time;

class UsersController extends AdminAppController
{
    public function index() {
        $this->set('title', __('General user list'));

        $query      = $this->request->getQuery();
        $conditions = [];

        if(!empty($query['id'])) {
            $conditions[]['Users.id'] = $query['id'];
            $this->request->data['search']['id'] = $query['id'];
        }
        if(!empty($query['player_name'])) {
            $conditions[]['player_name LIKE'] = "%{$query['player_name']}%";
            $this->request->data['search']['player_name'] = $query['player_name'];
        }
        if(!empty($query['billing_type'])) {
            $conditions[]['Billings.billing_type'] = $query['billing_type'];
            $this->request->data['search']['billing_type'] = $query['billing_type'];
        }
        if(isset($query['status'])) {
            if($query['status'] == 0) {
                $conditions[]['OR'] = ['premium_end_at IS' => null, 'premium_end_at <' => $this->getCurrentDate()];
            } else {
                $conditions[]['premium_end_at >='] = $this->getCurrentDate();
            }
            $this->request->data['search']['status'] = $query['status'];
        }

        $this->request->data['display']['limit'] = !empty($query['limit']) ? $query['limit'] : LIMIT_VALUE;

        $fields = ['Users.id', 'Users.player_name', 'Users.email', 'Users.premium_end_at', 'Users.delete_flg', 'Billings.billing_type', 'Billings.billing_start_at', 'Billings.billing_end_at'];

        $this->paginate = [
            'conditions'     => [$conditions],
            'fields'         => $fields,
            'limit'          => LIMIT_VALUE,
            'order'          => ['id' => 'desc'],
            'join'           => [
                'alias'      => 'Billings',
                'table'      => 't_billing_inf',
                'type'       => 'LEFT',
                'conditions' => [
                    'Billings.user_account_id = Users.id',
                    'Billings.id = (SELECT MAX(t_billing_inf.id) FROM t_billing_inf WHERE t_billing_inf.user_account_id = Users.id AND t_billing_inf.delete_flg = 0)' ,
                ]
            ],
        ];

        $labelBill = Configure::read('config.billings');

        $this->set(['users' => $this->paginate($this->Users), 'label' => $labelBill['billing_type']]);
    }

    public function show($id = null) {
        $this->set('title', __('User detailed information'));
        $fields = ['Users.id', 'Users.player_name', 'Users.email', 'Users.sex', 'Users.birthday', 'Users.height', 'Users.weight', 'Users.right_left_hander', 'Users.profile_picture', 'Users.facebook_id', 'twitter_id', 'Users.session_id', 'Billings.id', 'Billings.billing_start_at', 'Billings.billing_type', 'Billings.billing_end_at', 'Billings.billing_update_flg', 'Billings.billing_update_reason', 'Billings.billing_update_by', 'Billings.device_os'];
        $user = $this->Users->find()
            ->where(['Users.id' => $id])
            ->select($fields)
            ->join([
                'Billings' => [
                    'table'      => 't_billing_inf',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'Billings.user_account_id = Users.id',
                        'Billings.id = (SELECT MAX(t_billing_inf.id) FROM t_billing_inf WHERE t_billing_inf.user_account_id = Users.id AND t_billing_inf.delete_flg = 0)' ,
                    ]
                ]
            ]);
        $labelUser = Configure::read('config.users');
        $labelBill = Configure::read('config.billings');
        $this->set(['user' => $user->first(), 'labelUser' => $labelUser, 'labelBill' => $labelBill]);
    }
}