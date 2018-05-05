<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
<?= $this->element('content_header', ['controlName' => __('Notification management'), 'title' => $title, 'icon' => 'fa fa-bell']) ?>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $title ?></h3>
            </div>
            <?= $this->Form->create($notice, ['class' => 'form-horizontal']) ?>
            <div class="box-body">
                <div class="form-group <?= $this->Form->isFieldError('id') ? 'has-error' : '' ?>">
                    <?= $this->Form->label('id', __('ID'), ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-5">
                        <?= $this->Form->text('id', ['class' => 'form-control input-sm', 'disabled' => 'disabled']) ?>
                        <?= $this->Form->error('id') ?>
                    </div>
                </div>
                <div class="form-group <?= $this->Form->isFieldError('title') ? 'has-error' : '' ?>">
                    <?= $this->Form->label('title', __('title'), ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-5">
                        <div class="required-field-block">
                            <?= $this->Form->text('title', ['class' => 'form-control input-sm']) ?>
                            <div class="required-icon">
                                <div class="text">*</div>
                            </div>
                            <?= $this->Form->error('title') ?>
                        </div>
                    </div>
                </div>
                <div class="form-group <?= $this->Form->isFieldError('body') ? 'has-error' : '' ?>">
                    <?= $this->Form->label('body', __('body'), ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-5">
                        <div class="required-field-block">
                            <?= $this->Form->textarea('body', ['class' => 'form-control input-sm']) ?>
                            <div class="required-icon">
                                <div class="text">*</div>
                            </div>
                            <?= $this->Form->error('body') ?>
                        </div>
                    </div>
                </div>
                <div class="form-group <?= $this->Form->isFieldError('category_id') ? 'has-error' : '' ?>">
                    <?= $this->Form->label('category_id', __('type'), ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-5">
                        <?= $this->Form->select('category_id', $list_category, ['class' => 'form-control']); ?>
                        <?= $this->Form->error('category_id') ?>
                    </div>
                </div>
                <div class="form-group <?= $this->Form->isFieldError('start_date') ? 'has-error' : '' ?>">
                    <?= $this->Form->label('start_at', __('start'), ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-5">
                        <?= $this->Form->text('start_at', ['class' => 'form-control input-sm', 'maxlength' => 60, 'id' => 'notice_start_date']) ?>
                        <?= $this->Form->error('start_at') ?>
                    </div>
                </div>
                <div class="form-group <?= $this->Form->isFieldError('end_date') ? 'has-error' : '' ?>">
                    <?= $this->Form->label('end_at', __('end'), ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-5">
                        <?= $this->Form->text('end_at', ['class' => 'form-control input-sm', 'maxlength' => 60, 'id' => 'notice_end_date']) ?>
                        <?= $this->Form->error('end_at') ?>
                    </div>
                </div>
                <div class="form-group <?= $this->Form->isFieldError('public_flg') ? 'has-error' : '' ?>">
                    <?= $this->Form->label('public_flg', __('status'), ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-5">
                        <?=
                        $this->Form->select(
                            'public_flg',
                            [0 => __('private'), 1 => __('Now open')],
                            ['class' => 'form-control']
                        );
                        ?>
                        <?= $this->Form->error('status') ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-5">
                        <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-default']) ?>
                        <?= $this->Form->button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary', 'formnovalidate' => true]) ?>
                    </div>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
