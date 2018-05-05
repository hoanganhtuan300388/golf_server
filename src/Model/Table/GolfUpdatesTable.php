<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2017
 * Time: 11:52 AM
 */
namespace App\Model\Table;

class GolfUpdatesTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('t_golf_update_inf');
        $this->primaryKey('id');
    }

}