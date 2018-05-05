<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header"><?= __('Menu') ?></li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'golfs', 'action' => 'index'], true) ?>">
                    <i class="fa fa-table"></i>
                    <span><?= __('Golf course management') ?></span>
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'admins', 'action' => 'index'], true) ?>">
                    <i class="fa fa-user-secret"></i>
                    <span><?= __('Administrator management') ?></span>
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'users', 'action' => 'index'], true) ?>">
                    <i class="fa fa-users"></i>
                    <span><?= __('General user management') ?></span>
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'billings', 'action' => 'index'], true) ?>">
                    <i class="fa fa-money"></i>
                    <span><?= __('Charging log management') ?></span>
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'notices', 'action' => 'index'], true) ?>">
                    <i class="fa fa-bell"></i>
                    <span><?= __('Notification management') ?></span>
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'golf_updates', 'action' => 'index'], true) ?>">
                    <i class="fa fa-exchange"></i>
                    <span><?= __('Notice of change in par') ?></span>
                    <?php if($num_update > 0) { ?>
                    <span class="pull-right-container">
                        <small class="label pull-right bg-red">
                            <?= $num_update ?>
                        </small>
                    </span>
                    <?php }?>
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'helps', 'action' => 'index'], true) ?>">
                    <i class="fa fa-question-circle-o"></i>
                    <span><?= __('Help management') ?></span>
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'maintenances', 'action' => 'index'], true) ?>">
                    <i class="fa fa-legal"></i>
                    <span><?= __('Maintenance notification management') ?></span>
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'forceds', 'action' => 'index'], true) ?>">
                    <i class="fa fa-download"></i>
                    <span><?= __('Forced update notification management') ?></span>
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'admins', 'action' => 'logout'], true) ?>">
                    <i class="fa fa-sign-out"></i>
                    <span><?= __('logout') ?></span>
                </a>
            </li>
        </ul>
    </section>
</aside>