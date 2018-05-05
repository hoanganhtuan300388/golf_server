<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2017
 * Time: 11:58 AM
 */
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UserClubsTable extends Table
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('t_user_club_inf');
        $this->primaryKey(['id', 'user_account_id']);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'updated' => 'always',
                ]
            ]
        ]);
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->notEmpty('user_account_id', __('{0} is required', __('account_id')))
            ->notEmpty('m_club_id', __('{0} is required', __('m_club_id')));

        return $validator;
    }

}