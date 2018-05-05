<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2017
 * Time: 11:35 AM
 */
namespace App\Model\Table;

use Cake\Event\Event;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;

class AdminsTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('t_admin_inf');
        $this->primaryKey('id');
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->notEmpty('login_id', __('{0} is required', __('login_id')))
            ->add('login_id', 'unique', [
                'rule'      => ['validateUnique'],
                'message'   => __('This {0} is already registered. Please try again.', __('login_id')),
                'provider'  => 'table'
            ])
            ->requirePresence('password_plain')
            ->notEmpty('password_plain', __('{0} is required', __('password')), 'create')
            ->add('password_plain', [
                'length' => [
                    'rule'      => ['minLength', 9],
                    'message'   => __('{0} must be {1} digits or more', __('password'), 9),
                ]
            ])
            ->requirePresence('password_confirm')
            ->notEmpty('password_confirm', __('{0} is required', __('password_confirm')), 'create')
            ->add('password_confirm', [
                'compare' => [
                    'rule'      => ['compareWith', 'password_plain'],
                    'message'   => __('{0} requires the same {1}', __('password_confirm'), __('password')),
                ],
            ]);

        return $validator;
    }

    /**
     * @param Event $event
     * @return bool
     */
    public function beforeSave(Event $event) {
        $entity = $event->getData('entity');

        //Make a password for digest auth.
        if(!empty($entity->password_plain)) {
            $entity->admin_pass = $this->_setPassword($entity->password_plain);
        }

        return true;
    }

    /**
     * @param $password
     * @return mixed
     */
    protected function  _setPassword($password) {
        return (new DefaultPasswordHasher)->hash($password);
    }

}