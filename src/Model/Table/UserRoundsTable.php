<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2017
 * Time: 1:41 PM
 */
namespace App\Model\Table;

class UserRoundsTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('t_user_round_rlt');
        $this->primaryKey('id');

        $this->addAssociations([
            'hasMany' => [
                'UserCourses' => [
                    'className'     => 'App\Model\Table\UserCoursesTable',
                    'foreignKey'    => 'user_round_id'
                ]
            ]
        ]);
    }

}