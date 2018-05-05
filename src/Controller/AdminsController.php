<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2017
 * Time: 1:47 PM
 */
namespace App\Controller;

class AdminsController extends AdminAppController {

    public function initialize() {
        parent::initialize();
        $this->Auth->allow('login');
    }

    /**
     * display admin list, search, pagination
     */
    public function index() {
        $this->set('title', __('Administrator list'));

        $query      = $this->request->getQuery();
        $conditions = [];

        if(!empty($query['login_id'])) {
            $conditions[]['login_id LIKE'] = "%{$query['login_id']}%";
            $this->request->data['search']['login_id'] = $query['login_id'];
        }
        if(!empty($query['admin_name'])) {
            $conditions[]['admin_name LIKE'] = "%{$query['admin_name']}%";
            $this->request->data['search']['admin_name'] = $query['admin_name'];
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

        $this->set('admins', $this->paginate($this->Admins));
    }

    /**
     * add new admin
     * @return \Cake\Http\Response|null
     */
    public function add() {
        $this->set('title', __('Administrator registration'));

        $admin = $this->Admins->newEntity();

        if ($this->request->is('post')) {
            $admin = $this->Admins->patchEntity($admin, $this->request->data);

            if ($this->Admins->save($admin)) {
                $this->Flash->success(__('Registration succeeded'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Registration failed'));
            }
        }

        $this->set('admin', $admin);
    }

    /**
     * @return mixed
     */
    public function login() {
        $session = $this->request->Session();

        if($this->Auth->user()) {
            $this->redirect(['controller' => 'golfs', 'action' => 'index']);
        }

        if ($this->request->is('post')) {
            if($session->read('loginfail') < 5 ) {
                $admin = $this->Auth->identify();

                if (!empty($admin)) {
                    $this->Auth->setUser($admin);

                    $admin = $this->Admins->get($this->Auth->user('id'));
                    $admin->last_login_time = $this->getCurrentDate();
                    $this->Admins->save($admin);
                    $session->delete('loginfail');
                    $session->delete('timeblock');
                    return $this->redirect(['controller' => 'golfs', 'action' => 'index']);
                }

                $this->Flash->error(__('Username or password is incorrect'), [
                    'key' => 'login_fail',
                ]);
            } else {
                $this->Flash->error(__('You must be waitting for 10 minutes to login again'));
            }

            if ($session->read('loginfail')) {
                $login_fail = $session->read('loginfail') + 1;
            } else {
                $login_fail = 1;
            }

            if($session->read('loginfail') == 5) {
                $session->write('timeblock',$this->getCurrentDate());
            }

            $session->write("loginfail", $login_fail);
        }

        $this->set('title', __('Login'));

        $this->viewBuilder()->setLayout('auth_default');
    }

    /**
     * @return \Cake\Http\Response|null
     */
    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    /**
     * @param null $id
     * @return \Cake\Http\Response|null
     */
    public function edit($id = null) {
        $this->set('title', __('Administrator detail information'));

        if (!$id){
            $this->response = $this->redirect(['action' => 'index']) ;
            $this->Flash->error(__('Not Found Admin'));
        }

        $admin = $this->Admins->findById($id)->firstOrFail();

        if($this->request->is(['post', 'put'])) {
            $admin = $this->Admins->patchEntity($admin, $this->request->getData());

            if($this->Admins->save($admin)) {
                $this->Flash->success(__('Update succeeded'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Update failed'));
            }
        }

        $this->set('admin',$admin);
    }

}