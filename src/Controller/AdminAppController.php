<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/21/2017
 * Time: 9:51 AM
 */
namespace App\Controller;

use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
class AdminAppController extends AppController
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        /*
         * check authentication login
         */
        $this->loadComponent('Auth', [
            'loginAction' => [
                'controller' => 'Admins',
                'action' => 'login'
            ],
            'authError' => __('Did you really think you are allowed to see that?'),
            'authenticate' => [
                'Form' => [
                    'fields'    => ['username' => 'login_id', 'password' => 'admin_pass'],
                    'userModel' => 'Admins'
                ]
            ],
            'storage' => 'Session',
            //'unauthorizedRedirect' => false
        ]);
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $num_update = count(TableRegistry::get('GolfUpdates')->find('all',[
            'fields' => ['GolfUpdates.updated'],
            'conditions' => ['GolfUpdates.updated IS NULL']
        ])->toArray());
        $this->set('num_update',$num_update);

    }

    /**
     * action common search
     * @return \Cake\Http\Response|null
     */
    public function search()
    {
        $referer = Router::parse(str_replace(Router::url('/', true), '', $this->referer()));

        $query = [];

        if(!empty($referer['?']['limit'])) {
            $query['limit'] = $referer['?']['limit'];
        }

        foreach ($this->request->data as $k => $v) {
            if($k == 'search' || $k == 'display') {
                foreach ($v as $kk => $vv) {
                    if($vv != '') {
                        if(is_array($vv)) {
                            $query[$kk] = urlencode(serialize($vv));
                        } else {
                            $query[$kk] = trim($vv);
                        }
                    }
                }
            }
        }

        return $this->redirect([
            'controller'    => $referer['controller'],
            'action'        => $referer['action'],
            '?'             => $query
        ]);
    }

    public function beforeFilter(Event $event)
    {
        if($this->request->Session()->read('loginfail') > 5) {
            if($this->getCurrentDate() > $this->convertDate($this->request->Session()->read('timeblock'). "+10 minutes")) {
                $this->request->Session()->delete('loginfail');
                $this->request->Session()->delete('timeblock');
            }
        }
        return parent::beforeFilter($event); // TODO: Change the autogenerated stub
    }
}