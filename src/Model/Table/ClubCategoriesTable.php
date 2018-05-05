<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2017
 * Time: 11:41 AM
 */
namespace App\Model\Table;

use Cake\ORM\Table;

class ClubCategoriesTable extends Table
{
    /**
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('m_club_category');
        $this->primaryKey('id');
    }

}