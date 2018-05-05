<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2017
 * Time: 1:40 PM
 */
namespace App\Model\Table;

class UserHolesTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('t_user_hole_rlt');
        $this->primaryKey('id');

        $this->addAssociations([
            'hasMany' => [
                'UserShots' => [
                    'className'     => 'App\Model\Table\UserShotsTable',
                    'foreignKey'    => 'user_hole_id'
                ]
            ]
        ]);
    }

}