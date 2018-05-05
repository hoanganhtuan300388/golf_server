<?php $this->assign('title', $title); ?>

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="box box-solid box-default" id="login-box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= __('Login') ?></h3>
            </div>
            <?= $this->Form->create(); ?>
                <div class="box-body">
                    <div class="form-group" style = "font-size : 18px; font-family: inherit">
                    <?= $this->Flash->render('login_fail'); ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->label('login_id', __('ログイン名'), ['class' => 'control-label']) ?>
                        <?= $this->Form->text('login_id', ['id' => 'login-id', 'class' => 'form-control input-lg', 'maxlength' => 32]) ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->label('admin_pass', __('password'), ['class' => 'control-label']) ?>
                        <?= $this->Form->text('admin_pass', ['id' => 'admin-pass', 'class' => 'form-control input-lg', 'type' => 'password', 'maxlength' => 128]) ?>
                    </div>
                    <div class="checkbox">
                        <label>
                            <?= $this->Form->checkbox('remember') ?>
                            <span><?= __('ログイン情報を保存する') ?></span>
                        </label>
                    </div>
                </div>
                <div class="box-footer">
                    <?= $this->Form->button('<strong>' . __('Login') . '</strong>', ['type' => 'submit', 'class' => 'btn btn-primary btn-lg btn-block', 'formnovalidate' => true]) ?>
                </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>