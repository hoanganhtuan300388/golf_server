<?php

namespace App\Controller;


use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Routing\Router;

class GolfsController extends AdminAppController {
    public function initialize() {
        parent::initialize();
        $this->GolfFields           = TableRegistry::get('GolfFields');
        $this->Nations              = TableRegistry::get('Nations');
        $this->Prefectures          = TableRegistry::get('Prefectures');
        $this->Holes                = TableRegistry::get('Holes');
        $this->Greens               = TableRegistry::get('Greens');
        $this->Courses              = TableRegistry::get('Courses');
    }

    public function index() {
        $this->set('title', __('Golf course list'));

        $labelGolf  = Configure::read('config.golf');

        $query      = $this->request->getQuery();
        $conditions = [];
        $conditions['last_version_flg '] = LAST_VERSION_ACTIVE;

        $list_nation = $this->Nations->find('list',[
            'keyField' => 'id',
            'valueField' => 'nation_name_jp'
        ])->toArray();

        $list_pre = [];
        $url_pre = Router::url(['controller' => 'Golfs', 'action' => 'getPrefecture'], true);
        if(!empty($query['field_name'])) {
            $conditions[]['field_name LIKE'] = "%{$query['field_name']}%";
            $this->request->data['search']['field_name'] = $query['field_name'];
        }
        if(!empty($query['service_status'])) {
            $conditions[]['service_status'] = $query['service_status'];
            $this->request->data['search']['service_status'] = $query['service_status'];
        }
        if(!empty($query['nation'])) {
            $this->request->data['search']['nation'] = $query['nation'];
            $list_pre = $this->Prefectures->find('list', [
                'keyField' => 'id',
                'valueField' => 'prefecture_name'
            ])->where(['nation_id' => $query['nation'], 'delete_flg' => DELETE_FLG_ACTIVE])->toArray();
        }
        if(!empty($query['prefecture'])) {
            $conditions[]['prefecture_id'] = $query['prefecture'];
            $this->request->data['search']['prefecture'] = $query['prefecture'];
        }

        $this->request->data['display']['limit'] = !empty($query['limit']) ? $query['limit'] : LIMIT_VALUE;

        $fields = ['GolfFields.field_id', 'GolfFields.version', 'GolfFields.last_version_flg', 'GolfFields.field_name', 'GolfFields.address', 'GolfFields.service_status', 'GolfFields.delete_flg'];

        $this->paginate = [
            'conditions'    => [$conditions],
            'fields'        => $fields,
            'limit'         => LIMIT_VALUE,
            'order'         => ['field_id' => 'desc'],
            'contain'       => ['Courses' => function ($q) {
                return $q
                    ->where(['Courses.last_version_flg' => LAST_VERSION_ACTIVE]);
            }]
        ];
        $this->set('url_pre', $url_pre);
        $this->set(['data' => $this->paginate($this->GolfFields), 'list_nation' => $list_nation, 'labelGolf' => $labelGolf, 'list_pre' => $list_pre]);
    }


