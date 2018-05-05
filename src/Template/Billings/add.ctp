<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('Charging log management'), 'title' => $title, 'icon' => 'fa fa-money']) ?>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $title ?></h3>
            </div>
            <?= $this->Form->create($billing, ['class' => 'form-horizontal']) ?>
                <div class="box-body">
                    <div class="form-group <?= $this->Form->isFieldError('user_account_id') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('user_account_id', __('account_id'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <div class="required-field-block">
                                <?= $this->Form->text('user_account_id', ['id' => 'user_account_id', 'class' => 'form-control input-sm']) ?>
                                <div class="required-icon">
                                    <div class="text">*</div>
                                </div>
                                <?= $this->Form->error('user_account_id') ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('billing_start_at') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('billing_start_at', __('billing_start'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <div class="required-field-block">
                                <?= $this->Form->text('billing_start_at', ['id' => 'billing_start_at', 'class' => 'form-control input-sm', 'maxlength' => 128]) ?>
                                <div class="required-icon">
                                    <div class="text">*</div>
                                </div>
                                <?= $this->Form->error('billing_start_at') ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('billing_type') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('billing_type', __('billing_type'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <?= $this->Form->select('billing_type', ['1' => __('1 month'), '2' => __('3 months'), '3' => __('6 months'), '4' => __('one year')],
                                ['class' => 'form-control']) ?>
                            <?= $this->Form->error('billing_type') ?>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('billing_end_at') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('billing_end_at', __('billing_final'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <div class="required-field-block">
                                <?= $this->Form->text('billing_end_at', ['id' => 'billing_end_at', 'class' => 'form-control input-sm', 'maxlength' => 128]) ?>
                                <div class="required-icon">
                                    <div class="text">*</div>
                                </div>
                                <?= $this->Form->error('billing_end_at') ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('combined_object_flg') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('combined_object_flg', __('combined_object_flg'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <?= $this->Form->select('combined_object_flg', [1 => __('Sum covered'), 0 => __('Sum not covered')], ['id' => 'user_account_id', 'class' => 'form-control input-sm']) ?>
                            <?= $this->Form->error('combined_object_flg') ?>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('billing_update_reason') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('billing_update_reason', __('billing_update_reason'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <?= $this->Form->text('billing_update_reason', ['id' => 'billing_update_reason', 'class' => 'form-control input-sm', 'maxlength' => 128]) ?>
                            <?= $this->Form->error('billing_update_reason') ?>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('billing_update_by') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('billing_update_by', __('billing_update_by'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <div class="required-field-block">
                                <?= $this->Form->text('billing_update_by', ['id' => 'billing_update_by', 'class' => 'form-control input-sm', 'maxlength' => 128]) ?>
                                <div class="required-icon">
                                    <div class="text">*</div>
                                </div>
                                <?= $this->Form->error('billing_update_by') ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('device_os') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('device_os', __('device_os'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <?= $this->Form->select('device_os', ['1' => __('iOS'), '2' => __('Android'), '3' => __('他')],
                                                            ['class' => 'form-control']) ?>
                            <?= $this->Form->error('device_os') ?>
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