<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2017
 * Time: 1:38 PM
 */
namespace App\Model\Table;

class UserCoursesTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('t_user_course_rlt');
        $this->primaryKey('id');

        $this->addAssociations([
            'hasMany' => [
                'UserHoles' => [
                    'className'     => 'App\Model\Table\UserHolesTable',
                    'foreignKey'    => 'user_course_id'
                ]
            ]
        ]);
    }

}