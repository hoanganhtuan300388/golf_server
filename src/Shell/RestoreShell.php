<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/8/2018
 * Time: 10:30 AM
 */
namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;

class RestoreShell extends Shell
{
    public function main()
    {
        $restoreTable = TableRegistry::get('Restores');

        $restoreTable->updateAll(
            ['status' => RESTORE_STATUS_INVALID],
            ['status' => RESTORE_STATUS_ISSUED, 'restore_at <' => date(FORMAT_DATE_001)]
        );

        $this->out('Call restore shell');
    }
}