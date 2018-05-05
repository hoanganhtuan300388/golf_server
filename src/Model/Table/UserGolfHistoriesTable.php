<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/7/2017
 * Time: 9:32 AM
 */
namespace App\Model\Table;

class UserGolfHistoriesTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('t_user_golf_history');
        $this->primaryKey('id');

        $this->addAssociations([
            'belongsTo' => [
                'GolfField' => [
                    'className'     => 'App\Model\Table\GolfFieldsTable',
                    'foreignKey'    => 'm_field_id',
                    'propertyName'  => 'golf_field'
                ]
            ]
        ]);
    }

}