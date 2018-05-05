<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/22/2018
 * Time: 10:02 AM
 */
namespace App\Error;

use Cake\Error\ExceptionRenderer;

class AppExceptionRenderer extends ExceptionRenderer
{

    /*public function pdo($error) {
        if(!empty($this->controller->request->params['plugin'])) {
            $plugin = $this->controller->request->params['plugin'];

            if($plugin == 'Api') {
                $this->__renderJson(500, $error->getMessage());
            }
        }

        parent::handleException($error);
    }

    private function __renderJson($status, $message) {
        header('Content-type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Status: ' . $status);

        $data = [
            'status'    => $status,
            'message'   => $message
        ];

        echo json_encode($data);
        exit();
    }*/

}
