<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/21/2017
 * Time: 2:47 PM
 */
namespace Api\Controller;

use Cake\Routing\Router;

class DebugController extends AppController
{

    public $uses = [];

    public function index()
    {
        $this->methodBadRequest();
    }

    /**
     * @param null $filename
     * @param bool $delete
     */
    public function viewlogs($filename = null, $delete = false)
    {
        $html = '';

        //delete file log
        if($filename && file_exists(LOGS . DS . $filename) && $delete) {
            unlink(LOGS . DS . $filename);
            $delete = true;
        }

        if(!$filename || ($filename && $delete === true)) {
            $data = $this->_getDataList('.log');
            $html .= empty($data) ? 'Not Found Data' : $this->_displayData($data);
        } else {
            if($filename && !file_exists(LOGS . DS . $filename)) {
                $html .= $this->_displayHeaderHome();
                $html .= __('File not found.');
            }
        }

        header('Content-Type: text/html; charset=utf8');
        echo '<pre>';

        if($filename && file_exists(LOGS . DS . $filename)) {
            echo $this->_displayHeaderHome();
            print_r(htmlspecialchars(file_get_contents(LOGS . DS . $filename)));
        } else {
            echo $html;
        }

        echo '</pre>';
        exit();
    }

    /**
     * @param string $ext
     * @return array
     */
    protected function _getDataList($ext = '.log')
    {
        $dir = LOGS . DS;
        $data = [];
        if(is_dir($dir)) {
            $dir = rtrim($dir, "/\\") . DS;
            $files = glob($dir . "api_*" . $ext);

            if($files === false) {
                return $data;
            }

            $files = array_reverse($files);
            foreach($files as $file) {
                $tmp = [];
                $tmp['filename'] = basename($file);
                $tmp['datetime'] = date('Y-m-d H:i:s', filemtime($file));
                $data[filemtime($file)] = $tmp;
            }
            krsort($data);
        }

        return array_values($data);
    }

    /**
     *
     */
    public function download_log()
    {
        $api_log_file = 'api_logging_' . date("Ymd", time()) . ".log";
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachemnt; filename="' . basename($api_log_file) . '"');
        header("Content-Transfer-Encoding: binary\n");
        echo file_get_contents(LOGS . DS . $api_log_file);
        exit();
    }

    /**
     * @param $data
     * @param bool $echo
     * @return string
     */
    private function _displayData($data, $echo = false)
    {
        $one = $data[0];
        $str = "<table width='100%' border='0' style='border: 1px solid #ccc;'><thead><tr>";
        foreach ($one as $k => $v) {
            $str .= "<th style='border-left: 1px solid #ccc;border-bottom: 1px solid #ccc; background: #cdcdcd;padding: 8px;'>" . ucfirst($k) . "</th>";
        }
        $str .= "<th style='border-left: 1px solid #ccc;border-bottom: 1px solid #ccc; background: #cdcdcd;padding: 8px;'>Action</th>";
        $str .= "</tr></thead><tbody>";

        foreach($data as $i => $_item) {
            $str .= '<tr>';
            $style = "padding: 8px;";
            if($i % 2) {
                $style .= "border-left: 1px solid #ccc;border-bottom: 1px solid #ccc; background: #cdcdcd;";
            } else {
                $style .= "background: #FFF;";
            }
            $_i = 0;
            foreach($_item as $k => $v) {
                $str .= "<td style='" . $style . "'>";
                $str .= ($_i) ? $v : '<a href="' . Router::url("/") . 'api/viewlogs/' . $v . '" title="View detail log ' . $v . '">' . $v . '</a>';
                $str .= "</td>";
                $_i++;
            }

            $str .= "<td style='" . $style . "'>";
            $str .= '<a href="' . Router::url("/") . 'api/viewlogs/' . $_item['filename'] . '/delete" title="Delete log ' . $_item['filename'] . '">Delete</a>';
            $str .= "</td>";
            $str .= '</tr>';
        }
        $str .= "</table>";

        if($echo === true) {
            echo $str;
        } else {
            return $str;
        }
    }

    /**
     * @return string
     */
    private function _displayHeaderHome()
    {
        $str = "<table width='100%' border='0' style='border: 1px solid #ccc;'><thead><tr>";
        $str .= "<th style='border-left: 1px solid #ccc;border-bottom: 1px solid #ccc; background: #cdcdcd;padding: 8px;'>";
        $str .= '<a href="' . Router::url("/") . 'api/viewlogs/">Back</a>';
        $str .= "</th>";
        $str .= "</tr></thead><tbody>";
        $str .= "</table>";
        return $str;
    }
}