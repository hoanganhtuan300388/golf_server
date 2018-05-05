<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/5/2017
 * Time: 10:20 AM
 */
namespace App\Model\Table;

use Cake\ORM\Table;

class NationsTable extends Table
{
    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('m_nation');
        $this->primaryKey('id');
    }
}