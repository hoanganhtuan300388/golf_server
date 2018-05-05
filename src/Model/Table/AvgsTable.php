<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/3/2018
 * Time: 9:28 AM
 */
namespace App\Model\Table;

class AvgsTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('t_avg_data');
        $this->primaryKey('id');
    }
}