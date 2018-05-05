<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/3/2018
 * Time: 9:19 AM
 */
namespace Api\Controller;

use Cake\ORM\TableRegistry;

class AvgController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->Avgs = TableRegistry::get('Avgs');
    }

    /**
     * IF028詳細 平均データ取得
     */
    public function getData() {
        if($this->request->is('get')) {
            //get param
            $output = $this->getBaseOutputParams();
            $params = $this->getParams();

            if (empty($params['round_type']) || !in_array($params['round_type'], [ROUND_TYPE_HALF, ROUND_TYPE_FULL])) {
                $this->_errors['round_type'] = __('{0} is required', __('round_type'));
            }

            if (empty($params['avg_score'])) {
                $this->_errors['avg_score'] = __('{0} is required', __('avg_score'));
            }

            if (!empty($this->_errors)) {
                $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $this->_errors);
            }

            $avg = $this->Avgs->find('all', [
                'fields'        => [
                    'avg_score',
                    'avg_pat',
                    'avg_fairway',
                    'avg_gir',
                    'avg_sandsave',
                    'avg_scramble'
                ],
                'conditions'    => [
                    'round_type'    => $params['round_type'],
                    'avg_score'     => $params['avg_score']
                ]
            ])->first();

            $output['status']   = API_CODE_OK;
            $output['message']  = API_CODE_MSG_SUCCESS;
            $output['data']     = $avg;
            $this->renderJson($output);
        } else {
            $this->methodNotAllow();
        }
    }
}