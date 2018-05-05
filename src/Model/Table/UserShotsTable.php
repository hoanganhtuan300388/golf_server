<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2017
 * Time: 1:42 PM
 */
namespace App\Model\Table;

use Cake\ORM\Table;

class UserShotsTable extends Table
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('t_user_shot_rlt');
        $this->primaryKey(['id', 'user_round_id', 'user_course_id', 'user_hole_id']);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'updated' => 'always',
                ]
            ]
        ]);
    }

}