<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/21/2017
 * Time: 2:10 PM
 */
namespace Api\Controller;

use Cake\ORM\TableRegistry;

class TestController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Admins = TableRegistry::get('Admins');
    }

    /**
     * Api hiển thị danh sách quản trị
     */
    public function getList()
    {
        //get param
        $output = $this->getBaseOutputParams();

        if($this->request->is('get')) {
            $admins = $this->Admins->find('all');

            $data = [];

            foreach($admins as $admin) {
                $data[] = $admin;
            }

            $output['status']   = API_CODE_OK;
            $output['message']  = __('Danh sách quản trị');
            $output['data']     = $data;
            $this->renderJson($output);
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * Api hiển thị thông tin quản trị
     */
    public function getItem() {
        //get param
        $output = $this->getBaseOutputParams();
        $params = $this->getParams();

        if (!isset($params['id']) || empty($params['id'])) {
            $this->_errors['id'] = __('Id truyền vào thiếu hoặc không đúng');
        }

        if (!empty($this->_errors)) {
            $this->sendError(__('Tham số truyền vào thiếu hoặc không đúng'), API_CODE_NG, API_HTTP_CODE_200, $this->_errors);
        }

        if($this->request->is('get')) {
            $id         = isset($params['id']) ? $params['id'] : null;
            $fields     = ['id', 'login_id', 'admin_name', 'last_login_time'];

            $admin = $this->Admins->find('all', [
                'conditions'    => ['id' => $id],
                'fields'        => $fields,
                'contain'       => false
            ])->first();

            if(empty($admin)) {
                $this->sendError(__('Quản trị không tồn tại'), API_CODE_NG, API_HTTP_CODE_200);
            }

            $output['status']   = API_CODE_OK;
            $output['message']  = __('Chi tiết quản trị');
            $output['data']     = $admin;
            $this->renderJson($output);
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * Api thêm mới quản trị
     */
    public function addItem() {
        //get param
        $output = $this->getBaseOutputParams();
        $params = $this->getParams();

        $admin = $this->Admins->newEntity();

        if($this->request->is('post')) {
            $data = [
                'login_id'          => isset($params['login_id']) ? $params['login_id'] : null,
                'admin_name'        => isset($params['admin_name']) ? $params['admin_name'] : null,
                'password_plain'    => isset($params['password_plain']) ? $params['password_plain'] : null,
                'status'            => isset($params['status']) ? $params['status'] : null,
            ];

            $admin = $this->Admins->patchEntity($admin, $data);

            if($this->Admins->save($admin)) {
                $output['status']   = API_CODE_OK;
                $output['message']  = __('Thêm mới quản trị thành công');
                $output['data']     = array('id' => $admin->id);
                $this->renderJson($output);
            } else {
                $this->sendError(__('Lỗi xảy ra trong quá trình thêm mới quản trị'), API_CODE_NG, API_HTTP_CODE_200, $admin->errors());
            }
        } else {
            $this->methodNotAllow();
        }
    }

    /**
     * Api sửa thông tin quản trị
     */
    public function editItem() {
        //get param
        $output = $this->getBaseOutputParams();
        $params = $this->getParams();

        if(!isset($params['id']) || empty($params['id'])) {
            $this->_errors['id'] = __('Id truyền vào thiếu hoặc không đúng');
        }

        if(!empty($this->_errors)) {
            $this->sendError(__('Tham số truyền vào thiếu hoặc không đúng'), API_CODE_NG, API_HTTP_CODE_200, $this->_errors);
        }

        if($this->request->is('put')) {
            $admin = $this->Admins->get($params['id']);

            if(!empty($admin)) {
                $data = [
                    'admin_name' => isset($params['admin_name']) ? $params['admin_name'] : null
                ];

                $admin = $this->Admins->patchEntity($admin, $data);

                if ($this->Admins->save($admin)) {
                    $output['status']   = API_CODE_OK;
                    $output['message']  = __('Sửa thông tin quản trị thành công');
                    $output['data']     = array('id' => $admin->id);
                    $this->renderJson($output);
                } else {
                    $this->sendError(__('Lỗi xảy ra trong quá trình sửa thông tin quản trị'), API_CODE_NG, API_HTTP_CODE_200, $admin->errors());
                }
            } else {
                $this->sendError(__('Quản trị không tồn tại'), API_CODE_NG, API_HTTP_CODE_200);
            }
        } else {
            $this->methodNotAllow();
        }
    }
}