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
                    <div class="form-group <?= $this->Form->isFieldError('login_id') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('login_id', __('login_id'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <div class="required-field-block">
                                <?= $this->Form->text('login_id', ['id' => 'login-id', 'class' => 'form-control input-sm', 'maxlength' => 32]) ?>
                                <div class="required-icon">
                                    <div class="text">*</div>
                                </div>
                                <?= $this->Form->error('login_id') ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('password_plain') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('password_plain', __('password'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <div class="required-field-block">
                                <?= $this->Form->password('password_plain', ['id' => 'password-plain', 'class' => 'form-control input-sm', 'maxlength' => 128]) ?>
                                <div class="required-icon">
                                    <div class="text">*</div>
                                </div>
                                <?= $this->Form->error('password_plain') ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('password_confirm') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('password_confirm', __('password_confirm'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <div class="required-field-block">
                                <?= $this->Form->password('password_confirm', ['id' => 'password-confirm', 'class' => 'form-control input-sm', 'maxlength' => 128]) ?>
                                <div class="required-icon">
                                    <div class="text">*</div>
                                </div>
                                <?= $this->Form->error('password_confirm') ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('admin_name') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('admin_name', __('管理者名'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <?= $this->Form->text('admin_name', ['id' => 'admin-name', 'class' => 'form-control input-sm', 'maxlength' => 128]) ?>
                            <?= $this->Form->error('admin_name') ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->label('status_0', __('state'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <div class="radio">
                                <?= $this->Form->radio('status', [0 => __('Disabled'), 1 => __('Enabled')], ['id' => 'status', 'default' => 1]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-5">
                            <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-default']) ?>
                            <?= $this->Form->button(__('Add'), ['type' => 'submit', 'class' => 'btn btn-primary', 'formnovalidate' => true]) ?>
                        </div>
                    </div>
                </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>