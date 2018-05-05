<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2017
 * Time: 11:48 AM
 */
namespace App\Model\Table;

use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class RestoresTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('t_restore_inf');
        $this->primaryKey('id');
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            /**email**/
            ->allowEmpty('restore_email')
            ->add('restore_email', 'format', [
                'rule'      => 'email',
                'message'   => __('{0} wrong format {1}', __('email'), __('email'))
            ])
            ->add('restore_email', 'unique', [
                'rule'      => [$this, 'checkUniqueMail'],
                'message'   => __('This {0} is already registered. Please try again.', __('email_address'))
            ])
            ->add('restore_email', 'length', [
                'rule'      => ['lengthBetween', 0, 128],
                'message'   => __('please enter up to {1} digits for {0}', __('email'), 128)
            ]);

        return $validator;
    }

    /**
     * @param $check
     * @param array $context
     * @return bool
     */
    public function checkUniqueMail($check, array $context) {
        if(isset($context['data']['restore_email'])) {
            $tblUserAccounts = TableRegistry::get('UserAccounts');

            $restoreConditions = [
                'restore_type in'   => [RESTORE_TYPE_ACCOUNT_REGISTER, RESTORE_TYPE_CHANGE_MAIL],
                'status'            => RESTORE_STATUS_ISSUED,
                'restore_email'     => $context['data']['restore_email'],
                'restore_at >='     => date(FORMAT_DATE_001)
            ];

            if(!empty($context['data']['user_account_id'])) {
                $restoreConditions['user_account_id !='] = $context['data']['user_account_id'];
            }

            $userConditions = [
                'email' => $context['data']['restore_email']
            ];

            $restore = $this->find('all', [
                'conditions' => $restoreConditions
            ])->first();

            $user = $tblUserAccounts->find('all', [
                'conditions' => $userConditions
            ])->first();

            return empty($user) && empty($restore);
        }

        return true;
    }


}