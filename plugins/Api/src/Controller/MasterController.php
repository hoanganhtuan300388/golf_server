<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/5/2017
 * Time: 9:11 AM
 */
namespace Api\Controller;

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class MasterController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->Prefectures          = TableRegistry::get('Prefectures');
        $this->Nations              = TableRegistry::get('Nations');
        $this->Clubs                = TableRegistry::get('Clubs');
        $this->ClubCategories       = TableRegistry::get('ClubCategories');
        $this->ServerSettings       = TableRegistry::get('ServerSettings');
    }
    /**
     * API IF001詳細
     */
    public function dataGet() {
        if($this->request->is('get')) {
            //get param
            $output         = $this->getBaseOutputParams();
            $params         = $this->getParams();

            $data = $nations = $prefectures = $clubs = $conditonNation = $conditionPre = $conditonClub = $conditonClubCat = [];
            if(isset($params['timestamp']) && $params['timestamp'] != ''){
                $conditonNation['Nations.updated >= '] = $params['timestamp'];
                $conditionPre['Prefectures.updated >= '] = $params['timestamp'];
                $conditonClub['Clubs.updated >= '] = $params['timestamp'];
                $conditonClubCat['ClubCategories.updated >= '] = $params['timestamp'];
            }
            //get server setting
            $settingListData = $this->ServerSettings->find('list',[
                'keyField' => 'constant_key',
                'valueField' => 'constant_value'
            ])->toArray();
            //get nation
            $nationListData = $this->Nations->find('all',[
                'conditions' =>  $conditonNation,
                'fields' => ['id', 'nation_name_jp', 'nation_name_en', 'updated', 'delete_flg'],
                'contain' => false
            ]);
            $data['timestamp'] = date('Y-m-d H:i:s');
            $data['private_policy_text'] = isset($settingListData['private_policy_text']) ? $settingListData['private_policy_text'] : '';
            $data['agreement_text'] = isset($settingListData['agreement_text']) ? $settingListData['agreement_text'] : '';
            $data['license_text'] = isset($settingListData['license_text']) ? $settingListData['license_text'] : '';
            $data['change_device_guide_text'] = isset($settingListData['change_device_guide_text']) ? $settingListData['change_device_guide_text'] : '';
            $data['nation_list'] = $nationListData;
            //get prefecture
            $preListData = $this->Prefectures->find('all',[
                'conditions' =>  $conditionPre,
                'fields' => ['id', 'nation_id', 'prefecture_name', 'prefecture_name_en', 'updated', 'delete_flg'],
                'contain' => false
            ]);
            $data['prefecture_list'] = $preListData;
            //get clubs
            $clubListData = $this->Clubs->find('all',[
                'conditions' =>  $conditonClub,
                'fields' => ['id', 'category_id', 'best_distance', 'type', 'distance', 'image_name', 'updated', 'delete_flg'],
                'contain' => false
            ]);
            $data['club_list'] = $clubListData;
            //get clubs category
            $clubCatListData = $this->ClubCategories->find('all',[
                'conditions' =>  $conditonClubCat,
                'fields' => ['id', 'category_name', 'category_name_en', 'category_color', 'updated', 'delete_flg'],
                'contain' => false
            ]);
            $data['club_category_list'] = $clubCatListData;
            //response all
            $output['status']   = API_CODE_OK;
            $output['message']  = API_CODE_MSG_SUCCESS;
            $output['data']     = $data;
            $this->renderJson($output);

        } else {
            $this->methodNotAllow();
        }
    }

}