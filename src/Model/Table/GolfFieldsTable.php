<?php

namespace App\Model\Table;

class GolfFieldsTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('m_golf_field');
        $this->primaryKey('field_id');

        $this->addAssociations([
            'hasMany' => [
                'Courses' => [
                    'className'     => 'App\Model\Table\CoursesTable',
                    'foreignKey'    => 'golf_field_id',
                    'conditions'    => ['delete_flg' => DELETE_FLG_ACTIVE]
                ]
            ],
            'belongsTo' => [
                'Prefecture' => [
                    'className'     => 'App\Model\Table\PrefecturesTable',
                    'foreignKey'    => 'prefecture_id',
                    'propertyName'  => 'prefecture'
                ]
            ]
        ]);
    }

}