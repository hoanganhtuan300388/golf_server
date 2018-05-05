<?php
namespace App\Model\Table;

class UsersTable extends AppTable
{
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('t_user_acc_inf');
        $this->setPrimaryKey('id');

        $this->hasMany('Billings', [
            'foreignKey' => 'user_account_id'
        ]);
    }
}