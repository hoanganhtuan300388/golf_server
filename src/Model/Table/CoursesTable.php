<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/5/2017
 * Time: 10:20 AM
 */
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\Event\Event;

class CoursesTable extends AppTable
{
    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('m_course');
        $this->primaryKey(['golf_field_id', 'course_id', 'version']);
    }

    public function beforeFind(Event $event, Query $query) {

    }
}