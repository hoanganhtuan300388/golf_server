<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/10/2018
 * Time: 10:15 AM
 */
namespace App\Controller;

class MaintenancesController extends AdminAppController {

    /**
     * display maintenance list, search, pagination
     */
    public function index() {
        $this->set('title', __('Maintenance notification list'));

        $this->Maintenances->updateAll(
            ['status' => MAINTENANCE_STATUS_DISABLE],
            [
                'end_at <'  => $this->getCurrentDate(),
                'status'    => MAINTENANCE_STATUS_ENABLE
            ]
        );

        $query      = $this->request->getQuery();
        $conditions = [];

        if(!empty($query['id'])) {
            $conditions[]['id'] = $query['id'];
            $this->request->data['search']['id'] = $query['id'];
        }
        if(!empty($query['keyword'])) {
            $conditions[]['OR'] = [
                'title LIKE'    => "%{$query['keyword']}%",
                'body LIKE'     => "%{$query['keyword']}%"
            ];
            $this->request->data['search']['keyword'] = $query['keyword'];
        }
        if(!empty($query['start_at']) && empty($query['end_at'])) {
            $conditions[]['start_at >='] = $query['start_at'];
            $this->request->data['search']['start_at'] = $query['start_at'];
        }
        if(!empty($query['end_at']) && empty($query['start_at'])) {
            $conditions[]['end_at <='] = $query['end_at'];
            $this->request->data['search']['end_at'] = $query['end_at'];
        }
        if(!empty($query['end_at']) && !empty($query['start_at'])) {
            $conditions[]['start_at >='] = $query['start_at'];
            $conditions[]['end_at <='] = $query['end_at'];
            $this->request->data['search']['start_at'] = $query['start_at'];
            $this->request->data['search']['end_at'] = $query['end_at'];
        }
        if(isset($query['status'])) {
            $conditions[]['status'] = $query['status'];
            $this->request->data['search']['status'] = $query['status'];
        }

        $this->request->data['display']['limit'] = !empty($query['limit']) ? $query['limit'] : LIMIT_VALUE;

        $this->paginate = [
            'conditions'    => [$conditions],
            'limit'         => LIMIT_VALUE,
            'order'         => ['id' => 'desc'],
            'contain'       => false
        ];

        $this->set('maintenances', $this->paginate($this->Maintenances));
    }

    /**
     * add new Maintenance
     * @return \Cake\Http\Response|null
     */
    public function add() {
        $this->set('title', __('Maintenance notification create'));

        $countEnable = $this->Maintenances->find('all', [
            'conditions' => [
                'status' => MAINTENANCE_STATUS_ENABLE
            ]
        ])->count();


        $maintenance = $this->Maintenances->newEntity();

        if ($this->request->is('post')) {
            $this->request->data['status']      = MAINTENANCE_STATUS_ENABLE;
            $this->request->data['create_by']   = $this->Auth->user('id');
            $maintenance                        = $this->Maintenances->patchEntity($maintenance, $this->request->data);

            if ($this->Maintenances->save($maintenance)) {
                if($countEnable > 0) {
                    $this->Maintenances->updateAll(
                        ['status' => MAINTENANCE_STATUS_DISABLE],
                        [
                            'id !='     => $maintenance->id,
                            'status'    => MAINTENANCE_STATUS_ENABLE
                        ]
                    );
                }
                $this->Flash->success(__('Registration succeeded'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Registration failed'));
            }
        }

        $this->set(['maintenance' => $maintenance, 'countEnable' => $countEnable]);
    }

    /**
     * @param null $id
     * @return \Cake\Http\Response|null
     */
    public function edit($id = null) {
        $this->set('title', __('Maintenance notification edit'));

        if (!$id) {
            $this->response = $this->redirect(['action' => 'index']) ;
            $this->Flash->error(__('Not Found Maintenance'));
        }

        $maintenance = $this->Maintenances->find('all', [
            'conditions' => [
                'id'        => $id,
                'status'    => MAINTENANCE_STATUS_ENABLE
            ]
        ])->firstOrFail();

        if($this->request->is(['post', 'put'])) {
            $this->request->data['update_by']   = $this->Auth->user('id');
            $maintenance                        = $this->Maintenances->patchEntity($maintenance, $this->request->getData());

            if($this->Maintenances->save($maintenance)) {
                $this->Flash->success(__('Update succeeded'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Update failed'));
            }
        }

        $this->set('maintenance', $maintenance);
    }

    /**
     * @param null $id
     */
    public function view($id = null) {
        $this->set('title', __('Maintenance notification detail information'));

        if (!$id) {
            $this->response = $this->redirect(['action' => 'index']) ;
            $this->Flash->error(__('Not Found Maintenance'));
        }

        $maintenance = $this->Maintenances
            ->findById($id)
            ->contain(['CreateBy', 'UpdateBy'])
            ->firstOrFail();

        $this->set('maintenance', $maintenance);
    }

    /**
     * @param null $id
     * @return \Cake\Http\Response|null
     */
    public function delete($id = null) {
        if (!$id) {
            $this->response = $this->redirect(['action' => 'index']) ;
            $this->Flash->error(__('Not Found Maintenance'));
        }

        $maintenance = $this->Maintenances->findById($id)->firstOrFail();
        $maintenance->delete_flg = 1;

        if($this->Maintenances->save($maintenance)){
            $this->Flash->success(__('Delete succeeded'));
            return $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->error(__('Delete failed'));
        }
    }
}