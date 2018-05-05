<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/10/2018
 * Time: 9:17 AM
 */
namespace App\Model\Table;

use Cake\Validation\Validator;

class MaintenancesTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('t_maintenance');
        $this->primaryKey('id');

        $this->addAssociations([
            'belongsTo' => [
                'CreateBy' => [
                    'className'     => 'App\Model\Table\AdminsTable',
                    'foreignKey'    => 'create_by',
                    'propertyName'  => 'createby'
                ],
                'UpdateBy' => [
                    'className'     => 'App\Model\Table\AdminsTable',
                    'foreignKey'    => 'update_by',
                    'propertyName'  => 'updateby'
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
            ->notEmpty('title', __('{0} is required', __('title')))
            ->notEmpty('body', __('{0} is required', __('body')))
            ->notEmpty('start_at', __('{0} is required', __('start_at')))
            ->notEmpty('end_at', __('{0} is required', __('end_at')))
            ->add('end_at', 'datediff', [
                'rule'      => [$this, 'checkDateDiff'],
                'message'   => __('{0} must be greater than {1}', __('end_at'), __('start_at'))
            ]);

        return $validator;
    }

    /**
     * @param $check
     * @param array $context
     * @return bool
     */
    public function checkDateDiff($check, array $context) {
        if(isset($context['data']['start_at']) && isset($context['data']['end_at'])) {
            if(strtotime($context['data']['start_at']) >= strtotime($context['data']['end_at'])) {
                return false;
            }
        }

        return true;
    }
}