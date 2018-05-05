<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('Maintenance notification management'), 'title' => $title, 'icon' => 'fa-legal']) ?>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $title ?></h3>
            </div>
            <?= $this->Form->create($maintenance, ['class' => 'form-horizontal']) ?>
                <div class="box-body">
                    <div class="form-group <?= $this->Form->isFieldError('id') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('id', __('ID'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <?= $this->Form->text('id', ['id' => 'id', 'class' => 'form-control input-sm', 'disabled' => 'disabled']) ?>
                            <?= $this->Form->error('id') ?>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('title') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('title', __('title'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <div class="required-field-block">
                                <?= $this->Form->text('title', ['id' => 'title', 'class' => 'form-control input-sm', 'maxlength' => 128]) ?>
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
                                <?= $this->Form->textarea('body', ['id' => 'body', 'class' => 'form-control input-sm']) ?>
                                <div class="required-icon">
                                    <div class="text">*</div>
                                </div>
                                <?= $this->Form->error('body') ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('start_at') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('start_at', __('start_at'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <div class="required-field-block">
                                <?= $this->Form->text('start_at', ['id' => 'start_at', 'class' => 'form-control input-sm input-date-format-full', 'readonly' => true, 'maxlength' => 16]) ?>
                                <div class="required-icon">
                                    <div class="text">*</div>
                                </div>
                                <?= $this->Form->error('start_at') ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?= $this->Form->isFieldError('end_at') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('start_at', __('end_at'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <div class="required-field-block">
                                <?= $this->Form->text('end_at', ['id' => 'end_at', 'class' => 'form-control input-sm input-date-format-full', 'readonly' => true, 'maxlength' => 16]) ?>
                                <div class="required-icon">
                                    <div class="text">*</div>
                                </div>
                                <?= $this->Form->error('end_at') ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->label('status_0', __('state'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <div class="radio">
                                <?= $this->Form->radio('status', [0 => __('Disabled'), 1 => __('Enabled')], ['id' => 'status']) ?>
                            </div>
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