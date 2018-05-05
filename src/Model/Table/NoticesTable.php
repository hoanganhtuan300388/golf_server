<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2017
 * Time: 11:44 AM
 */
namespace App\Model\Table;
use Cake\Validation\Validator;

class NoticesTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('t_notice');
        $this->primaryKey('id');

        $this->addAssociations([
            'belongsTo' => [
                'NoticeCategories' => [
                    'className'     => 'App\Model\Table\NoticeCategoriesTable',
                    'foreignKey'    => 'category_id',
                    'propertyName'  => 'category'
                ]
            ]
        ]);
    }

    public function validationDefault(Validator $validator) {
        $validator
            ->notEmpty('title', __('{0} is required', __('title')))
            ->notEmpty('body', __('{0} is required', __('body')));

        return $validator;
    }
}