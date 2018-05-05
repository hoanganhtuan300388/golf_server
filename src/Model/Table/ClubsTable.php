<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2017
 * Time: 11:39 AM
 */
namespace App\Model\Table;

use Cake\ORM\Table;

class ClubsTable extends Table
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('m_club');
        $this->primaryKey('id');
        $this->belongsTo('ClubCategories', [
            'foreignKey' => 'category_id'
        ]);

    }

}