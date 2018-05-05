<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/12/2017
 * Time: 4:16 PM
 */
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Event\Event;

class AppTable extends Table {

    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'updated' => 'always',
                ]
            ]
        ]);
    }

    /**
     * @param Event $event
     * @param Query $query
     */
    public function beforeFind(Event $event, Query $query) {
        $query->where([
            $this->_alias . '.delete_flg' => DELETE_FLG_ACTIVE
        ]);
    }
}