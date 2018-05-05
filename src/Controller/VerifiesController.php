<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/19/2017
 * Time: 2:37 PM
 */
namespace App\Controller;

use Cake\ORM\TableRegistry;

class VerifiesController extends AppController
{
    /**
     * @param null $code
     */
    public function updateEmail($code = null) {
        $tblUserAccount = TableRegistry::get('UserAccounts');
        $tblRestore     = TableRegistry::get('Restores');

        $message        = __('メールアドレスを変更することが失敗しました。');

        $restore        = $tblRestore->find('all', [
            'conditions' => [
                'restore_code'  => $code,
                'restore_type'  => RESTORE_TYPE_CHANGE_MAIL,
                'restore_at >=' => $this->getCurrentDate()
            ]
        ])->first();

        if(!empty($restore)) {
            if($restore['status'] == RESTORE_STATUS_ISSUED) {
                $user = $tblUserAccount->find('all', [
                    'conditions' => [
                        'id' => $restore['user_account_id']
                    ]
                ])->first();

                if (!empty($user)) {
                    $data = ['email' => $restore['restore_email']];
                    $userEntity = $tblUserAccount->patchEntity($user, $data);

                    if ($tblUserAccount->save($userEntity)) {
                        $tblRestore->updateAll(
                            ['status' => RESTORE_STATUS_RESTORED],
                            ['id' => $restore['id']]
                        );
                        $message = __('メールアドレスを変更することが成功しました。');
                    }
                }
            } else if($restore['status'] == RESTORE_STATUS_RESTORED) {
                $message = __('The status has been restored');
            } else if($restore['status'] == RESTORE_STATUS_INVALID) {
                $message = __('The status is invalid');
            } else {
                $message = __('The status is incorrect');
            }
        }

        pr($message);
        exit;
    }
}