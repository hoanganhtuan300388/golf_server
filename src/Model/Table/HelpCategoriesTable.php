<?php

namespace App\Model\Table;


class HelpCategoriesTable extends AppTable
{
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('m_help_category');
        $this->setPrimaryKey('id');
    }

}