    public function add() {
        $this->set('title', __('Arlex Golf Club'));
        $service_status = Configure::read('config.golf.service_status');
        $status = Configure::read('config.golf.status');
        $list_pre = [];
        $list_nation = $this->Nations->find('list',[
            'keyField' => 'id',
            'valueField' => 'nation_name_jp'
        ])->where(['delete_flg' => DELETE_FLG_ACTIVE])->toArray();
        $url_pre = Router::url(['controller' => 'Golfs', 'action' => 'getPrefecture'], true);
        $url_latlong = Router::url(['controller' => 'Golfs', 'action' => 'getlatlong'], true);
        if(!empty($query['nation'])) {
            $this->request->data['search']['nation'] = $query['nation'];
            $list_pre = $this->Prefectures->find('list', [
                'keyField' => 'id',
                'valueField' => 'prefecture_name'
            ])->where(['nation_id' => $query['nation'], 'delete_flg' => DELETE_FLG_ACTIVE])->toArray();
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            //get golf_field_id
            $maxGolf = $this->GolfFields->find('all',[
                'fields' => [
                    'field_id', 'version'
                ],
                'contain' => false
            ])->order(['field_id' => 'desc'])->first();
            $field_id = 1;
            if(isset($maxGolf['field_id'])) $field_id = ($maxGolf['field_id'] + 1);
            //start transaction
            $conn = ConnectionManager::get('default');
            $conn->begin();
            $transaction = 0;

            //save data golf field
            $golfdata = (isset($data['golf']) && count($data['golf']) > 0) ? $data['golf'] : [];
            $golfdata['field_id'] = $field_id;
            $golf = $this->GolfFields->newEntity($golfdata);
            if ($this->GolfFields->save($golf)) {
                //save data course
                if(isset($data['course']) && count($data['course']) > 0){
                    foreach($data['course'] as $key => $value){
                        if(!$this->_addCourse($value,$field_id)){
                            $transaction = 1;
                        }
                    }
                }
            }else{
                $transaction = 1;
            }

            //end save golf
            if($transaction == 1){
                $conn->rollback();
                $this->Flash->error(__('Registration failed'));
                return $this->redirect(['action' => 'index']);
            }else{
                $conn->commit();
                $this->Flash->success(__('Registration succeeded'));
                return $this->redirect(['action' => 'index']);
            }
        }
       // $this->set('data', $golf);
        $this->set('list_nation', $list_nation);
        $this->set('service_status', $service_status);
        $this->set('status', $status);
        $this->set('list_pre', $list_pre);
        $this->set('url_pre', $url_pre);
        $this->set('url_latlong', $url_latlong);

    }
    //delete golf
    public function delete()
    {
        $this->set('title', __('楽部'));
        $query = $this->request->getQuery();
        //check data
        if (!isset($query['field_id'])) {
            $this->Flash->error(__('Not Found Golf'));
            return $this->redirect(['action' => 'index']);
        }
        $golf = $this->GolfFields->find()->where(['GolfFields.field_id' => $query['field_id'], 'GolfFields.last_version_flg' => LAST_VERSION_ACTIVE, 'GolfFields.delete_flg' => 0])->first();
        if (empty($golf)) {
            $this->Flash->error(__('Not Found Golf'));
            return $this->redirect(['action' => 'index']);
        }
        $delete = [
            'delete_flg' => DELETE_FLG_DEACTIVE
        ];
        $condition = [
            'golf_field_id' => $query['field_id']
        ];
        $golfDeleteModel = $this->GolfFields->patchEntity($golf, $delete);
        //start transaction
        $conn = ConnectionManager::get('default');
        $conn->begin();
        $transaction = 0;
        //delete golf
        if ($this->GolfFields->save($golfDeleteModel)) {
            //delete Course
            $queryCourse = $this->Courses->query();
            if ($queryCourse->update()
                ->set($delete)
                ->where($condition)
                ->execute()
            ) {
                //delete Hole
                $queryHole = $this->Holes->query();
                if ($queryHole->update()
                    ->set($delete)
                    ->where($condition)
                    ->execute()
                ) {
                    $queryGreen = $this->Greens->query();
                    if (!$queryGreen->update()
                        ->set($delete)
                        ->where($condition)
                        ->execute()
                    ) {
                        $transaction = 1;
                    }
                }else{
                    $transaction = 1;
                }
            }else{
                $transaction = 1;
            }

            //delete Green
        }else{
            $transaction = 1;
        }
        //end Delete golf
        if($transaction == 1){
            $conn->rollback();
            $this->Flash->error(__('Delete failed'));
            return $this->redirect(['action' => 'index']);
        }else{
            $conn->commit();
            $this->Flash->success(__('Delete succeeded'));
            return $this->redirect(['action' => 'index']);
        }
    }
    public function edit() {
        $service_status = Configure::read('config.golf.service_status');
        $status = Configure::read('config.golf.status');
        $query      = $this->request->getQuery();
        //check data
        if (!isset($query['field_id'])){
            $this->Flash->error(__('Not Found Golf'));
            return $this->redirect(['action' => 'index']) ;
        }
        $url_pre = Router::url(['controller' => 'Golfs', 'action' => 'getPrefecture'], true);
        $url_latlong = Router::url(['controller' => 'Golfs', 'action' => 'getlatlong'], true);
        $golf = $this->GolfFields->find()->where(['GolfFields.field_id' => $query['field_id'], 'GolfFields.last_version_flg' => LAST_VERSION_ACTIVE, 'GolfFields.delete_flg' => 0])->first();
        if (empty($golf)){
            $this->Flash->error(__('Not Found Golf'));
            return $this->redirect(['action' => 'index']) ;
        }
        $this->set('title', $golf['field_name']);
        $list_pre = [];
        $list_nation = $this->Nations->find('list',[
            'keyField' => 'id',
            'valueField' => 'nation_name_jp'
        ])->where(['delete_flg' => DELETE_FLG_ACTIVE])->toArray();
        $nationold = 0;
        if(count($list_nation) > 0 ) {
            //find nation
            $prefectures = $this->Prefectures->find('all', [
                'keyField' => 'id',
                'valueField' => 'prefecture_name'
            ])->where(['id' => $golf['prefecture_id'], 'delete_flg' => DELETE_FLG_ACTIVE])->first()->toArray();
            $nationold = $prefectures['nation_id'];
            //find prefecture
            $list_pre = $this->Prefectures->find('list', [
                'keyField' => 'id',
                'valueField' => 'prefecture_name'
            ])->where(['nation_id' => $nationold, 'delete_flg' => DELETE_FLG_ACTIVE])->toArray();
        }
        //find course
        $coursesListData = $this->Courses->find('all',[
            'conditions' => [
                'Courses.golf_field_id'     => $golf['field_id'],
                'Courses.last_version_flg'  => LAST_VERSION_ACTIVE,
                'Courses.delete_flg'        => DELETE_FLG_ACTIVE
            ],
            'fields' => ['golf_field_id', 'course_id', 'version', 'hole_num', 'par_num', 'course_name', 'course_name_kana', 'course_name_en', 'service_status'],
            'contain' => false
        ])->toArray();
        foreach($coursesListData as $key => $value){
            $holesList = $this->Holes->find('all',[
                'conditions' => [
                    'Holes.golf_field_id'       => $golf['field_id'],
                    'Holes.course_id'           => $value['course_id'],
                    'Holes.last_version_flg'    => LAST_VERSION_ACTIVE,
                    'Holes.delete_flg'          => DELETE_FLG_ACTIVE
                ],
                'fields' => [
                    'golf_field_id', 'course_id', 'hole_id', 'version', 'last_version_flg', 'hole_name', 'par_num', 'tee_long', 'tee_lat', 'service_status'
                ],
                'contain' => false
            ])->toArray();
            foreach($holesList as $h => $v){
                $greens = $this->Greens->find('all',[
                    'conditions' => [
                        'Greens.golf_field_id'      => $golf['field_id'],
                        'Greens.course_id'          => $value['course_id'],
                        'Greens.hole_id'            => $v['hole_id'],
                        'Greens.last_version_flg'   => LAST_VERSION_ACTIVE,
                        'Greens.delete_flg'         => DELETE_FLG_ACTIVE
                    ],
                    'fields' => [
                        'golf_field_id', 'course_id', 'hole_id', 'green_id', 'version', 'last_version_flg', 'front_lat', 'front_long', 'centre_lat', 'centre_long', 'back_lat', 'back_long'
                    ],
                    'contain' => false
                ])->toArray();
                $holesList[$h]['greens'] = $greens;
            }
            $coursesListData[$key]['holes'] = $holesList;
        }
        //save data
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            //start transaction
            $conn = ConnectionManager::get('default');
            $conn->begin();
            $transaction = 0;

            //save data golf field
            $golfdata = (isset($data['golf']) && count($data['golf']) > 0) ? $data['golf'] : [];
            $field_id = $data['golf']['field_id'];
            $queryGolf = $this->GolfFields->query();
            if ($queryGolf->update()
                ->set($golfdata)
                ->where(['field_id' => $field_id, 'version' => $golfdata['version'], 'last_version_flg' => LAST_VERSION_ACTIVE])
                ->execute()) {
                //save data course
                if(isset($data['course']) && count($data['course']) > 0){
                    foreach($data['course'] as $key => $value){
                        //case edit or delete
                        $value['field_id'] = $data['golf']['field_id'];
                        if(isset($value['course_id']) && $value['course_id'] > 0){
                            //case edit
                            if(isset($value['delete']) && $value['delete'] == 0){
                                if(!$this->_editCourse($value)){
                                    $transaction = 1;
                                }
                            }
                            //case delete
                            else{
                                if(!$this->_deleteCourse($value)){
                                    $transaction = 1;
                                }
                            }
                        }else{
                        //case add new courses
                            if(isset($value['delete']) && $value['delete'] == 0){
                                if(!$this->_addCourse($value,$field_id)){
                                    $transaction = 1;
                                }
                            }
                        }
                    }
                }
            }else{
                $transaction = 1;
            }
            //end save golf
            if($transaction == 1){
                $conn->rollback();
                $this->Flash->error(__('Update failed'));
                return $this->redirect(['action' => 'index']);
            }else{
                $conn->commit();
                $this->Flash->success(__('Update succeeded'));
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set('list_nation', $list_nation);
        $this->set('service_status', $service_status);
        $this->set('status', $status);
        $this->set('list_pre', $list_pre);
        $this->set('golf', $golf);
        $this->set('nationold', $nationold);
        $this->set('coursesListData', $coursesListData);
        $this->set('url_pre', $url_pre);
        $this->set('url_latlong', $url_latlong);

    }

    public function _deleteCourse($data) {
        //check exit course
        $results['error'] = '';
        $courseData = $this->Courses->find('all',[
            'conditions' => [
                'Courses.golf_field_id'     => $data['field_id'],
                'Courses.course_id'         => $data['course_id'],
                'Courses.version'           => $data['version'],
                'Courses.last_version_flg'  => LAST_VERSION_ACTIVE
            ],
            'fields' => [
                'golf_field_id', 'course_id', 'version', 'last_version_flg', 'delete_flg', 'hole_num', 'par_num'
            ],
            'contain' => false
        ])->first();
        if(count($courseData) > 0){
            $delete = [
                'delete_flg' => DELETE_FLG_DEACTIVE
            ];
            $courseUpdateModel = $this->Courses->patchEntity($courseData, $delete);
            //begin delete Course
            if ($this->Courses->save($courseUpdateModel)) {
                //delete holes
                $holesData = $this->Holes->find('all',[
                    'conditions' => [
                        'Holes.golf_field_id'   => $data['field_id'],
                        'Holes.course_id'       => $data['course_id']
                    ],
                    'fields' => [
                        'golf_field_id', 'course_id', 'hole_id', 'version', 'last_version_flg', 'hole_name', 'par_num', 'tee_long', 'tee_lat', 'delete_flg'
                    ],
                    'contain' => false
                ])->toArray();
                if(count($holesData) > 0){
                    foreach($holesData as $key => $value){
                        $dataHoleUpdate = [
                            'golf_field_id' => $data['field_id'],
                            'course_id'     => $data['course_id'],
                            'hole_id'       => $value['hole_id'],
                            'version'       => $value['version']
                        ];
                        $dataHoleUpdate = $this->Holes->newEntity($dataHoleUpdate);
                        $holeUpdateModel = $this->Holes->patchEntity($dataHoleUpdate, $delete);
                        //begin delete Holes
                        if ($this->Holes->save($holeUpdateModel)) {
                            $greensData = $this->Greens->find('all',[
                                'conditions' => [
                                    'Greens.golf_field_id'      => $data['field_id'],
                                    'Greens.course_id'          => $data['course_id'],
                                    'Greens.hole_id'            => $value['hole_id']
                                ],
                                'fields' => [
                                    'golf_field_id', 'course_id', 'hole_id', 'green_id', 'version', 'last_version_flg', 'delete_flg'
                                ],
                                'contain' => false
                            ])->toArray();
                            if(count($greensData) > 0) {
                                foreach ($greensData as $k => $v) {
                                    $dataGreenUpdate = [
                                        'golf_field_id' => $data['field_id'],
                                        'course_id' => $data['course_id'],
                                        'hole_id' => $value['hole_id'],
                                        'green_id' => $v['green_id'],
                                        'version' => $v['version']
                                    ];
                                    //begin delete Greens
                                    $dataGreenUpdate = $this->Greens->newEntity($dataGreenUpdate);
                                    $greenUpdateModel = $this->Greens->patchEntity($dataGreenUpdate, $delete);
                                    if (!$this->Greens->save($greenUpdateModel)) {
                                        //error
                                        return false;
                                    }
                                }
                            }
                        }
                        //end delete Holes
                        else{
                            //error
                            return false;
                        }
                    }

                }
            }
            //end delete Course
            else{
                //error
                return false;
            }
            return true;
        }
    }
    public function _deleteHole($data) {
        $courseData = $this->Courses->find('all',[
            'conditions' => [
                'Courses.golf_field_id'     => $data['field_id'],
                'Courses.course_id'         => $data['course_id'],
                'Courses.last_version_flg'  => LAST_VERSION_ACTIVE
            ],
            'fields' => [
                'golf_field_id', 'course_id', 'version', 'last_version_flg', 'delete_flg', 'hole_num', 'par_num'
            ],
            'contain' => false
        ])->first();
        if(count($courseData) > 0){
            //delete holes
            $holesData = $this->Holes->find('all',[
                'conditions' => [
                    'Holes.golf_field_id'   => $data['field_id'],
                    'Holes.course_id'       => $data['course_id'],
                    'Holes.hole_id'         => $data['hole_id'],
                    'Holes.version'         => $data['version']
                ],
                'fields' => [
                    'golf_field_id', 'course_id', 'hole_id', 'version', 'last_version_flg', 'hole_name', 'par_num', 'tee_long', 'tee_lat', 'delete_flg'
                ],
                'contain' => false
            ])->first();
            if(count($holesData) > 0){
                $delete = [
                    'delete_flg' => DELETE_FLG_DEACTIVE
                ];
                $holeUpdateModel = $this->Holes->patchEntity($holesData, $delete);
                //begin delete Holes
                if ($this->Holes->save($holeUpdateModel)) {
                    $greensData = $this->Greens->find('all',[
                        'conditions' => [
                            'Greens.golf_field_id'  => $data['field_id'],
                            'Greens.course_id'      => $data['course_id'],
                            'Greens.hole_id'        => $data['hole_id']
                        ],
                        'fields' => [
                            'golf_field_id', 'course_id', 'hole_id', 'green_id', 'version', 'last_version_flg', 'delete_flg'
                        ],
                        'contain' => false
                    ])->toArray();
                    if(count($greensData) > 0) {
                        foreach ($greensData as $k => $v) {
                            $dataGreenUpdate = [
                                'golf_field_id' => $data['field_id'],
                                'course_id' => $data['course_id'],
                                'hole_id' => $data['hole_id'],
                                'green_id' => $v['green_id'],
                                'version' => $v['version']
                            ];
                            //begin delete Greens
                            $dataGreenUpdate = $this->Greens->newEntity($dataGreenUpdate);
                            $greenUpdateModel = $this->Greens->patchEntity($dataGreenUpdate, $delete);
                            if (!$this->Greens->save($greenUpdateModel)) {
                                return false;
                            }
                        }
                    }
                }
                //end delete Holes
                else{
                    //error
                    return false;
                }
            }
            return true;
        }else{
            return false;
        }
    }
    public function _addCourse($data,$field_id) {
        $courseFind = $this->Courses->find('all',
            array(
                'conditions' => array(
                    'Courses.golf_field_id' => $field_id,
                    'Courses.course_id = (SELECT MAX(m_course.course_id) FROM m_course WHERE m_course.golf_field_id = '.$field_id.')' ,
                ),
            )
        )->first();
        $course_id = 1;
        if(isset($courseFind['course_id'])) $course_id = ($courseFind['course_id'] + 1);
        $dataCourse = [
            'golf_field_id'         => $field_id,
            'course_id'             => $course_id,
            'version'               => 1,
            'service_status'        => $data['service_status'],
            'course_name'           => $data['course_name'],
            'course_name_kana'      => $data['course_name_kana'],
            'course_name_en'        => $data['course_name_en'],
            'hole_num'              => $data['hole_num'],
            'par_num'               => $data['par_num']
        ];
        $courses = $this->Courses->newEntity($dataCourse);
        if ($this->Courses->save($courses)) {
            //save data hole
            if(isset($data['hole']) && count($data['hole']) > 0){
                foreach($data['hole'] as $h => $vh){
                    $holeFind = $this->Holes->find('all',
                        array(
                            'conditions' => array(
                                'Holes.golf_field_id'   => $field_id,
                                'Holes.course_id'       => $course_id,
                                'Holes.hole_id = (SELECT MAX(m_hole.hole_id) FROM m_hole WHERE m_hole.golf_field_id = '.$field_id.' AND m_hole.course_id ='.$course_id.' )' ,
                            ),
                        )
                    )->first();
                    $hole_id = 1;
                    if(isset($holeFind['hole_id'])) $hole_id = ($holeFind['hole_id'] + 1);
                    $dataHole = [
                        'golf_field_id'         => $field_id,
                        'course_id'             => $course_id,
                        'hole_id'               => $hole_id,
                        'version'               => 1,
                        'service_status'        => $vh['status_hole_add'],
                        'hole_name'             => $vh['hole_name_add'],
                        'par_num'               => $vh['par_hole_add'],
                        'tee_long'              => $vh['tee_long_hole_add'],
                        'tee_lat'               => $vh['tee_lat_hole_add']
                    ];
                    $holes = $this->Holes->newEntity($dataHole);

                    if ($this->Holes->save($holes)) {
                        //save data green
                        if(isset($vh['latlong']) && count($vh['latlong']) > 0){
                            $flg = 0;
                            $dataGreen = [
                                'golf_field_id'     => $field_id,
                                'course_id'         => $course_id,
                                'hole_id'           => $hole_id,
                                'version'           => 1,
                            ];
                            foreach($vh['latlong'] as $g => $vg){
                                if(strpos($g, 'ichi_hdd_') !== false){
                                    $pieces = explode(", ", $vg);
                                    $dataGreen['front_lat'] = $pieces[0];
                                    $dataGreen['front_long'] = $pieces[1];
                                    $flg ++;
                                }
                                if(strpos($g, 'ni_hdd_') !== false){
                                    $pieces = explode(", ", $vg);
                                    $dataGreen['centre_lat'] = $pieces[0];
                                    $dataGreen['centre_long'] = $pieces[1];
                                    $flg ++;
                                }
                                if(strpos($g, 'san_hdd_') !== false){
                                    $pieces = explode(", ", $vg);
                                    $dataGreen['back_lat'] = $pieces[0];
                                    $dataGreen['back_long'] = $pieces[1];
                                    $flg ++;
                                }
                                if($flg%3 == 0){
                                    $greens = $this->Greens->newEntity($dataGreen);
                                    if (!$this->Greens->save($greens)) {
                                        //save data green
                                        return false;
                                    }
                                    //end save green
                                }
                            }
                        }
                    }else{
                        return false;
                    }
                    //end save hole
                }
            }
        }else{
            return false;
        }
        //end save Courses
        return true;
    }
    //edit course hole green
    public function _editCourse($data) {

        //check exit course
        $courseData = $this->Courses->find('all',[
            'conditions' => [
                'Courses.golf_field_id'     => $data['field_id'],
                'Courses.course_id'         => $data['course_id'],
                'Courses.version'           => $data['version'],
                'Courses.last_version_flg'  => LAST_VERSION_ACTIVE
            ],
            'fields' => [
                'golf_field_id', 'course_id', 'version', 'last_version_flg', 'delete_flg', 'hole_num', 'par_num'
            ],
            'contain' => false
        ])->first();
        if(count($courseData) > 0){
            $dataEdit = [
                'course_name'           => $data['course_name'],
                'course_name_kana'      => $data['course_name_kana'],
                'course_name_en'        => $data['course_name_en'],
                'service_status'        => $data['service_status'],
                'hole_num'              => $data['hole_num'],
                'par_num'               => $data['par_num']
            ];
            $courseUpdateModel = $this->Courses->patchEntity($courseData, $dataEdit);
            if (!$this->Courses->save($courseUpdateModel)) {
                return false;
            }else{
                //edit Holes
                $field_id = $data['field_id'];
                $course_id = $data['course_id'];
                if(isset($data['hole']) && count($data['hole']) > 0) {
                    foreach ($data['hole'] as $key => $data) {
                        //case edit or delete
                        $data['field_id'] = $field_id;
                        $data['course_id'] = $course_id;
                        if (isset($data['hole_id']) && $data['hole_id'] > 0) {
                            //case edit
                            if (isset($data['delete']) && $data['delete'] == 0) {
                                if (!$this->_editHole($data)) {
                                    return false;
                                }
                            } //case delete
                            else {
                                if (!$this->_deleteHole($data)) {
                                    return false;
                                }
                            }
                        } else {
                            //case add new
                            if (isset($data['delete']) && $data['delete'] == 0) {
                                $holeFind = $this->Holes->find('all',
                                    array(
                                        'conditions' => array(
                                            'Holes.golf_field_id' => $field_id,
                                            'Holes.course_id' => $course_id,
                                            'Holes.hole_id = (SELECT MAX(m_hole.hole_id) FROM m_hole WHERE m_hole.golf_field_id = ' . $field_id . ' AND m_hole.course_id =' . $course_id . ' )',
                                        ),
                                    )
                                )->first();
                                $hole_id = 1;
                                if (isset($holeFind['hole_id'])) $hole_id = ($holeFind['hole_id'] + 1);
                                $dataHole = [
                                    'golf_field_id' => $field_id,
                                    'course_id' => $course_id,
                                    'hole_id' => $hole_id,
                                    'version' => 1,
                                    'service_status' => $data['status_hole_add'],
                                    'hole_name' => $data['hole_name_add'],
                                    'par_num' => $data['par_hole_add'],
                                    'tee_long' => $data['tee_long_hole_add'],
                                    'tee_lat' => $data['tee_lat_hole_add']
                                ];
                                $holes = $this->Holes->newEntity($dataHole);
                                if ($this->Holes->save($holes)) {
                                    //save data green
                                    if (isset($data['latlong']) && count($data['latlong']) > 0) {
                                        $flg = 0;
                                        $dataGreen = [
                                            'golf_field_id' => $field_id,
                                            'course_id' => $course_id,
                                            'hole_id' => $hole_id,
                                            'version' => 1,
                                        ];
                                        foreach ($data['latlong'] as $g => $vg) {
                                            if (strpos($g, 'ichi_hdd_') !== false) {
                                                $pieces = explode(", ", $vg);
                                                $dataGreen['front_lat'] = $pieces[0];
                                                $dataGreen['front_long'] = $pieces[1];
                                                $flg++;
                                            }
                                            if (strpos($g, 'ni_hdd_') !== false) {
                                                $pieces = explode(", ", $vg);
                                                $dataGreen['centre_lat'] = $pieces[0];
                                                $dataGreen['centre_long'] = $pieces[1];
                                                $flg++;
                                            }
                                            if (strpos($g, 'san_hdd_') !== false) {
                                                $pieces = explode(", ", $vg);
                                                $dataGreen['back_lat'] = $pieces[0];
                                                $dataGreen['back_long'] = $pieces[1];
                                                $flg++;
                                            }
                                            if ($flg % 3 == 0) {
                                                $greens = $this->Greens->newEntity($dataGreen);
                                                if (!$this->Greens->save($greens)) {
                                                    //save data green
                                                    return false;
                                                }
                                                //end save green
                                            }
                                        }
                                    }
                                } else {
                                    return false;
                                }
                                //end save hole
                            }
                        }
                    }
                    //end save holes
                }
            }
            return true;
        }else{
            return false;
        }
    }
    public function _editHole($data) {
        //check exit course
        $holeData = $this->Holes->find('all',[
            'conditions' => [
                'Holes.golf_field_id'       => $data['field_id'],
                'Holes.course_id'           => $data['course_id'],
                'Holes.hole_id'             => $data['hole_id'],
                'Holes.version'             => $data['version'],
                'Holes.last_version_flg'    => LAST_VERSION_ACTIVE
            ],
            'fields' => [
                'golf_field_id', 'course_id', 'hole_id', 'version', 'last_version_flg', 'delete_flg', 'tee_long', 'tee_lat', 'par_num'
            ],
            'contain' => false
        ])->first();
        if(count($holeData) > 0){
            $dataHoleEdit = [
                'golf_field_id'     => $data['field_id'],
                'course_id'         => $data['course_id'],
                'hole_id'           => $data['hole_id'],
                'version'           => $data['version'],
                'service_status'    => $data['status_hole_add'],
                'hole_name'         => $data['hole_name_add'],
                'par_num'           => $data['par_hole_add'],
                'tee_long'          => $data['tee_long_hole_add'],
                'tee_lat'           => $data['tee_lat_hole_add']
            ];
            //edit hole
            $holeUpdateModel = $this->Holes->patchEntity($holeData, $dataHoleEdit);
            if (!$this->Holes->save($holeUpdateModel)) {
                return false;
            }else{
                //edit Green
                $field_id = $data['field_id'];
                $course_id = $data['course_id'];
                $hole_id = $data['hole_id'];
                $flg = 0;
                $dataGreen = [
                    'golf_field_id' => $field_id,
                    'course_id'     => $course_id,
                    'hole_id'       => $hole_id
                ];
                if(isset($data['latlong']) && count($data['latlong']) > 0) {
                    foreach ($data['latlong'] as $g => $vg) {
                        if (strpos($g, 'ichi_hdd_') !== false) {
                            $pieces = explode(", ", $vg);
                            $dataGreen['front_lat'] = $pieces[0];
                            $dataGreen['front_long'] = $pieces[1];
                            $flg++;
                        }
                        if (strpos($g, 'ni_hdd_') !== false) {
                            $pieces = explode(", ", $vg);
                            $dataGreen['centre_lat'] = $pieces[0];
                            $dataGreen['centre_long'] = $pieces[1];
                            $flg++;
                        }
                        if (strpos($g, 'san_hdd_') !== false) {
                            $pieces = explode(", ", $vg);
                            $dataGreen['back_lat'] = $pieces[0];
                            $dataGreen['back_long'] = $pieces[1];
                            $flg++;
                        }
                        if ($flg % 3 == 0 && $flg > 0) {
                            $flg = 0;
                            $flg_tmp = substr($g, (strripos($g, "_") + 1));
                            //case: edit or delete green
                            if (isset($data['latlong']['green_id_' . $flg_tmp]) && $data['latlong']['green_id_' . $flg_tmp] > 0) {
                                //$dataGreen['green_id'] = $dataDeleteGreen['green_id'] = $data['latlong']['green_id_'.$flg_tmp];
                                $dataGreen['version'] = $data['latlong']['version_' . $flg_tmp];
                                $queryGreen = $this->Greens->query();
                                if (isset($data['latlong']['delete_' . $flg_tmp]) && $data['latlong']['delete_' . $flg_tmp] == 1) {
                                    //case delete green
                                    //$greens = $this->Greens->newEntity($dataDeleteGreen);
                                    if (!$queryGreen->update()
                                        ->set(['delete_flg' => DELETE_FLG_DEACTIVE])
                                        ->where(['green_id' => $data['latlong']['green_id_' . $flg_tmp]])
                                        ->execute()
                                    ) {
                                        return false;
                                    }
                                } else {
                                    //case edit green
                                    //$greens = $this->Greens->newEntity($dataGreen);
                                    if (!$queryGreen->update()
                                        ->set($dataGreen)
                                        ->where(['green_id' => $data['latlong']['green_id_' . $flg_tmp]])
                                        ->execute()
                                    ) {
                                        return false;
                                    }
                                }
                                //end save green
                            } else {
                                //case add new
                                if (!isset($data['latlong']['delete_' . $flg_tmp]) || (isset($data['latlong']['delete_' . $flg_tmp]) && $data['latlong']['delete_' . $flg_tmp] == 0)) {
                                    $dataGreen['version'] = 1;
                                    $greens = $this->Greens->newEntity($dataGreen);
                                    if (!$this->Greens->save($greens)) {
                                        //save data green
                                        return false;
                                    }
                                }
                                //end save new green
                            }
                        }
                    }
                }
            }
            //end save holes
            return true;
        }else{
            return false;
        }
    }

    public function getPrefecture(){
        $this->autoRender = false;
        $params = $this->request->query;
        $results = [];
        if(isset($params['nation_id']) && $params['nation_id'] > 0){
            $results = $this->Prefectures->find('list', [
                'keyField' => 'id',
                'valueField' => 'prefecture_name'
            ])->where(['nation_id' => $params['nation_id'], 'delete_flg' => DELETE_FLG_ACTIVE])->toArray();
        }
        echo json_encode($results);
    }
    public function getlatlong(){
        $this->autoRender = false;
        $params = $this->request->query;
        $latitude = '';
        $longitude = '';
        $results = [];
        $prepAddr = str_replace(' ', '', $params['address']);
        $opts = array('http' => array('header' => "User-Agent:Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201"));
        $context = stream_context_create($opts);
        $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . urlencode($prepAddr) . '&sensor=false&key=AIzaSyAsiZ-Pg5NMTh2w8bFdPlPlkhoegrJZBpY', FALSE, $context);
        $output = json_decode($geocode);
        if (isset($output->{'results'}[0])) {
            $results['latitude'] = $output->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
            $results['longitude'] = $output->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
        }
        echo json_encode($results);
    }
}