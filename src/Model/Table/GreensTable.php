<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/5/2017
 * Time: 10:20 AM
 */
namespace App\Model\Table;

class GreensTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('m_green');
        $this->primaryKey(['golf_field_id', 'hole_id', 'course_id', 'green_id', 'version']);
    }
}