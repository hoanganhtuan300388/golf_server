<?php

namespace App\Controller;


class SettingsController extends AdminAppController
{
    public function index() {
        $this->set('title', __('コンスタント一覧'));

        $this->set('settings', $this->paginate($this->Settings));
    }

    public function add() {
        $this->set('title', __(' コンスタント管理'));

        $this->set('constant', $this->Settings->newEntity());
    }
}