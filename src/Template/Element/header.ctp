<header class="main-header">
    <a href="<?= $this->Url->build('/', true) ?>" class="logo">
        <span class="logo-mini"><b>ゴ</b>ルフ</span>
        <span class="logo-lg"><b>ゴルフ</b>システム</span>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="javascript:void(0)" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="">
                        <span class="hidden-xs">
                            <?= $this->request->session()->read('Auth.User.admin_name') ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>