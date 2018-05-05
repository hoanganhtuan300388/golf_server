<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/17/2018
 * Time: 2:31 PM
 */
namespace App\Model\Table;

use Cake\Validation\Validator;

class ForcedsTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('t_force_update');
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
            ->notEmpty('version', __('{0} is required', __('version')))
            ->add('version', 'validFormat', [
                'rule'      => ['custom', '/^[0-9].[0-9].[0-9]$/i'],
                'message'   => __('{0} wrong format {1}', __('version'), __('version'))
            ])
            ->notEmpty('title', __('{0} is required', __('title')))
            ->notEmpty('body', __('{0} is required', __('body')));

        return $validator;
    }

}