<?php
/**
 * Created by PhpStorm.
 * User: Quyen Cao
 * Date: 13/11/2017
 * Time: 11:29 SA
 */

namespace App\Controller;


use Cake\Core\Configure;

use Cake\ORM\TableRegistry;

class NoticesController extends AdminAppController
{
    public function initialize() {
        parent::initialize();
        $this->NoticeCategories = TableRegistry::get('NoticeCategories');
    }

    public function index() {
        $this->set('title', __('Notice list'));

        $query      = $this->request->getQuery();
        $conditions = [];

        if(!empty($query['keyword'])) {
            $conditions[]['body LIKE'] = "%{$query['keyword']}%";
            $this->request->data['search']['keyword'] = $query['keyword'];
        }

        if(isset($query['status'])) {
            $conditions[]['public_flg'] = $query['status'];
            $this->request->data['search']['status'] = $query['status'];
        }

        $this->request->data['display']['limit'] = !empty($query['limit']) ? $query['limit'] : LIMIT_VALUE;

        $this->paginate = [
            'conditions'    => [$conditions],
            'order'         => ['id' => 'desc'],
            'contain'       => false,
            'limit'         => LIMIT_VALUE,
        ];

        $labelNotices = Configure::read('config.notices');

        $this->set(['notices' => $this->paginate($this->Notices), 'labelNotice' => $labelNotices]);
    }

    public function add() {
        $this->set('title', __('Notice registration'));

        $list_category = $this->getListCategory();
        $notices = $this->Notices->newEntity();

        if($this->request->is('post')) {
            $this->Notices->patchEntity($notices, $this->request->data);

            if($this->Notices->save($notices)) {
                $this->Flash->success(__('Registration succeeded'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Registration failed'));
            }
        }

        $this->set(['notice' => $notices, 'list_category' => $list_category]);
    }

    public function edit($id = null) {
        $this->set('title', __('Notice detail information'));

        $list_category = $this->getListCategory();

        if (!$id){
            $this->response = $this->redirect(['action' => 'index']) ;
            $this->Flash->error(__('Not Found Notice'));
        }

        $notice = $this->Notices->findById($id)->first();

        if (empty($notice)){
            $this->response = $this->redirect(['action' => 'index']) ;
            $this->Flash->error(__('Not found this Notice'));
        }

        if($this->request->is(['post', 'put'])) {
            $this->Notices->patchEntity($notice, $this->request->getData());

            if($this->Notices->save($notice)) {
                $this->Flash->success(__('Update succeeded'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Update failed'));
            }
        }

        $this->set(['notice' => $notice, 'list_category' => $list_category]);
    }

    public function delete($id = null) {
        $notice = $this->Notices->get($id);

        $notice->delete_flg = 1;

        if($this->Notices->save($notice)){
            $this->Flash->success(__('Delete succeeded'));
            return $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->error(__('Delete failed'));
        }
    }

    public function getListCategory() {
        $list_category = $this->NoticeCategories->find('list', [
            'keyField'      => 'id',
            'valueField'    => 'category_name'
        ])->toArray();

        return $list_category;
    }
}