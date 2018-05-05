<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
<?= $this->element('content_header', ['controlName' => __('Help management'), 'title' => $title, 'icon' => 'fa fa-question-circle-o']) ?>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $title ?></h3>
            </div>
            <?= $this->Form->create($help, ['class' => 'form-horizontal']) ?>
            <div class="box-body">
                <div class="form-group <?= $this->Form->isFieldError('title') ? 'has-error' : '' ?>">
                    <?= $this->Form->label('title', __('question'), ['class' => 'col-sm-2 control-label']) ?>
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
                    <?= $this->Form->label('body', __('answer_content'), ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-5">
                        <div class="required-field-block">
                            <?= $this->Form->textarea('body', ['class' => 'form-control input-sm', 'maxlength' => 128]) ?>
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
                <div class="form-group <?= $this->Form->isFieldError('order_by') ? 'has-error' : '' ?>">
                    <?= $this->Form->label('order_by', __('order_by'), ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-5">
                        <div class="required-field-block">
                            <?= $this->Form->text('order_by', ['class' => 'form-control input-sm']) ?>
                            <div class="required-icon">
                                <div class="text">*</div>
                            </div>
                            <?= $this->Form->error('order_by') ?>
                        </div>
                    </div>
                </div>
                <div class="form-group <?= $this->Form->isFieldError('public_flg') ? 'has-error' : '' ?>">
                    <?= $this->Form->label('public_flg', __('status'), ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-5">
                        <?= $this->Form->select('public_flg', $label['public_flg'], ['class' => 'form-control']); ?>
                        <?= $this->Form->error('status') ?>
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
