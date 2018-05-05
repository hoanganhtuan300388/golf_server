<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('Administrator management'), 'title' => $title, 'icon' => 'fa-user-secret']) ?>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $title ?></h3>
            </div>
            <?= $this->Form->create($admin, ['class' => 'form-horizontal']) ?>
                <div class="box-body">
                    <div class="form-group <?= $this->Form->isFieldError('id') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('id', __('ID'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <?= $this->Form->text('id', ['id' => 'id', 'class' => 'form-control input-sm', 'disabled' => 'disabled']) ?>
                            <?= $this->Form->error('id') ?>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('login_id') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('login_id', __('login_id'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <?= $this->Form->text('login_id', ['id' => 'login_id', 'class' => 'form-control input-sm', 'disabled' => 'disabled']) ?>
                            <?= $this->Form->error('login_id') ?>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('password_plain') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('password_plain', __('password'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <?= $this->Form->password('password_plain', ['id' => 'password_plain', 'class' => 'form-control input-sm', 'maxlength' => 128]) ?>
                            <?= $this->Form->error('password_plain') ?>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('password_confirm') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('password_confirm', __('password_confirm'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <?= $this->Form->password('password_confirm', ['id' => 'password-confirm', 'class' => 'form-control input-sm', 'maxlength' => 128]) ?>
                            <?= $this->Form->error('password_confirm') ?>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('admin_name') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('admin_name', __('管理者名'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <?= $this->Form->text('admin_name', ['id' => 'admin-name', 'class' => 'form-control input-sm', 'maxlength' => 128]) ?>
                            <?= $this->Form->error('admin_name') ?>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('last_login_time') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('last_login_time', __('最後ログイン日付'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <?= $this->Form->text('last_login_time', ['id' => 'last_login_time', 'class' => 'form-control input-sm', 'maxlength' => 128, 'disabled' => 'disabled']) ?>
                            <?= $this->Form->error('last_login_time') ?>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('updated') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('updated', __('更新日付'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <?= $this->Form->text('updated', ['id' => 'updated', 'class' => 'form-control input-sm', 'maxlength' => 128, 'disabled' => 'disabled']) ?>
                            <?= $this->Form->error('updated') ?>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('created') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('created', __('date_of_create'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <?= $this->Form->text('created', ['id' => 'created', 'class' => 'form-control input-sm', 'maxlength' => 128, 'disabled' => 'disabled']) ?>
                            <?= $this->Form->error('created') ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->label('status_0', __('state'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <div class="radio">
                                <?= $this->Form->radio('status', [0 => __('Disabled'), 1 => __('Enabled')], ['id' => 'status', 'default' => $admin['status']]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-5">
                            <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-default']) ?>
                            <?= $this->Form->button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary', 'formnovalidate' => true]) ?>
                            <?= $this->Html->link(__('Delete'), ['action' => 'delete',$admin['id']],['type' => 'reset', 'class' => 'btn btn-danger', 'confirm' => __('Certainly deleted data?')]) ?>                        </div>
                    </div>
                </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>