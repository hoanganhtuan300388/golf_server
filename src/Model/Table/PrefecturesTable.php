<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/21/2017
 * Time: 3:31 PM
 */
namespace App\Model\Table;

use Cake\ORM\Table;

class PrefecturesTable extends Table
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('m_prefecture');
        $this->primaryKey('id');
    }

}