<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/17/2018
 * Time: 2:03 PM
 */
namespace App\Controller;

class ForcedsController extends AdminAppController {

    /**
     * display forced list, search, pagination
     */
    public function index() {
        $this->set('title', __('Forced update list'));

        $query      = $this->request->getQuery();
        $conditions = [];

        if(!empty($query['id'])) {
            $conditions[]['id'] = $query['id'];
            $this->request->data['search']['id'] = $query['id'];
        }
        if(!empty($query['version'])) {
            $conditions[]['id'] = $query['version'];
            $this->request->data['search']['version'] = $query['version'];
        }
        if(isset($query['status'])) {
            $conditions[]['status'] = $query['status'];
            $this->request->data['search']['status'] = $query['status'];
        }
        if(!empty($query['keyword'])) {
            $conditions[]['OR'] = [
                'title LIKE'    => "%{$query['keyword']}%",
                'body LIKE'     => "%{$query['keyword']}%"
            ];
            $this->request->data['search']['keyword'] = $query['keyword'];
        }

        $this->request->data['display']['limit'] = !empty($query['limit']) ? $query['limit'] : LIMIT_VALUE;

        $this->paginate = [
            'conditions'    => [$conditions],
            'limit'         => LIMIT_VALUE,
            'order'         => ['id' => 'desc'],
            'contain'       => false
        ];

        $this->set('forceds', $this->paginate($this->Forceds));
    }

    /**
     * add new Maintenance
     * @return \Cake\Http\Response|null
     */
    public function add() {
        $this->set('title', __('Forced update create'));

        $countEnable = $this->Forceds->find('all', [
            'conditions' => [
                'status' => FORCED_STATUS_ENABLE
            ]
        ])->count();


        $forced = $this->Forceds->newEntity();

        if ($this->request->is('post')) {
            $this->request->data['status']      = FORCED_STATUS_ENABLE;
            $this->request->data['create_by']   = $this->Auth->user('id');
            $forced                             = $this->Forceds->patchEntity($forced, $this->request->data);

            if ($this->Forceds->save($forced)) {
                if($countEnable > 0) {
                    $this->Forceds->updateAll(
                        ['status' => FORCED_STATUS_DISABLE],
                        [
                            'id !='     => $forced->id,
                            'status'    => FORCED_STATUS_ENABLE
                        ]
                    );
                }

                $this->Flash->success(__('Registration succeeded'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Registration failed'));
            }
        }

        $this->set(['forced' => $forced, 'countEnable' => $countEnable]);
    }

    /**
     * @param null $id
     * @return \Cake\Http\Response|null
     */
    public function edit($id = null) {
        $this->set('title', __('Forced update editing'));

        if (!$id) {
            $this->response = $this->redirect(['action' => 'index']) ;
            $this->Flash->error(__('Not Found Forced'));
        }

        $forced = $this->Forceds->find('all', [
            'conditions' => [
                'id'        => $id,
                'status'    => FORCED_STATUS_ENABLE
            ]
        ])->firstOrFail();

        if($this->request->is(['post', 'put'])) {
            $this->request->data['update_by']   = $this->Auth->user('id');
            $forced                             = $this->Forceds->patchEntity($forced, $this->request->getData());

            if($this->Forceds->save($forced)) {
                $this->Flash->success(__('Update succeeded'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Update failed'));
            }
        }

        $this->set('forced', $forced);
    }

    /**
     * @param null $id
     */
    public function view($id = null) {
        $this->set('title', __('Forced update detailed information'));

        if (!$id) {
            $this->response = $this->redirect(['action' => 'index']) ;
            $this->Flash->error(__('Not Found Forced'));
        }

        $forced = $this->Forceds
            ->findById($id)
            ->contain(['CreateBy', 'UpdateBy'])
            ->firstOrFail();

        $this->set('forced', $forced);
    }

    /**
     * @param null $id
     * @return \Cake\Http\Response|null
     */
    public function delete($id = null) {
        if (!$id) {
            $this->response = $this->redirect(['action' => 'index']) ;
            $this->Flash->error(__('Not Found Forced'));
        }

        $forced = $this->Forceds->findById($id)->firstOrFail();
        $forced->delete_flg = 1;

        if($this->Forceds->save($forced)){
            $this->Flash->success(__('Delete succeeded'));
            return $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->error(__('Delete failed'));
        }
    }

}