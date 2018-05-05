<?php

namespace App\Controller;


use Cake\Core\Configure;

class GolfUpdatesController extends AdminAppController
{
    public function index() {
        $this->set('title', __('Number of change notification list'));

        $query      = $this->request->getQuery();
        $conditions = [];

        if(!empty($query['account_id'])) {
            $conditions[]['update_by'] = $query['account_id'];
            $this->request->data['search']['account_id'] = $query['account_id'];
        }
        if(!empty($query['golf_field_id'])) {
            $conditions[]['update_golf_field_id'] = $query['golf_field_id'];
            $this->request->data['search']['golf_field_id'] = $query['golf_field_id'];
        }
        if(!empty($query['notice_before_date'])) {
            $conditions[]['update_at >'] = $query['notice_before_date'];
            $this->request->data['search']['notice_before_date'] = $query['notice_before_date'];
        }
        if(!empty($query['notice_after_date'])) {
            $conditions[]['update_at <'] = $query['notice_after_date'];
            $this->request->data['search']['notice_after_date'] = $query['notice_after_date'];
        }
        if(isset($query['status'])) {
            $conditions[]['status'] = $query['status'];
            $this->request->data['search']['status'] = $query['status'];
        }

        $this->request->data['display']['limit'] = !empty($query['limit']) ? $query['limit'] : LIMIT_VALUE;

        $this->paginate = [
            'conditions'    => [$conditions],
            'order'         => ['id' => 'desc'],
            'contain'       => false,
            'limit'         => LIMIT_VALUE
        ];

        $labelGolf = Configure::read('config.golf_update');

        $this->set(['par_notices' => $this->paginate($this->GolfUpdates), 'labelGolf' => $labelGolf]);
    }

    public function edit($id, $status) {
        $golf_update = $this->GolfUpdates->get($id);

        $golf_update->status = $status;

        if($this->GolfUpdates->save($golf_update)){
            $this->Flash->success(__('Status has been updated'));
            return $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->error(__('Status could not be update'));
        }
    }
}