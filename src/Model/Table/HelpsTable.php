<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class HelpsTable extends Table
{
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('t_help');
        $this->setPrimaryKey('id');

        $this->addAssociations([
            'belongsTo' => [
                'HelpCategories' => [
                    'className'     => 'App\Model\Table\HelpCategoriesTable',
                    'foreignKey'    => 'category_id',
                    'propertyName'  => 'category'
                ]
            ]
        ]);
    }

    public function validationDefault(Validator $validator) {
        $validator
            ->notEmpty('title', __('{0} is required', __('question')))
            ->notEmpty('body', __('{0} is required', __('answer_content')))
            ->notEmpty('order_by', __('{0} is required', __('order_by')))
            ->numeric('order_by', __('{0} field must be a number', __('order_by')));

        return $validator;
    }
}