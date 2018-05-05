<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('Forced update notification management'), 'title' => $title, 'icon' => 'fa-download']) ?>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $title ?></h3>
            </div>
            <?php
                $options = ['class' => 'form-horizontal'];
                if($countEnable > 0) {
                    $options['onsubmit'] = "return confirm('Đã có thông báo forced rồi. Bạn có đồng ý vô hiệu hóa thông báo trước!')";
                }
            ?>
            <?= $this->Form->create($forced, $options) ?>
                <div class="box-body">
                    <div class="form-group <?= $this->Form->isFieldError('version') ? 'has-error' : '' ?>">
                        <?= $this->Form->label('version', __('version'), ['class' => 'col-sm-2 control-label']) ?>
                        <div class="col-sm-5">
                            <div class="required-field-block">
                                <?= $this->Form->text('version', ['id' => 'version', 'class' => 'form-control input-sm', 'maxlength' => 5]) ?>
                                <div class="required-icon">
                                    <div class="text">*</div>
                                </div>
                                <?= $this->Form->error('version') ?>
                            </div>
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