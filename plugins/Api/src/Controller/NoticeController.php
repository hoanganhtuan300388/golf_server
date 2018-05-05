<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/2/2018
 * Time: 10:13 AM
 */
namespace Api\Controller;

use Cake\ORM\TableRegistry;

class NoticeController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Notices = TableRegistry::get('Notices');
    }

    /**
     * API IF023 お知らせ一覧取得
     */
    public function getList() {
        if($this->request->is('get')) {
            //get param
            $output     = $this->getBaseOutputParams();
            $params     = $this->getParams();

            $conditions = [
                'public_flg'    => 1,
                'start_at <='   => $this->getCurrentDate(),
                'end_at >='     => $this->getCurrentDate(),
            ];

            if(!empty($timestamp)) {
                $conditions['Helps.updated >='] = $timestamp;
            }

            $notices = $this->Notices->find('all', [
                'fields'        => [
                    'id',
                    'title',
                    'body',
                    'public_flg',
                    'start_at',
                    'end_at',
                    'updated'
                ],
                'conditions'    => $conditions,
                'order'         => ['Notices.updated' => 'desc']
            ])->contain([
                'NoticeCategories' => function ($q) {
                    return $q->select([
                        'id',
                        'category_name'
                    ])->autoFields(false);
                }
            ]);

            $notices = $notices->toArray();

            $data = [];

            foreach($notices as $notice) {
                $data[] = [
                    'id'            => $notice['id'],
                    'title'         => $notice['title'],
                    'body'          => $notice['body'],
                    'public_flg'    => $notice['public_flg'],
                    'start_at'      => $notice['start_at'],
                    'end_at'        => $notice['end_at'],
                    'updated'       => $notice['updated'],
                    'category_id'   => $notice['category']['id'],
                    'category_name' => $notice['category']['category_name']
                ];
            }

            $output['status']       = API_CODE_OK;
            $output['message']      = API_CODE_MSG_SUCCESS;
            $output['data']         = $data;
            $this->renderJson($output);
        } else {
            $this->methodNotAllow();
        }
    }
}