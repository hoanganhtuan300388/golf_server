<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/25/2017
 * Time: 1:49 PM
 */
namespace Api\Controller;

use Cake\ORM\TableRegistry;

class HelpController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Helps = TableRegistry::get('Helps');
    }

    /**
     * API IF030 ヘルプリスト取得
     */
    public function getList() {
        if($this->request->is('get')) {
            //get param
            $output     = $this->getBaseOutputParams();
            $params     = $this->getParams();
            $conditions = ['public_flg' => 1];

            $timestamp  = !empty($params['timestamp']) ? $this->convertDate($params['timestamp']) : null;

            if(!empty($timestamp)) {
                $conditions['Helps.updated >='] = $timestamp;
            }

            $helps = $this->Helps->find('all', [
                'conditions'    => $conditions,
                'order'         => ['ISNULL(Helps.order_by)', 'Helps.order_by' => 'asc']
            ])->contain([
                'HelpCategories' => function ($q) {
                    return $q->select([
                        'id',
                        'category_name'
                    ])->autoFields(false);
                }
            ]);

            $helps = $helps->toArray();

            $data = [];

            foreach($helps as $help) {
                $data[] = [
                    'id'            => $help['id'],
                    'question'      => $help['title'],
                    'answer'        => $help['body'],
                    'updated'       => $help['updated'],
                    'order_by'      => $help['order_by'],
                    'public_flg'    => $help['public_flg'],
                    'delete_flg'    => $help['delete_flg'],
                    'category_id'   => $help['category']['id'],
                    'category_name' => $help['category']['category_name']
                ];
            }

            $output['status']       = API_CODE_OK;
            $output['timestamp']    = (count($helps) > 0) ? $helps[0]['updated'] : $timestamp;
            $output['message']      = API_CODE_MSG_SUCCESS;
            $output['data']         = $data;
            $this->renderJson($output);
        } else {
            $this->methodNotAllow();
        }
    }
}