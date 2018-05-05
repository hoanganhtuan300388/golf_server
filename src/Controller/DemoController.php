<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/26/2018
 * Time: 9:31 AM
 */
namespace App\Controller;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class DemoController extends AdminAppController {

    public function initialize() {
        parent::initialize();
        $this->Golf = TableRegistry::get('GolfFields');
    }

    public function index() {
        $golfsDisplay = [];

        $golfs = $this->Golf->find('all')->hydrate(false)->toArray();
        //pr($golfs);exit;
        foreach($golfs as $golf) {
            $golfDisplay = [
                'id'            => $golf['field_id'],
                'name'          => $golf['field_name'],
                'address'       => $golf['address'],
                'shotnavi_url'  => $golf['shotnavi_url'],
                'ngaynghi'      => '',
            ];

            if(!empty($golf['shotnavi_url'])) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $golf['shotnavi_url']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                curl_close($ch);

                $dom = new \DOMDocument();
                @$dom->loadHTML($output);

                $xPath = new \DOMXPath($dom);
                $nodes = $xPath->query("//section[@class='outline-area']//table//tr");

                foreach ($nodes as $node1) {
                    foreach ($xPath->query('th', $node1) as $node2) {
                        if ($node2->nodeValue === '休場日') {
                            foreach ($xPath->query('td', $node1) as $node2) {
                                $golfDisplay['ngaynghi'] = $node2->nodeValue;
                            }
                        }
                    }
                }
            }

            $golfsDisplay[] = $golfDisplay;
        }

        $this->set('golfsDisplay', $golfsDisplay);
    }

}