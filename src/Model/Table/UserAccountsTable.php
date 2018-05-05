<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2017
 * Time: 11:56 AM
 */
namespace App\Model\Table;

use Cake\Event\Event;
use Cake\Validation\Validator;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class UserAccountsTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('t_user_acc_inf');
        $this->primaryKey('id');

        $this->addAssociations([
            'hasMany' => [
                'UserClubs' => [
                    'className'     => 'App\Model\Table\UserClubsTable',
                    'foreignKey'    => 'user_account_id'
                ]
            ]
        ]);

        //https://github.com/FriendsOfCake/cakephp-upload/blob/master/docs/examples.rst
        /*$this->addBehavior('Josegonzalez/Upload.Upload', [
            'profile_picture' => [
                'path'              => 'webroot{DS}files{DS}images{DS}accounts{DS}',
                'keepFilesOnDelete' => false,
                'nameCallback'      => function(array $data, array $settings) {
                    $ext        = pathinfo($data['name'], PATHINFO_EXTENSION);
                    $filename   = pathinfo($data['name'], PATHINFO_FILENAME);
                    return $filename . '_' . uniqid() . '.' . $ext;
                },
                'transformer'       =>  function ($table, $entity, $data, $field, $settings) {
                    if(!$entity->isNew()) {
                        if(!empty($entity->getOriginal('profile_picture'))) {
                            $filePath = WWW_ROOT . 'files' . DS . 'images' . DS . 'accounts' . DS . $entity->getOriginal('profile_picture');
                            if (file_exists($filePath)) {
                                unlink($filePath);
                            }
                        }
                    }

                    return [
                        $data['tmp_name'] => $data['name']
                    ];
                }
            ]
        ]);*/
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            /**email**/
            ->allowEmpty('email')
            ->add('email', 'format', [
                'rule'      => 'email',
                'message'   => __('{0} wrong format {1}', __('email'), __('email'))
            ])
            ->add('email', 'unique', [
                'rule'      => [$this, 'checkUniqueMail'],
                'message'   => __('This {0} is already registered. Please try again.', __('email_address'))
            ])
            ->add('email', 'length', [
                'rule'      => ['lengthBetween', 0, 128],
                'message'   => __('please enter up to {1} digits for {0}', __('email'), 128)
            ])
            /**email_confirm_code**/
            ->allowEmpty('email_confirm_code', function ($context) {
                return empty($context['data']['email']);
            }, __('{0} is required', __('email_confirm_code')))
            ->add('email_confirm_code', 'exist', [
                'rule'      => [$this, 'checkExistEmailCreateCode'],
                'message'   => __('Restore code is correct, verify the expiration date, please proceed again.')
            ])
            /**password**/
            ->allowEmpty('password', function ($context) {
                return empty($context['data']['email']);
            }, __('{0} is required', __('password')))
            ->add('password', 'minLength', [
                'rule'      => ['minLength', 8],
                'message'   => __('{0} must be {1} digits or more', __('password'), 8)
            ])
            /**birthday**/
            ->allowEmpty('birthday')
            ->add('birthday', 'date', [
                'rule'      => ['date', 'ymd'],
                'message'   => __('{0} wrong format {1}', __('birthday'), 'Y-m-d')
            ])
            /**profile_picture**/
            /*->allowEmpty('profile_picture')
            ->add('profile_picture', 'file', [
                //'rule'      => ['mimeType', ['image/gif', 'image/jpeg', 'image/png', 'image/jpg']],
                'rule'      => ['extension', ['gif', 'jpeg', 'png', 'jpg']],
                'message'   => __('{0} wrong format {1}', __('profile_picture'), '写真')
            ])*/
            /**facebook_id**/
            ->allowEmpty('facebook_id')
            ->add('facebook_id', 'unique', [
                'rule'      => 'validateUnique',
                'provider'  => 'table',
                'message'   => __('This {0} is already registered. Please try again.', __('facebook_id'))
            ])
            /**twitter_id**/
            ->allowEmpty('twitter_id')
            ->add('twitter_id', 'unique', [
                'rule'      => 'validateUnique',
                'provider'  => 'table',
                'message'   => __('This {0} is already registered. Please try again.', __('twitter_id'))
            ])
            /**player_name**/
            ->allowEmpty('player_name')
            ->add('player_name', 'maxLengthBytes', [
                'rule'      => ['maxLengthBytes', 30],
                'message'   => __('There is an error in the number of digits of {0}', __('player_name'))
            ])
            /**sex**/
            ->allowEmpty('sex')
            ->add('sex', 'inList', [
                'rule'      => ['inList', [0, 1, 2, 3]],
                'message'   => __('{0} is incorrect', __('sex'))
            ])
            /**right_left_hander**/
            ->allowEmpty('right_left_hander')
            ->add('right_left_hander', 'inList', [
                'rule'      => ['inList', [1, 2]],
                'message'   => __('{0} is incorrect', __('right_left_hander'))
            ]);

        return $validator;
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    public function validationChangeMail(Validator $validator) {
        $validator
            /**new_mail**/
            ->notEmpty('new_mail', __('{0} is required', __('email')))
            ->add('new_mail', 'format', [
                'rule'      => 'email',
                'message'   => __('{0} wrong format {1}', __('email'), __('email'))
            ])
            ->add('new_mail', 'unique', [
                'rule'      => [$this, 'checkUniqueNewMail'],
                'message'   => __('This {0} is already registered. Please try again.', __('email_address'))
            ])
            ->add('new_mail', 'length', [
                'rule'      => ['lengthBetween', 0, 128],
                'message'   => __('please enter up to {1} digits for {0}', __('email'), 128)
            ])
            /**email_confirm_code**/
            ->notEmpty('email_confirm_code', __('{0} is required', __('email_confirm_code')))
            ->add('email_confirm_code', 'exist', [
                'rule'      => [$this, 'checkExistEmailChangeCode'],
                'message'   => __('{0} does not exist', __('email_confirm_code'))
            ]);

        return $validator;
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    public function validationCreatePasswordAuthenCode(Validator $validator) {
        $validator
            ->notEmpty('email', __('{0} is required', __('email')))
            ->add('email', 'format', [
                'rule'      => 'email',
                'message'   => __('{0} wrong format {1}', __('email'), __('email'))
            ])
            ->add('email', 'exist', [
                'rule'      => [$this, 'checkExistMail'],
                'message'   => __('{0} does not exist', __('email'))
            ]);

        return $validator;
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    public function validationChangePassword(Validator $validator) {
        $validator->notEmpty('pw_change_authen_code', __('{0} is required', __('restore_code')))
            ->add('pw_change_authen_code', 'exist', [
                'rule' => function ($value, $context) {
                    $tblRestore = TableRegistry::get('Restores');

                    $restore = $tblRestore->find('all', [
                        'conditions' => [
                            'restore_code'  => $context['data']['pw_change_authen_code'],
                            'restore_type'  => RESTORE_TYPE_FORGOT_PASSWORD,
                            'status'        => RESTORE_STATUS_ISSUED,
                            'restore_at >=' => date(FORMAT_DATE_001)
                        ]
                    ])->first();

                    return !empty($restore);
                },
                'message' => __('{0} does not exist', __('restore_code'))
            ])
            ->notEmpty('old_password', __('{0} is required', __('old_password')))
            ->notEmpty('new_password', __('{0} is required', __('new_password')))
            ->add('new_password', 'minLength', [
                'rule'      => ['minLength', 8],
                'message'   => __('{0} must be {1} digits or more', __('new_password'), 8)
            ])
            ->notEmpty('confirm_password', __('{0} is required', __('password_confirm')))
            ->add('confirm_password', 'minLength', [
                'rule'      => ['minLength', 8],
                'message'   => __('{0} must be {1} digits or more', __('confirm_password'), 8)
            ])
            ->add('confirm_password', 'compare', [
                'rule'      => [$this, 'compareNewPassword'],
                'message'   => __('{0} requires the same {1}', __('password_confirm'), __('new_password'))
            ]);

        return $validator;
    }

    /**
     * @param Event $event
     * @return bool
     */
    public function beforeSave(Event $event, Entity $entity) {
        $entity = $event->getData('entity');

        if(!empty($entity->new_password)) {
            $entity->password = $this->setPassword($entity->new_password);
        }

        return true;
    }

    /**
     * @param $check
     * @param array $context
     * @return bool
     */
    public function checkUniqueMail($check, array $context) {
        if(isset($context['data']['email'])) {
            $userConditions = ['email' => $context['data']['email']];

            if(!empty($context['data']['id'])) {
                $userConditions['id !='] = $context['data']['id'];
            }

            $user = $this->find('all', [
                'conditions' => $userConditions
            ])->first();

            return empty($user);
        }

        return true;
    }

    /**
     * @param $check
     * @param array $context
     * @return bool
     */
    public function checkUniqueNewMail($check, array $context) {
        if(isset($context['data']['new_mail'])) {
            $tblRestore = TableRegistry::get('Restores');

            $restore = $tblRestore->find('all', [
                'conditions' => [
                    'user_account_id !='    => $context['data']['id'],
                    'restore_type'          => RESTORE_TYPE_CHANGE_MAIL,
                    'status'                => RESTORE_STATUS_ISSUED,
                    'restore_email'         => $context['data']['new_mail']
                ]
            ])->first();

            $user = $this->find('all', [
                'conditions' => [
                    'id !=' => $context['data']['id'],
                    'email' => $context['data']['new_mail']
                ]
            ])->first();

            return empty($user) && empty($restore);
        }

        return true;
    }

    /**
     * @param $check
     * @param array $context
     * @return bool
     */
    public function checkExistMail($check, array $context) {
        if(isset($context['data']['email'])) {
            $user = $this->find('all', [
                'conditions' => ['email' => $context['data']['email']]
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
    public function checkExistEmailCreateCode($check, array $context) {
        if(isset($context['data']['email_confirm_code'])) {
            return $this->checkCreateChangeEmailCodeIssued(RESTORE_TYPE_ACCOUNT_REGISTER, $context['data']['email_confirm_code'], $context['data']['email']);
        }

        return true;
    }

    /**
     * @param $check
     * @param array $context
     * @return bool
     */
    public function checkExistEmailChangeCode($check, array $context) {
        if(isset($context['data']['email_confirm_code'])) {
            return $this->checkCreateChangeEmailCodeIssued(RESTORE_TYPE_CHANGE_MAIL, $context['data']['email_confirm_code'], $context['data']['new_mail']);
        }

        return true;
    }

    /**
     * @param $check
     * @param array $context
     * @return bool
     */
    public function compareMail($check, array $context) {
        if(isset($context['data']['new_mail_confirm']) && isset($context['data']['new_mail'])) {
            return $context['data']['new_mail'] == $context['data']['new_mail_confirm'];
        }

        return true;
    }

    /**
     * @param $check
     * @param array $context
     * @return bool
     */
    public function compareNewPassword($check, array $context) {
        if(isset($context['data']['new_password']) && isset($context['data']['confirm_password'])) {
            return $context['data']['confirm_password'] == $context['data']['new_password'];
        }

        return true;
    }

    /**
     * @param $password
     * @return mixed
     */
    public function setPassword($password) {
        return md5($password);
    }

    /**
     * @param array $conditions
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getUserInfo($conditions = []) {
        return $this->find('all', [
                'fields' => [
                    'id',
                    'session_id',
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
                'conditions' => $conditions
            ])
            ->contain([
                'UserClubs' => function ($q) {
                    return $q->select([
                        'id',
                        'user_account_id',
                        'm_club_id',
                        'best_distance',
                        'maker_name',
                        'type',
                        'nick_name',
                        'distance',
                        'accuracy',
                        'bag_in_flg',
                        'sum_shots',
                        'hit_shots',
                        'delete_flg'
                    ])->autoFields(false);
                }
            ])
            ->first();
    }

    /**
     * @param null $type
     * @return bool
     */
    public function checkCreateChangeEmailCodeIssued($type = null, $code = null, $email = null) {
        $tblRestore = TableRegistry::get('Restores');

        $restore = $tblRestore->find('all', [
            'conditions' => [
                'restore_type'      => $type,
                'status'            => RESTORE_STATUS_ISSUED,
                'restore_code'      => $code,
                'restore_email'     => $email,
                'restore_at >='     => date(FORMAT_DATE_001)
            ]
        ])->first();

        return !empty($restore);
    }
}