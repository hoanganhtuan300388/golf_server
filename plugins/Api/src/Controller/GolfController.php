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
use \ZipArchive;
use Cake\Routing\Router;

class GolfController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->GolfFields           = TableRegistry::get('GolfFields');
        $this->Holes                = TableRegistry::get('Holes');
        $this->Greens               = TableRegistry::get('Greens');
        $this->Prefectures          = TableRegistry::get('Prefectures');
        $this->Nations              = TableRegistry::get('Nations');
        $this->UserGolfHistories    = TableRegistry::get('UserGolfHistories');
        $this->UserRounds           = TableRegistry::get('UserRounds');
        $this->UserCourses          = TableRegistry::get('UserCourses');
        $this->UserHoles            = TableRegistry::get('UserHoles');
        $this->UserShots            = TableRegistry::get('UserShots');
        $this->GolfUpdates          = TableRegistry::get('GolfUpdates');
        $this->UserClubs            = TableRegistry::get('UserClubs');
        $this->Courses              = TableRegistry::get('Courses');
    }
    
    /**
     * API IF014 ゴルフ場一覧取得
     */
    public function courseSearch() {
        if($this->request->is('get')) {
            //get param
            $output             = $this->getBaseOutputParams();
            $params             = $this->getParams();
            $golfFields         = [];

            $golfFieldSelect    = ['prefecture_id', 'prefecture_name' => 'Prefecture.prefecture_name', 'field_id', 'field_name', 'field_name_kana', 'field_name_en', 'address', 'field_long', 'field_lat'];

            if(!empty($params['golf_field_id'])) {
                $golfField = $this->GolfFields->find('all', [
                    'fields' => $golfFieldSelect,
                    'conditions' => [
                        'field_id'          => $params['golf_field_id'],
                        'last_version_flg'  => LAST_VERSION_ACTIVE
                    ]
                ])
                ->contain([
                    'Courses' => function ($q) {
                        return $q->select(['golf_field_id', 'course_id', 'course_name', 'hole_num', 'par_num'])->autoFields(false);
                    },
                    'Prefecture' => function ($q) {
                        return $q->autoFields(false);
                    }
                ])
                ->first();

                if(!empty($golfField)) {
                    $golfFields[] = $golfField->toArray();
                }
            } else {
                if (!empty($params['type']) && in_array($params['type'], [FIELD_SEARCH_GPS, FIELD_SEARCH_AREA, FIELD_SEARCH_HISTORY])) {
                    $conditions = [];
                    $limit      = empty($params['limit']) ? LIMIT_DEFAULT_50 : $params['limit'];
                    $page       = empty($params['page']) ? PAGE_DEFAULT : $params['page'];

                    //search history
                    if ($params['type'] == FIELD_SEARCH_HISTORY) {
                        $userGolfHistories = $this->UserGolfHistories->find();
                        $userGolfHistories->where([
                            'user_account_id'               => $this->account['id'],
                            'GolfField.last_version_flg'    => LAST_VERSION_ACTIVE
                        ]);
                        $userGolfHistories->group(['user_account_id', 'm_field_id']);
                        $userGolfHistories->order(['UserGolfHistories.updated' => 'desc']);
                        $userGolfHistories->contain([
                            'GolfField' => function ($q) {
                                return $q->contain([
                                    'Courses' => function ($q) {
                                        return $q->select(['golf_field_id', 'course_id', 'course_name', 'hole_num', 'par_num'])->autoFields(false);
                                    },
                                    'Prefecture' => function ($q) {
                                        return $q->autoFields(false);
                                    }
                                ])
                                    ->autoFields(false);
                            }
                        ]);
                        $userGolfHistories->limit($limit);
                        $userGolfHistories->page($page);

                        $golfHistories  = $userGolfHistories->hydrate(false)->toArray();
                        $data           = [];

                        foreach($golfHistories as $key => $golfHistory) {
                            $data[] = [
                                'prefecture_id'     => $golfHistory['golf_field']['prefecture_id'],
                                'prefecture_name'   => $golfHistory['golf_field']['prefecture']['prefecture_name'],
                                'field_id'          => $golfHistory['golf_field']['field_id'],
                                'field_name'        => $golfHistory['golf_field']['field_name'],
                                'field_name_kana'   => $golfHistory['golf_field']['field_name_kana'],
                                'field_name_en'     => $golfHistory['golf_field']['field_name_en'],
                                'address'           => $golfHistory['golf_field']['address'],
                                'field_long'        => $golfHistory['golf_field']['field_long'],
                                'field_lat'         => $golfHistory['golf_field']['field_lat'],
                                'courses'           => $golfHistory['golf_field']['courses']
                            ];
                        }

                        $output['status']   = API_CODE_OK;
                        $output['message']  = API_CODE_MSG_SUCCESS;
                        $output['data']     = $data;
                        $this->renderJson($output);
                        exit;
                    }

                    $golfFields = $this->GolfFields->find();
                    $golfFields->select($golfFieldSelect);

                    //search area
                    if ($params['type'] == FIELD_SEARCH_AREA && !empty($params['prefecture_id'])) {
                        $conditions['prefecture_id'] = $params['prefecture_id'];
                    }

                    //search gps
                    if ($params['type'] == FIELD_SEARCH_GPS && !empty($params['lat']) && !empty($params['long'])) {
                        //$distanceField = '(3959 * acos (cos ( radians(:latitude) ) * cos( radians( GolfFields.field_lat ) ) * cos( radians( GolfFields.field_long  ) - radians(:longitude) ) + sin ( radians(:latitude) ) * sin( radians( GolfFields.field_lat ) )))';
                        $distanceField = '(111.111 * DEGREES(acos(cos(radians(:latitude)) * cos(radians(field_lat)) * cos(radians(field_long) - radians(:longitude)) + sin( radians(:latitude)) * sin(radians(field_lat)))))';

                        $conditions['field_lat IS NOT'] = null;
                        $conditions['field_long IS NOT'] = null;
                        $golfFields->select(['distance' => $distanceField]);
                        $golfFields->having(["distance < " => DISTANCE_KM]);
                        $golfFields->bind(':latitude', $params['lat'], 'float');
                        $golfFields->bind(':longitude', $params['long'], 'float');
                        $golfFields->order(['distance' => 'asc']);
                    }

                    $conditions['last_version_flg'] = LAST_VERSION_ACTIVE;

                    $golfFields->where($conditions);
                    $golfFields->contain([
                        'Courses' => function ($q) {
                            return $q->select(['golf_field_id', 'course_id', 'course_name', 'hole_num', 'par_num'])->autoFields(false);
                        },
                        'Prefecture' => function ($q) {
                            return $q->autoFields(false);
                        }
                    ]);
                    $golfFields->limit($limit);
                    $golfFields->page($page);

                    $golfFields = $golfFields->hydrate(false)->toArray();
                }
            }

            $output['status']   = API_CODE_OK;
            $output['message']  = API_CODE_MSG_SUCCESS;
            $output['data']     = $golfFields;
            $this->renderJson($output);

        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * API IF015 ゴルフ場のデータ取得
     */
    public function courseDataGet() {
        //khi nao dung account_id session thi bo di
        //$this->account['id'] = 1;
        if($this->request->is('get')) {
            //get param
            $output = $this->getBaseOutputParams();
            $params = $this->getParams();

            if (empty($params['field_id'])) {
                $this->_errors['field_id'] = __('Golf field is invalid');
            }
            if(!isset($params['delete_flg']) || (isset($params['delete_flg']) && $params['delete_flg'] != 1)){
                //case create
                if (empty($params['course_1st_id']) && empty($params['course_2st_id'])) {
                    $this->_errors['course_st_id'] = __('Course is invalid');
                }
                //case edit
                if (empty($params['course_2st_id']) && !empty($params['round_id'])) {
                    $this->_errors['course_st_id'] = __('Course is invalid');
                }
            }

            if (!empty($this->_errors)) {
                $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $this->_errors);
            }
            $data = array();
            //check version
            $golfFields = $this->GolfFields->find('all',[
                'conditions' => [
                    'GolfFields.field_id'           => $params['field_id'],
                    'GolfFields.last_version_flg'   => LAST_VERSION_ACTIVE,
                    'GolfFields.delete_flg'         => DELETE_FLG_ACTIVE
                ],
                'fields' => [
                    'version','field_name','field_name_kana','field_name_en', 'field_id'
                ],
                'contain' => false
            ])->first();
            $maxVersionArr = json_decode(json_encode($golfFields), true);
            //Create Round
            $conn = ConnectionManager::get('default');
            $conn->begin();
            $transaction = 0;
            if (empty($params['round_id'])){
                if(isset($maxVersionArr['version']) && $maxVersionArr['version'] > 0){
                    //get info golf & course
                    $courseSt = $coursesListData1 = $coursesListData2 = $coursesListData = array();
                    if(isset($params['course_1st_id']) && $params['course_1st_id'] > 0){
                        $courseSt[] = $params['course_1st_id'];
                        $coursesListData1 = $this->Courses->find('all',[
                            'conditions' => [
                                'Courses.golf_field_id'         => $params['field_id'],
                                'Courses.course_id'             => $params['course_1st_id'],
                                'Courses.last_version_flg'      => LAST_VERSION_ACTIVE,
                                'Courses.delete_flg'            => DELETE_FLG_ACTIVE
                            ],
                            'fields' => ['golf_field_id', 'course_id', 'version', 'hole_num', 'par_num', 'course_name', 'course_name_kana', 'course_name_en'],
                            'contain' => false
                        ])->first();
                        $coursesListData1 = json_decode(json_encode($coursesListData1), true);
                        if(count($coursesListData1) > 0) $coursesListData[] = $coursesListData1;
                    }
                    if(isset($params['course_2st_id']) && $params['course_2st_id'] > 0){
                        $courseSt[] = $params['course_2st_id'];
                        $coursesListData2 = $this->Courses->find('all',[
                            'conditions' => [
                                'Courses.golf_field_id'         => $params['field_id'],
                                'Courses.course_id'             => $params['course_2st_id'],
                                'Courses.last_version_flg'      => LAST_VERSION_ACTIVE,
                                'Courses.delete_flg'            => DELETE_FLG_ACTIVE
                            ],
                            'fields' => ['golf_field_id', 'course_id', 'version', 'hole_num', 'par_num', 'course_name', 'course_name_kana', 'course_name_en'],
                            'contain' => false
                        ])->first();
                        $coursesListData2 = json_decode(json_encode($coursesListData2), true);
                        if(count($coursesListData2) > 0) $coursesListData[] = $coursesListData2;
                    }
                    if(count($coursesListData) > 0 && count($coursesListData) == count($courseSt) ){
                        //total hole_num, parnum
                        $hole_num = $par_num = $type = 0;
                        foreach($coursesListData as $key => $value){
                            $hole_num = $hole_num + $value['hole_num'];
                            $par_num = $par_num + $value['par_num'];
                            $type = ($key+1);
                        }
                        //create Round
                        $dataRound = array(
                            'user_account_id'       => $this->account['id'],
                            'm_field_id'            => $params['field_id'],
                            'm_field_version'       => $maxVersionArr['version'],
                            'field_name'            => $maxVersionArr['field_name'],
                            'field_name_kana'       => $maxVersionArr['field_name_kana'],
                            'field_name_en'         => $maxVersionArr['field_name_en'],
                            'status'                => 0,
                            'type'                  => $type,
                            'hole_num'              => $hole_num,
                            'par_num'               => $par_num
                        );
                        if(isset($params['round_date']) && $params['round_date'] != '') $dataRound['start_at'] = $params['round_date'];
                        $UserRoundsModel = $this->UserRounds->newEntity($dataRound);
                        if ($this->UserRounds->save($UserRoundsModel)) {
                            //check exit history
                            $checkUserGolfHistories = $this->UserGolfHistories->find('all', [
                                'conditions' => [
                                    'user_account_id'       => $this->account['id'],
                                    'm_field_id'            => $params['field_id'],
                                    'delete_flg'            => DELETE_FLG_ACTIVE
                                ]
                            ])->toArray();
                            if(count($checkUserGolfHistories) == 0){
                                //create golf history
                                $UserHistoryModel = $this->UserGolfHistories->newEntity(['user_account_id' => $this->account['id'], 'm_field_id' => $params['field_id']]);
                                if (!$this->UserGolfHistories->save($UserHistoryModel)) {
                                    $transaction = 1;
                                }
                            }
                            if ($transaction == 0) {
                                $roundId = $UserRoundsModel->id;
                                $data['round_id']               = $roundId;
                                $data['m_field_id']             = $params['field_id'];
                                $data['m_field_version']        = $maxVersionArr['version'];
                                $data['field_name']             = $maxVersionArr['field_name'];
                                $data['field_name_kana']        = $maxVersionArr['field_name_kana'];
                                $data['field_name_en']          = $maxVersionArr['field_name_en'];
                                $data['status']                 = 0;
                                $data['type']                   = $type;
                                $data['hole_num']               = $hole_num;
                                $data['par_num']                = $par_num;
                                //create course
                                foreach($coursesListData as $key => $value){
                                    $dataCourse = array(
                                        'user_account_id'       => $this->account['id'],
                                        'user_round_id'         => $roundId,
                                        'm_course_version'      => $value['version'],
                                        'm_course_id'           => $value['course_id'],
                                        'm_course_name'         => $value['course_name'],
                                        'm_course_name_kana'    => $value['course_name_kana'],
                                        'm_course_name_en'      => $value['course_name_en'],
                                        'type'                  => ($key + 1),
                                        'status'                => 1
                                    );
                                    $UserCoursesModel = $this->UserCourses->newEntity($dataCourse);
                                    if ($this->UserCourses->save($UserCoursesModel)) {
                                        $courseId = $UserCoursesModel->id;
                                        $data['courses'][$key]['user_course_id']        = $courseId;
                                        $data['courses'][$key]['user_round_id']         = $roundId;
                                        $data['courses'][$key]['m_course_version']      = $value['version'];
                                        $data['courses'][$key]['m_course_id']           = $value['course_id'];
                                        $data['courses'][$key]['m_course_name']         = $value['course_name'];
                                        $data['courses'][$key]['m_course_name_kana']    = $value['course_name_kana'];
                                        $data['courses'][$key]['m_course_name_en']      = $value['course_name_en'];
                                        $data['courses'][$key]['type']                  = ($key + 1);
                                        //create Hole 1
                                        //Get data master hole 1
                                        $holesList = $this->Holes->find('all',[
                                            'conditions' => [
                                                'Holes.golf_field_id'       => $params['field_id'],
                                                'Holes.course_id'           => $value['course_id'],
                                                'Holes.last_version_flg'    => LAST_VERSION_ACTIVE,
                                                'Holes.delete_flg'          => DELETE_FLG_ACTIVE
                                            ],
                                            'fields' => [
                                                'golf_field_id', 'course_id', 'hole_id', 'version', 'last_version_flg', 'hole_name', 'par_num', 'tee_long', 'tee_lat'
                                            ],
                                            'contain' => false
                                        ]);
                                        $holesList = json_decode(json_encode($holesList), true);
                                        if(count($holesList) > 0){
                                            $dataHole = array();
                                            foreach($holesList as $ho => $vho){
                                                $dataHole[] = array(
                                                    'user_account_id'           => $this->account['id'],
                                                    'user_round_id'             => $roundId,
                                                    'user_course_id'            => $courseId,
                                                    'm_hole_version'            => $vho['version'],
                                                    'm_hole_id'                 => $vho['hole_id'],
                                                    'hole_name'                 => $vho['hole_name'],
                                                    'master_par_num'            => $vho['par_num'],
                                                    'tee_long'                  => $vho['tee_long'],
                                                    'tee_lat'                   => $vho['tee_lat'],
                                                    'status'                    => 1
                                                );
                                            }
                                            $holeSave = $this->UserHoles->newEntities($dataHole);
                                            if($this->UserHoles->saveMany($holeSave)){
                                                //get info green
                                                $holeSave = json_decode(json_encode($holeSave), true);
                                                if(count($holeSave) > 0){
                                                    foreach($holeSave as $h => $v){
                                                        unset($holeSave[$h]['created']);
                                                        unset($holeSave[$h]['modified']);
                                                        $conditionhole = [
                                                            'Greens.golf_field_id'      => $params['field_id'],
                                                            'Greens.course_id'          => $params['course_'.($key+1).'st_id'],
                                                            'Greens.hole_id'            => $v['m_hole_id'],
                                                            'Greens.last_version_flg'   => LAST_VERSION_ACTIVE,
                                                            'Greens.delete_flg'         => DELETE_FLG_ACTIVE
                                                        ];
                                                        $greens = $this->Greens->find('all',[
                                                            'conditions' => $conditionhole,
                                                            'fields' => [
                                                                'golf_field_id', 'course_id', 'hole_id', 'green_id', 'version', 'last_version_flg', 'front_lat', 'front_long', 'centre_lat', 'centre_long', 'back_lat', 'back_long'
                                                            ],
                                                            'contain' => false
                                                        ]);
                                                        $greens = json_decode(json_encode($greens), true);
                                                        if(count($greens) > 0){
                                                            $holeSave[$h]['greens'] = $greens;
                                                        }else{
                                                            $holeSave[$h]['greens'] = array();
                                                        }
                                                    }
                                                    $data['courses'][$key]['holes'] = $holeSave;
                                                }
                                            }else{
                                                $transaction = 1;
                                            }
                                            //end save hole
                                        }
                                    }else{
                                        $transaction = 1;
                                    }
                                    //end save course
                                }
                            }else{
                                $transaction = 1;
                            }
                            //end golf history
                        }else{
                            $transaction = 1;
                        }
                        //end save round
                    }else{
                        $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, array(__('Course does not exits')));
                    }
                }else{
                    $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, array(__('Golf does not exist')));
                }
            //Update Round, Create course, delete round
            }else{
                $conditionround = [
                    'UserRounds.user_account_id'        => $this->account['id'],
                    'UserRounds.id'                     => $params['round_id'],
                    'UserRounds.delete_flg'             => DELETE_FLG_ACTIVE
                ];
                $rounds = $this->UserRounds->find('all',[
                    'conditions' => $conditionround,
                    'fields' => [
                        'id', 'user_account_id', 'm_field_id', 'm_field_version', 'status', 'hole_num', 'par_num'
                    ],
                    'contain' => false
                ])->first();
                $roundData = json_decode(json_encode($rounds), true);
                if(count($roundData) > 0) {
                    //delete round
                    if (isset($params['delete_flg']) && $params['delete_flg'] == 1) {
                        if(!$this->_deleteRound($roundData['id'])){
                            $transaction = 1;
                        }
                    }else{
                        //Update Round, Create course
                        //check course
                        $usercoursesListData = $this->UserCourses->find('all', [
                            'conditions' => [
                                'UserCourses.user_account_id'       => $this->account['id'],
                                'UserCourses.user_round_id'         => $params['round_id'],
                                'UserCourses.delete_flg'            => DELETE_FLG_ACTIVE
                            ],
                            'fields' => ['id', 'user_round_id', 'user_account_id', 'm_course_version', 'm_course_id'],
                            'contain' => false
                        ]);
                        $usercoursesListData = json_decode(json_encode($usercoursesListData), true);
                        if (count($usercoursesListData) == 0) {
                            $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, array(__('Course_2 is not allowed to update')));
                        } elseif (count($usercoursesListData) == 2) {
                            $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, array(__('Course max 2 course')));
                        } else {
                            //get data master course
                            $masterCoursesListData = $this->Courses->find('all', [
                                'conditions' => [
                                    'Courses.golf_field_id'             => $params['field_id'],
                                    'Courses.course_id'                 => $params['course_2st_id'],
                                    'Courses.last_version_flg'          => LAST_VERSION_ACTIVE,
                                    'Courses.delete_flg'                => DELETE_FLG_ACTIVE
                                ],
                                'fields' => ['golf_field_id', 'course_id', 'version', 'hole_num', 'par_num', 'course_name', 'course_name_kana', 'course_name_en'],
                                'contain' => false
                            ])->first();
                            $masterCoursesListData = json_decode(json_encode($masterCoursesListData), true);
                            $dataRoundUpdate = array(
                                'type' => 2,
                                'hole_num' => ($roundData['hole_num'] + $masterCoursesListData['hole_num']),
                                'par_num' => ($roundData['par_num'] + $masterCoursesListData['par_num']),
                            );
                            if (isset($params['round_date']) && $params['round_date'] != '') $dataRoundUpdate['start_at'] = $params['round_date'];
                            $UserRoundsUpdateModel = $this->UserRounds->patchEntity($rounds, $dataRoundUpdate);
                            //update hole_number, par_number table round
                            if ($this->UserRounds->save($UserRoundsUpdateModel)) {
                                $data['round_id']           = $roundData['id'];
                                $data['m_field_id']         = $roundData['m_field_id'];
                                $data['m_field_version']    = $roundData['m_field_version'];
                                $data['field_name']         = $maxVersionArr['field_name'];
                                $data['field_name_kana']    = $maxVersionArr['field_name_kana'];
                                $data['field_name_en']      = $maxVersionArr['field_name_en'];
                                $data['status']             = $roundData['status'];
                                $data['type']               = 2;
                                $data['hole_num']           = ($roundData['hole_num'] + $masterCoursesListData['hole_num']);
                                $data['par_num']            = ($roundData['par_num'] + $masterCoursesListData['par_num']);
                                //update course 2
                                $dataCourse = array(
                                    'user_account_id'       => $this->account['id'],
                                    'user_round_id'         => $params['round_id'],
                                    'm_course_version'      => $masterCoursesListData['version'],
                                    'm_course_id'           => $masterCoursesListData['course_id'],
                                    'm_course_name'         => $masterCoursesListData['course_name'],
                                    'm_course_name_kana'    => $masterCoursesListData['course_name_kana'],
                                    'm_course_name_en'      => $masterCoursesListData['course_name_en'],
                                    'type'                  => 2,
                                    'status'                => 1
                                );
                                $UserCoursesModel = $this->UserCourses->newEntity($dataCourse);
                                //create course 2
                                if ($this->UserCourses->save($UserCoursesModel)) {
                                    $courseId = $UserCoursesModel->id;
                                    $data['courses'][0]['user_course_id']       = $courseId;
                                    $data['courses'][0]['user_round_id']        = $params['round_id'];
                                    $data['courses'][0]['m_course_version']     = $masterCoursesListData['version'];
                                    $data['courses'][0]['m_course_name']        = $masterCoursesListData['course_name'];
                                    $data['courses'][0]['m_course_name_kana']   = $masterCoursesListData['course_name_kana'];
                                    $data['courses'][0]['m_course_name_en']     = $masterCoursesListData['course_name_en'];
                                    $data['courses'][0]['m_course_id']          = $params['course_2st_id'];
                                    $data['courses'][0]['type']                 = 2;
                                    //Get data master hole 2
                                    $holesList = $this->Holes->find('all', [
                                        'conditions' => [
                                            'Holes.golf_field_id'       => $params['field_id'],
                                            'Holes.course_id'           => $params['course_2st_id'],
                                            'Holes.last_version_flg'    => LAST_VERSION_ACTIVE,
                                            'Holes.delete_flg'          => DELETE_FLG_ACTIVE
                                        ],
                                        'fields' => [
                                            'golf_field_id', 'course_id', 'hole_id', 'version', 'last_version_flg', 'hole_name', 'par_num', 'tee_long', 'tee_lat'
                                        ],
                                        'contain' => false
                                    ]);
                                    $holesList = json_decode(json_encode($holesList), true);
                                    if (count($holesList) > 0) {
                                        $dataHole = array();
                                        foreach ($holesList as $ho => $vho) {
                                            $dataHole[] = array(
                                                'user_account_id'       => $this->account['id'],
                                                'user_round_id'         => $params['round_id'],
                                                'user_course_id'        => $courseId,
                                                'm_hole_version'        => $vho['version'],
                                                'm_hole_id'             => $vho['hole_id'],
                                                'hole_name'             => $vho['hole_name'],
                                                'master_par_num'        => $vho['par_num'],
                                                'tee_long'              => $vho['tee_long'],
                                                'tee_lat'               => $vho['tee_lat'],
                                                'status'                => 1
                                            );
                                        }
                                        $holeSave2 = $this->UserHoles->newEntities($dataHole);
                                        //create Hole 2
                                        if ($this->UserHoles->saveMany($holeSave2)) {
                                            //get info green
                                            $holeSave2 = json_decode(json_encode($holeSave2), true);
                                            if (count($holeSave2) > 0) {
                                                foreach ($holeSave2 as $h => $v) {
                                                    unset($holeSave2[$h]['created']);
                                                    unset($holeSave2[$h]['modified']);
                                                    $conditionhole = [
                                                        'Greens.golf_field_id'      => $params['field_id'],
                                                        'Greens.course_id'          => $params['course_2st_id'],
                                                        'Greens.hole_id'            => $v['m_hole_id'],
                                                        'Greens.last_version_flg'   => LAST_VERSION_ACTIVE,
                                                        'Greens.delete_flg'         => DELETE_FLG_ACTIVE
                                                    ];
                                                    $greens = $this->Greens->find('all', [
                                                        'conditions' => $conditionhole,
                                                        'fields' => [
                                                            'golf_field_id', 'course_id', 'hole_id', 'green_id', 'version', 'last_version_flg', 'front_lat', 'front_long', 'centre_lat', 'centre_long', 'back_lat', 'back_long'
                                                        ],
                                                        'contain' => false
                                                    ]);
                                                    $greens = json_decode(json_encode($greens), true);
                                                    if (count($greens) > 0) {
                                                        $holeSave2[$h]['greens'] = $greens;
                                                    } else {
                                                        $holeSave2[$h]['greens'] = array();
                                                    }
                                                }
                                                $data['courses'][0]['holes'] = $holeSave2;
                                            }
                                        } else {
                                            $transaction = 1;
                                        }
                                        //end save hole
                                    }
                                } else {
                                    $transaction = 1;
                                }
                                //end save course 2
                            } else {
                                $transaction = 1;
                            }
                            //end update round
                        }
                    }
                }else{
                    $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, array(__('Round does not exist')));
                }
            }
            //save error
            if($transaction == 1){
                $data = [];
                $conn->rollback();
                $output['status']   = API_CODE_NG;
                $output['message']  = API_CODE_MSG_FAIL;
            }else{
                $output['status']   = API_CODE_OK;
                $output['message']  = API_CODE_MSG_SUCCESS;
                $conn->commit();
            }
            $output['data']     = $data;
            $this->renderJson($output);

        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * API IF016 ラウンド終了
     */
    public function finishRound() {
        if($this->request->is('post')) {
            //get param
            $output = $this->getBaseOutputParams();
            $params = $this->getParams();

            $conn = ConnectionManager::get('default');
            $conn->begin();

            $currentDate = $this->getCurrentDate();

            $saveStatus = $this->__finishUpdateRound($params, $currentDate);

            if($saveStatus == true) {
                $conn->commit();
                $output['status']   = API_CODE_OK;
                $output['message']  = API_CODE_MSG_SUCCESS;
                $output['data']     = [
                    'end_at' => $currentDate
                ];
            } else {
                $conn->rollback();
                $output['status']   = API_CODE_NG;
                $output['message']  = API_CODE_MSG_FAIL;
            }

            $this->renderJson($output);
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * API IF017 クラグリスト取得
     */
    public function getClubs() {
        if($this->request->is('get')) {
            //get param
            $output     = $this->getBaseOutputParams();

            $userClubs  = $this->UserClubs->find('all', [
                'fields' => [
                    'id',
                    'maker_name',
                    'type',
                    'nick_name',
                    'distance',
                    'bag_in_flg',
                    'delete_flg'
                ],
                'conditions' => ['user_account_id' => $this->account['id']]
            ]);

            $output['status']   = API_CODE_OK;
            $output['message']  = API_CODE_MSG_SUCCESS;
            $output['data']     = $userClubs;
            $this->renderJson($output);
        }  else {
            $this->methodNotAllow();
        }
    }

    /**
     * API IF018 クラグリスト更新
     */
    public function updateClub() {
        if($this->request->is('post')) {
            //get param
            $output     = $this->getBaseOutputParams();
            $params     = $this->getParams();

            $clubList   = [];

            if (!empty($params['id'])) {
                $clubList[] = $params;
            } else {
                $clubList   = $params;
            }

            $conn = ConnectionManager::get('default');
            $conn->begin();

            $result = $this->updateClubProcess($clubList);

            if($result['status'] == true) {
                $conn->commit();
                $output['status']   = API_CODE_OK;
                $output['message']  = API_CODE_MSG_SUCCESS;
                $this->renderJson($output);
            } else {
                if (!empty($result['id'])) {
                    $this->sendError(__('{0} {1} update failed', __('club'), $result['id']), API_CODE_100, API_HTTP_CODE_200, $result['errors']);
                } else {
                    $this->sendError(__('{0} is required', 'ID'), API_CODE_100, API_HTTP_CODE_200);
                }
            }
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * @param array $clubs
     * @return array
     */
    public function updateClubProcess($clubs = []) {
        $result = [
            'status'    => true,
            'id'        => null,
            'errors'    => []
        ];

        foreach($clubs as $params) {
            if (empty($params['id'])) {
                $result['status'] = false;
                break;
            }

            $userClub = $this->UserClubs->find('all', [
                'conditions' => [
                    'id'                => $params['id'],
                    'user_account_id'   => $this->account['id']
                ]
            ])->first();

            $userClubData['m_club_id']      = isset($params['m_club_id']) ? $params['m_club_id'] : null;
            $userClubData['best_distance']  = isset($params['best_distance']) ? $params['best_distance'] : null;
            $userClubData['maker_name']     = isset($params['maker_name']) ? $params['maker_name'] : null;
            $userClubData['type']           = isset($params['type']) ? $params['type'] : null;
            $userClubData['nick_name']      = isset($params['nick_name']) ? $params['nick_name'] : null;
            $userClubData['distance']       = isset($params['distance']) ? $params['distance'] : null;
            $userClubData['accuracy']       = isset($params['accuracy']) ? $params['accuracy'] : null;
            $userClubData['bag_in_flg']     = isset($params['bag_in_flg']) ? $params['bag_in_flg'] : null;
            $userClubData['sum_shots']      = isset($params['sum_shots']) ? $params['sum_shots'] : null;
            $userClubData['hit_shots']      = isset($params['hit_shots']) ? $params['hit_shots'] : null;
            $userClubData['delete_flg']     = isset($params['delete_flg']) ? $params['delete_flg'] : 0;

            if (empty($userClub)) {
                $userClubData['id']                 = $params['id'];
                $userClubData['user_account_id']    = $this->account['id'];
                $userClubEntity                     = $this->UserClubs->newEntity($userClubData);
            } else {
                $userClubEntity = $this->UserClubs->patchEntity($userClub, $userClubData);
            }

            if (!$this->UserClubs->save($userClubEntity)) {
                $result['status']   = false;
                $result['id']       = $params['id'];
                $result['errors']   = $userClubEntity->errors();
                break;
            }
        }

        return $result;
    }

    /**
     * API IF031 ユーザラウンド実績データダウンロード
     */
    public function downloadRoundData() {
        if($this->request->is('post')) {
            //get param
            $output         = $this->getBaseOutputParams();
            $params         = $this->getParams();

            $deleteFileFlag = !empty($params['downloaded_flg']) ? true : false;
            $downloadUrl    = '';

            $filename       = 'user_' . $this->account['id'];
            $dirFile        = WWW_ROOT . 'files' . DS . 'zips' . DS . $filename . '.zip';

            if($deleteFileFlag == false) {
                $jsonContent    = '';
                $roundsData     = $this->__getRoundsData();

                if(!empty($roundsData)) {
                    $jsonContent = json_encode($roundsData);
                }

                $zip = new ZipArchive();

                if ($zip->open($dirFile, ZipArchive::CREATE) !== TRUE) {
                    exit("cannot open <$dirFile>\n");
                }

                $zip->addFromString($filename . '.json', $jsonContent);

                $zip->close();

                $downloadUrl = Router::url('/', true) . 'files/zips/' . $filename . '.zip';
            } else {
                if(file_exists($dirFile)) {
                    unlink($dirFile);
                }
            }

            $output['status']   = API_CODE_OK;
            $output['message']  = API_CODE_MSG_SUCCESS;
            $output['data']     = [
                'download_url' => $downloadUrl
            ];
            $this->renderJson($output);
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * @param $roundParams
     * @return bool
     */
    private function __finishUpdateRound($roundParams, $currentDate) {
        if (empty($roundParams['id'])) {
            $this->_errors['id'] = __('{0} is required', __('round_id'));
        }

        if (!empty($this->_errors)) {
            $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $this->_errors);
        }

        $round = $this->UserRounds->get($roundParams['id']);

        if(empty($round->toArray())) {
            $this->sendError(__('{0} {1} does not exist', __('round_id'), $roundParams['id']), API_CODE_101, API_HTTP_CODE_200, $this->_errors);
        }

        $roundData = [
            'user_account_id'           => $this->account['id'],
            'm_field_id'                => isset($roundParams['m_field_id']) ? $roundParams['m_field_id'] : null,
            'm_field_version'           => isset($roundParams['m_field_version']) ? $roundParams['m_field_version'] : null,
            'status'                    => 2,//isset($roundParams['status']) ? $roundParams['status'] : null,
            'score'                     => isset($roundParams['score']) ? $roundParams['score'] : null,
            'putt_num'                  => isset($roundParams['putt_num']) ? $roundParams['putt_num'] : null,
            'fairway_keep_num'          => isset($roundParams['fairway_keep_num']) ? $roundParams['fairway_keep_num'] : null,
            'hole_num'                  => isset($roundParams['hole_num']) ? $roundParams['hole_num'] : null,
            'par_num'                   => isset($roundParams['par_num']) ? $roundParams['par_num'] : null,
            'sand_save_num'             => isset($roundParams['sand_save_num']) ? $roundParams['sand_save_num'] : null,
            'scramble_num'              => isset($roundParams['scramble_num']) ? $roundParams['scramble_num'] : null,
            'par_on_num'                => isset($roundParams['par_on_num']) ? $roundParams['par_on_num'] : null,
            'weather'                   => isset($roundParams['weather']) ? $roundParams['weather'] : null,
            'temperature'               => isset($roundParams['temperature']) ? $roundParams['temperature'] : null,
            'wind'                      => isset($roundParams['wind']) ? $roundParams['wind'] : null,
            'longest_drive_distance'    => isset($roundParams['longest_drive_distance']) ? $roundParams['longest_drive_distance'] : null,
            'current_lat'               => isset($roundParams['current_lat']) ? $roundParams['current_lat'] : null,
            'current_long'              => isset($roundParams['current_long']) ? $roundParams['current_long'] : null,
            'calibration_lat'           => isset($roundParams['calibration_lat']) ? $roundParams['calibration_lat'] : null,
            'calibration_long'          => isset($roundParams['calibration_long']) ? $roundParams['calibration_long'] : null,
            'start_at'                  => isset($roundParams['start_at']) ? $roundParams['start_at'] : null,
            'end_at'                    => $currentDate,
            'recovery_scramble_rate'    => isset($roundParams['recovery_scramble_rate']) ? $roundParams['recovery_scramble_rate'] : null,
            'setting_calibration_flg'   => isset($roundParams['setting_calibration_flg']) ? $roundParams['setting_calibration_flg'] : null,
            'bogey_on_num'              => isset($roundParams['bogey_on_num']) ? $roundParams['bogey_on_num'] : null,
            'sum_putt_of_par_on'        => isset($roundParams['sum_putt_of_par_on']) ? $roundParams['sum_putt_of_par_on'] : null,
            'putting_gir_rate'          => isset($roundParams['putting_gir_rate']) ? $roundParams['putting_gir_rate'] : null,
            'recovery_sandsave_rate'    => isset($roundParams['recovery_sandsave_rate']) ? $roundParams['recovery_sandsave_rate'] : null,
            'distance_deviation'        => isset($roundParams['distance_deviation']) ? $roundParams['distance_deviation'] : null,
            'bearing_deviation'         => isset($roundParams['bearing_deviation']) ? $roundParams['bearing_deviation'] : null

        ];

        $roundEntity = $this->UserRounds->patchEntity($round, $roundData);

        if($this->UserRounds->save($roundEntity)) {
            $courses    = isset($roundParams['user_courses']) ? $roundParams['user_courses'] : [];
            $pars       = isset($roundParams['pars']) ? $roundParams['pars'] : [];

            foreach ($courses as $key => $courseParams) {
                $this->__finishUpdateCourse($courseParams);
            }

            foreach ($pars as $key => $parParams) {
                $this->__finishUpdatePar($parParams);
            }
        } else {
            $this->sendError(__('{0} {1} update failed', __('round_id'), $roundParams['id']), API_CODE_100, API_HTTP_CODE_200, $courseEntity->errors());
        }

        return true;
    }

    /**
     * @param $courseParams
     * @return bool
     */
    private function __finishUpdateCourse($courseParams) {
        if (empty($courseParams['id'])) {
            $this->_errors['id'] = __('{0} is required', __('course_id'));
        }

        if (!empty($this->_errors)) {
            $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $this->_errors);
        }

        $course = $this->UserCourses->find('all', [
            'conditions' => [
                'id' => $courseParams['id']
            ]
        ])->first();

        if(empty($course)) {
            $this->sendError(__('{0} {1} does not exist', __('course_id'), $courseParams['id']), API_CODE_101, API_HTTP_CODE_200, $this->_errors);
        }

        $courseData = [
            'user_round_id'     => isset($courseParams['user_round_id']) ? $courseParams['user_round_id'] : null,
            'user_account_id'   => $this->account['id'],
            'm_course_version'  => isset($courseParams['m_course_version']) ? $courseParams['m_course_version'] : null,
            'm_course_id'       => isset($courseParams['m_course_id']) ? $courseParams['m_course_id'] : null,
            'type'              => isset($courseParams['type']) ? $courseParams['type'] : null,
            'status'            => isset($courseParams['status']) ? $courseParams['status'] : null
        ];

        $courseEntity = $this->UserRounds->patchEntity($course, $courseData);

        if($this->UserCourses->save($courseEntity)) {
            $holes = isset($courseParams['user_holes']) ? $courseParams['user_holes'] : [];

            foreach ($holes as $key => $holeParams) {
                $this->__finishUpdateHole($holeParams);
            }
        } else {
            $this->sendError(__('{0} {1} update failed', __('course_id'), $courseParams['id']), API_CODE_100, API_HTTP_CODE_200, $courseEntity->errors());
        }

        return true;
    }

    /**
     * @param $holeParams
     * @return bool
     */
    private function __finishUpdateHole($holeParams) {
        if (empty($holeParams['id'])) {
            $this->_errors['id'] = __('{0} is required', __('hole_id'));
        }

        if (!empty($this->_errors)) {
            $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $this->_errors);
        }

        $hole = $this->UserHoles->find('all', [
            'conditions' => [
                'id' => $holeParams['id']
            ]
        ])->first();

        if(empty($hole)) {
            $this->sendError(__('{0} {1} does not exist', __('hole_id'), $holeParams['id']), API_CODE_101, API_HTTP_CODE_200, $this->_errors);
        }

        $holeData = [
            'user_round_id'        => isset($holeParams['user_round_id']) ? $holeParams['user_round_id'] : null,
            'user_course_id'       => isset($holeParams['user_course_id']) ? $holeParams['user_course_id'] : null,
            'user_account_id'      => $this->account['id'],
            'm_hole_version'       => isset($holeParams['m_hole_version']) ? $holeParams['m_hole_version'] : null,
            'm_hole_id'            => isset($holeParams['m_hole_id']) ? $holeParams['m_hole_id'] : null,
            'hole_name'            => isset($holeParams['hole_name']) ? $holeParams['hole_name'] : null,
            'status'               => isset($holeParams['status']) ? $holeParams['status'] : null,
            'hole_stage'           => isset($holeParams['hole_stage']) ? $holeParams['hole_stage'] : null,
            'score_num'            => isset($holeParams['score_num']) ? $holeParams['score_num'] : null,
            'stroke_num'           => isset($holeParams['stroke_num']) ? $holeParams['stroke_num'] : null,
            'putt_num'             => isset($holeParams['putt_num']) ? $holeParams['putt_num'] : null,
            'sand_save_flg'        => isset($holeParams['sand_save_flg']) ? $holeParams['sand_save_flg'] : null,
            'scramble_flg'         => isset($holeParams['scramble_flg']) ? $holeParams['scramble_flg'] : null,
            'bogey_on_flg'         => isset($holeParams['bogey_on_flg']) ? $holeParams['bogey_on_flg'] : null,
            'guard_bunker_flg'     => isset($holeParams['guard_bunker_flg']) ? $holeParams['guard_bunker_flg'] : null,
            'fairway_status'       => isset($holeParams['fairway_status']) ? $holeParams['fairway_status'] : null,
            'par_num'              => isset($holeParams['par_num']) ? $holeParams['par_num'] : null,
            'penalty_num'          => isset($holeParams['penalty_num']) ? $holeParams['penalty_num'] : null,
            'bunker_shot_num'      => isset($holeParams['bunker_shot_num']) ? $holeParams['bunker_shot_num'] : null,
            'rating'               => isset($holeParams['rating']) ? $holeParams['rating'] : null,
            'km_stroke_num'        => isset($holeParams['km_stroke_num']) ? $holeParams['km_stroke_num'] : null,
            'km_putt_num'          => isset($holeParams['km_putt_num']) ? $holeParams['km_putt_num'] : null,
            'km_bunker_shot_num'   => isset($holeParams['km_bunker_shot_num']) ? $holeParams['km_bunker_shot_num'] : null,
            'km_penalty_num'       => isset($holeParams['km_penalty_num']) ? $holeParams['km_penalty_num'] : null,
            'km_tee_shot_lie'      => isset($holeParams['km_tee_shot_lie']) ? $holeParams['km_tee_shot_lie'] : null,
            'km_par_on_flg'        => isset($holeParams['km_par_on_flg']) ? $holeParams['km_par_on_flg'] : null,
            'km_bogey_on_flg'      => isset($holeParams['km_bogey_on_flg']) ? $holeParams['km_bogey_on_flg'] : null,
            'km_guard_bunker_flg'  => isset($holeParams['km_guard_bunker_flg']) ? $holeParams['km_guard_bunker_flg'] : null,
            'km_sand_save_flg'     => isset($holeParams['km_sand_save_flg']) ? $holeParams['km_sand_save_flg'] : null,
            'drive_distance'       => isset($holeParams['drive_distance']) ? $holeParams['drive_distance'] : null,
            'start_at'             => isset($holeParams['start_at']) ? $holeParams['start_at'] : null,
            'end_at'               => isset($holeParams['end_at']) ? $holeParams['end_at'] : null,
            'green_center_lat'     => isset($holeParams['green_center_lat']) ? $holeParams['green_center_lat'] : null,
            'tee_shot_lie'         => isset($holeParams['tee_shot_lie']) ? $holeParams['tee_shot_lie'] : null,
            'green_center_long'    => isset($holeParams['green_center_long']) ? $holeParams['green_center_long'] : null,
            'complete_play_flg'    => isset($holeParams['complete_play_flg']) ? $holeParams['complete_play_flg'] : null,
            'm_green_version'      => isset($holeParams['m_green_version']) ? $holeParams['m_green_version'] : null,
            'par_on_flg'           => isset($holeParams['par_on_flg']) ? $holeParams['par_on_flg'] : null,
            'score_input_mode'     => isset($holeParams['score_input_mode']) ? $holeParams['score_input_mode'] : null,
            'm_green_id'           => isset($holeParams['m_green_id']) ? $holeParams['m_green_id'] : null,
            'km_scramble_flg'      => isset($holeParams['km_scramble_flg']) ? $holeParams['km_scramble_flg'] : null
        ];

        $holeEntity = $this->UserHoles->patchEntity($hole, $holeData);

        if($this->UserHoles->save($holeEntity)) {
            $shots = isset($holeParams['user_shots']) ? $holeParams['user_shots'] : [];

            foreach ($shots as $key => $shotParams) {
                $this->__finishUpdateShot($shotParams);
            }
        } else {
            $this->sendError(__('{0} {1} update failed', __('hole_id'), $holeParams['id']), API_CODE_100, API_HTTP_CODE_200, $courseEntity->errors());
        }

        return true;
    }

    /**
     * @param $shotParams
     * @return bool
     */
    private function __finishUpdateShot($shotParams) {
        if (empty($shotParams['id'])) {
            $this->_errors['id'] = __('{0} is required', __('shot_id'));
        }

        if (!empty($this->_errors)) {
            $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $this->_errors);
        }

        $shot = $this->UserShots->find('all', [
            'conditions' => [
                'id'                => $shotParams['id'],
                'user_round_id'     => $shotParams['user_round_id'],
                'user_course_id'    => $shotParams['user_course_id'],
                'user_hole_id'      => $shotParams['user_hole_id']
            ]
        ])->first();

        if(!empty($shot)) {
            $shotEntity = $shot;
        } else {
            $shotEntity = $this->UserShots->newEntity();
        }

        $shotData = [
            'id'                    => isset($shotParams['id']) ? $shotParams['id'] : null,
            'user_round_id'         => isset($shotParams['user_round_id']) ? $shotParams['user_round_id'] : null,
            'user_course_id'        => isset($shotParams['user_course_id']) ? $shotParams['user_course_id'] : null,
            'user_hole_id'          => isset($shotParams['user_hole_id']) ? $shotParams['user_hole_id'] : null,
            'user_account_id'       => $this->account['id'],
            'shot_order'            => isset($shotParams['shot_order']) ? $shotParams['shot_order'] : null,
            'shot_state'            => isset($shotParams['shot_state']) ? $shotParams['shot_state'] : null,
            'version'               => isset($shotParams['version']) ? $shotParams['version'] : null,
            'club_id'               => isset($shotParams['club_id']) ? $shotParams['club_id'] : null,
            'lie_inclination_type'  => isset($shotParams['lie_inclination_type']) ? $shotParams['lie_inclination_type'] : null,
            'hit_course_type'       => isset($shotParams['hit_course_type']) ? $shotParams['hit_course_type'] : null,
            'hit_bend_type'         => isset($shotParams['hit_bend_type']) ? $shotParams['hit_bend_type'] : null,
            'back_spin_flg'         => isset($shotParams['back_spin_flg']) ? $shotParams['back_spin_flg'] : null,
            'result_lie_type'       => isset($shotParams['result_lie_type']) ? $shotParams['result_lie_type'] : null,
            'expectation_lat'       => isset($shotParams['expectation_lat']) ? $shotParams['expectation_lat'] : null,
            'expectation_long'      => isset($shotParams['expectation_long']) ? $shotParams['expectation_long'] : null,
            'result_lat'            => isset($shotParams['result_lat']) ? $shotParams['result_lat'] : null,
            'result_long'           => isset($shotParams['result_long']) ? $shotParams['result_long'] : null,
            'diff_distance'         => isset($shotParams['diff_distance']) ? $shotParams['diff_distance'] : null,
            'hit_lat'               => isset($shotParams['hit_lat']) ? $shotParams['hit_lat'] : null,
            'hit_long'              => isset($shotParams['hit_long']) ? $shotParams['hit_long'] : null,
            'hit_lie_type'          => isset($shotParams['hit_lie_type']) ? $shotParams['hit_lie_type'] : null,
            'direction_type'        => isset($shotParams['direction_type']) ? $shotParams['direction_type'] : null,
            'start_setting_at'      => isset($shotParams['start_setting_at']) ? $shotParams['start_setting_at'] : null,
            'result_setting_at'     => isset($shotParams['result_setting_at']) ? $shotParams['result_setting_at'] : null,
            'dif_y'                 => isset($shotParams['dif_y']) ? $shotParams['dif_y'] : null,
            'diff_alpha'            => isset($shotParams['diff_alpha']) ? $shotParams['diff_alpha'] : null,
            'diff_flg'              => isset($shotParams['diff_flg']) ? $shotParams['diff_flg'] : null,
            'm_club_id'             => isset($shotParams['m_club_id']) ? $shotParams['m_club_id'] : null,
            'dif_d'                 => isset($shotParams['dif_d']) ? $shotParams['dif_d'] : null,
            'putt_num'              => isset($shotParams['putt_num']) ? $shotParams['putt_num'] : null,
            'penalty_lat'           => isset($shotParams['penalty_lat']) ? $shotParams['penalty_lat'] : null,
            'distance'              => isset($shotParams['distance']) ? $shotParams['distance'] : null,
            'club_color'            => isset($shotParams['club_color']) ? $shotParams['club_color'] : null,
            'club_name'             => isset($shotParams['club_name']) ? $shotParams['club_name'] : null,
            'dif_x'                 => isset($shotParams['dif_x']) ? $shotParams['dif_x'] : null,
            'penalty_long'          => isset($shotParams['penalty_long']) ? $shotParams['penalty_long'] : null,
            'category_id'           => isset($shotParams['category_id']) ? $shotParams['category_id'] : null,
            'club_distance'         => isset($shotParams['club_distance']) ? $shotParams['club_distance'] : null
        ];

        $shotEntity = $this->UserShots->patchEntity($shotEntity, $shotData);

        if(!$this->UserShots->save($shotEntity)) {
            $this->sendError(__('{0} {1} update failed', __('shot_id'), $shotParams['id']), API_CODE_100, API_HTTP_CODE_200, $shotEntity->errors());
        }

        return true;
    }

    /**
     * @param $parParams
     * @return bool
     */
    private function __finishUpdatePar($parParams) {
        if (empty($parParams['user_round_id'])) {
            $this->_errors['user_round_id'] = __('{0} is required', __('round_id'));
        }

        if (empty($parParams['user_course_id'])) {
            $this->_errors['user_course_id'] = __('{0} is required', __('course_id'));
        }

        if (empty($parParams['master_hole_id'])) {
            $this->_errors['master_hole_id'] = __('{0} is required', __('hole_id'));
        }

        if (!empty($this->_errors)) {
            $this->sendError(API_CODE_100_MSG, API_CODE_100, API_HTTP_CODE_200, $this->_errors);
        }

        $round = $this->UserRounds->find('all', [
            'conditions' => [
                'id' => $parParams['user_round_id']
            ]
        ])->first();

        if(empty($round)) {
            $this->sendError(__('{0} {1} does not exist', __('round_id'), $parParams['round_id']), API_CODE_101, API_HTTP_CODE_200, $this->_errors);
        }

        $course = $this->UserCourses->find('all', [
            'conditions' => [
                'id' => $parParams['user_course_id']
            ]
        ])->first();

        if(empty($course)) {
            $this->sendError(__('{0} {1} does not exist', __('course_id'), $parParams['user_course_id']), API_CODE_101, API_HTTP_CODE_200, $this->_errors);
        }

        $parData = [
            'update_golf_field_id'  => $round['m_field_id'],
            'update_course_id'      => $course['m_course_id'],
            'update_hole_id'        => $parParams['master_hole_id'],
            'old_par_num'           => isset($parParams['master_par_num']) ? $parParams['master_par_num'] : null,
            'new_par_num'           => isset($parParams['par_num']) ? $parParams['par_num'] : null,
            'version'               => isset($parParams['master_hole_version']) ? $parParams['master_hole_version'] : null,
            'update_by'             => $this->account['id'],
            'status'                => 1
        ];

        $parEntity = $this->GolfUpdates->newEntity($parData);

        if(!$this->GolfUpdates->save($parEntity)) {
            $this->sendError(__('{0} registration failed', __('golf_update')), API_CODE_100, API_HTTP_CODE_200, $parEntity->errors());
        }

        return true;
    }

    /**
     * @return mixed
     */
    private function __getRoundsData() {
        $rounds = $this->UserRounds->find('all', [
            'fields' => [
                'id',
                'user_account_id',
                'm_field_id',
                'm_field_version',
                'field_name',
                'field_name_kana',
                'field_name_en',
                'status',
                'type',
                'score',
                'putt_num',
                'fairway_keep_num',
                'hole_num',
                'par_num',
                'sand_save_num',
                'scramble_num',
                'par_on_num',
                'weather',
                'temperature',
                'wind',
                'longest_drive_distance',
                'current_lat',
                'current_long',
                'calibration_lat',
                'calibration_long',
                'start_at',
                'end_at',
                'recovery_scramble_rate',
                'setting_calibration_flg',
                'bogey_on_num',
                'sum_putt_of_par_on',
                'putting_gir_rate',
                'recovery_sandsave_rate',
                'distance_deviation',
                'bearing_deviation'
            ],
            'conditions' => [
                'user_account_id'   => $this->account['id'],
                'status'            => 2
            ]
        ])->contain([
            'UserCourses' => function ($q) {
                $fields = [
                    'id',
                    'user_round_id',
                    'user_account_id',
                    'm_course_version',
                    'm_course_id',
                    'm_course_name',
                    'm_course_name_kana',
                    'm_course_name_en',
                    'type',
                    'status'
                ];

                return $q->select($fields)
                        ->contain(['UserHoles' => function ($q) {
                            $fields = [
                                'id',
                                'user_round_id',
                                'user_course_id',
                                'user_account_id',
                                'm_hole_version',
                                'm_hole_id',
                                'hole_name',
                                'status',
                                'hole_stage',
                                'score_input_mode',
                                'tee_lat',
                                'tee_long',
                                'tee_shot_lie',
                                'm_green_id',
                                'm_green_version',
                                'green_front_lat',
                                'green_front_long',
                                'green_center_lat',
                                'green_center_long',
                                'green_back_lat',
                                'green_back_long',
                                'score_num',
                                'stroke_num',
                                'putt_num',
                                'sand_save_flg',
                                'scramble_flg',
                                'par_on_flg',
                                'bogey_on_flg',
                                'guard_bunker_flg',
                                'fairway_status',
                                'par_num',
                                'master_par_num',
                                'penalty_num',
                                'bunker_shot_num',
                                'rating',
                                'km_stroke_num',
                                'km_putt_num',
                                'km_bunker_shot_num',
                                'km_penalty_num',
                                'km_tee_shot_lie',
                                'km_par_on_flg',
                                'km_bogey_on_flg',
                                'km_guard_bunker_flg',
                                'km_sand_save_flg',
                                'drive_distance',
                                'start_at',
                                'end_at',
                                'complete_play_flg',
                                'km_scramble_flg'
                            ];

                            return $q->select($fields)
                                    ->contain(['UserShots' => function ($q) {
                                        $fields = [
                                            'id',
                                            'user_round_id',
                                            'user_course_id',
                                            'user_hole_id',
                                            'user_account_id',
                                            'shot_order',
                                            'shot_state',
                                            'club_id',
                                            'lie_inclination_type',
                                            'hit_course_type',
                                            'hit_bend_type',
                                            'back_spin_flg',
                                            'result_lie_type',
                                            'expectation_lat',
                                            'expectation_long',
                                            'result_lat',
                                            'result_long',
                                            'diff_distance',
                                            'hit_lat',
                                            'hit_long',
                                            'hit_lie_type',
                                            'direction_type',
                                            'putt_num',
                                            'start_setting_at',
                                            'result_setting_at',
                                            'dif_y',
                                            'diff_alpha',
                                            'diff_flg',
                                            'm_club_id',
                                            'dif_d',
                                            'penalty_lat',
                                            'distance',
                                            'club_color',
                                            'club_name',
                                            'dif_x',
                                            'penalty_long',
                                            'category_id',
                                            'club_distance',
                                            'delete_flg'
                                        ];

                                        return $q->select($fields)->autoFields(false);
                                    }])
                                    ->autoFields(false);
                        }])
                        ->autoFields(false);
            }
        ]);

        return $rounds->hydrate(false)->toArray();
    }

    private function _deleteRound($round_id){
        $data = ['delete_flg' => DELETE_FLG_DEACTIVE];
        $condition = ['user_round_id' => $round_id];
        $queryRound = $this->UserRounds->query();
        //delete round
        if($queryRound->update()
            ->set($data)
            ->where(['id' => $round_id])
            ->execute()) {
            //delete course
            $queryCourse = $this->UserCourses->query();
            if($queryCourse->update()
                ->set($data)
                ->where($condition)
                ->execute()) {
                //delete hole
                $queryHole = $this->UserHoles->query();
                if($queryHole->update()
                    ->set($data)
                    ->where($condition)
                    ->execute()) {
                    //delete hole
                    $querySort = $this->UserShots->query();
                    if(!$querySort->update()
                        ->set($data)
                        ->where($condition)
                        ->execute()) {
                        return false;
                    }
                    //end delete short
                }
                //end delete hole
                else{
                    return false;
                }
            }
            //end delete course
            else{
                return false;
            }
        }
        //end delete round
        else{
            return false;
        }
        return true;
    }
}