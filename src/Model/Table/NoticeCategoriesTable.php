<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 3/1/2018
 * Time: 10:09 AM
 */
namespace App\Model\Table;


class NoticeCategoriesTable extends AppTable
{
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('m_notice_category');
        $this->setPrimaryKey('id');
    }

}