<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2017
 * Time: 11:54 AM
 */
namespace App\Model\Table;

use Cake\ORM\Table;

class ServerSettingsTable extends Table
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('t_server_setting');
        $this->primaryKey('id');

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