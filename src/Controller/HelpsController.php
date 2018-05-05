<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class HelpsController extends AdminAppController {

    public function initialize() {
        parent::initialize();
        $this->HelpCategories = TableRegistry::get('HelpCategories');
    }

    public function index() {
        $this->set('title', __('Help list'));

        $query          = $this->request->getQuery();
        $conditions     = [];
        $list_category  = $this->getListCategory();

        if(!empty($query['keyword'])) {
            $conditions[]['title LIKE'] = "%{$query['keyword']}%";
            $this->request->data['search']['keyword'] = $query['keyword'];
        }
        if(!empty($query['category'])) {
            $conditions[]['category_id'] = $query['category'];
            $this->request->data['search']['category'] = $query['category'];
        }
        if(isset($query['public_flg'])) {
            $conditions[]['public_flg'] = $query['public_flg'];
            $this->request->data['search']['public_flg'] = $query['public_flg'];
        }

        $this->request->data['display']['limit'] = !empty($query['limit']) ? $query['limit'] : LIMIT_VALUE;

        $fields = ['Helps.id', 'Helps.title', 'Helps.order_by', 'Helps.category_id', 'Helps.created', 'Helps.public_flg'];

        $this->paginate = [
            'fields'        => $fields,
            'conditions'    => $conditions,
            'limit'         => LIMIT_VALUE,
            'order'         => ['ISNULL(order_by)',  'order_by' => 'asc'],
        ];

        $this->set(['data' => $this->paginate($this->Helps), 'label' => Configure::read('config.help'), 'list_category' => $list_category]);
    }

    public function add() {
        $this->set('title', __('Help registration'));

        $list_category = $this->getListCategory();
        $help = $this->Helps->newEntity();

        if ($this->request->is('post')) {
            $help = $this->Helps->patchEntity($help, $this->request->getData());
            if ($this->Helps->save($help)) {
                $this->Flash->success(__('Registration succeeded'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Registration failed'));
            }
        } else {
            $helpOrderBy    = $this->Helps->find()->order(['order_by' => 'desc'])->first();
            $help->order_by = $helpOrderBy['order_by'] + 1;
        }

        $this->set(['help' => $help, 'label' => Configure::read('config.help'), 'list_category' => $list_category]);
    }

    public function edit($id = null) {
        $this->set('title', __('Help detailed information'));

        $list_category = $this->getListCategory();

        if (!$id){
            $this->response = $this->redirect(['action' => 'index']) ;
            $this->Flash->error(__('Not Found Help'));
        }

        $help = $this->Helps->findById($id)->first();

        if (empty($help)){
            $this->response = $this->redirect(['action' => 'index']) ;
            $this->Flash->error(__('Not Found Help'));
        }

        if($this->request->is(['post', 'put'])) {
            $help = $this->Helps->patchEntity($help, $this->request->getData());
            if($this->Helps->save($help)) {
                $this->Flash->success(__('Update succeeded'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Update failed'));
            }
        }

        $this->set(['help' => $help, 'label' => Configure::read('config.help'), 'list_category' => $list_category]);
    }
    
    public function delete($id) {
        $help = $this->Helps->get($id);

        $help->delete_flg = 1;

        if($this->Helps->save($help)) {
            $this->Flash->success(__('Delete succeeded'));
            $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->error(__('Delete failed'));
        }
    }

    public function getListCategory() {
        $list_category = $this->HelpCategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'category_name'
        ])->toArray();

        return $list_category;
    }
}