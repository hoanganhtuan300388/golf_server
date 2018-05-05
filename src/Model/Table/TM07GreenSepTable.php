<?php

namespace App\Model\Table;

use Cake\Validation\Validator;

class TM07GreenSepTable extends AppTable
{
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('tM07GreenSep');
        $this->setPrimaryKey('sep_id');
    }
}