<?php $this->assign('title', $title);?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('Forced update notification management'), 'title' => $title, 'icon' => 'fa-download']) ?>
<?php $this->end(); ?>

<div class="box" id="billing-details">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $title ?></h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-9">
                <div class="form-group">
                    <div class="input-group">
                        <?= $this->Form->label('id', __('ID'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($forced['id']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('title', __('version'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($forced['version']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('title', __('title'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($forced['title']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('title', __('body'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($forced['body']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('title', __('status'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, $forced['status'] == FORCED_STATUS_ENABLE ? __('Disabled') : __('Enabled'), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('title', __('create_by'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($forced['createby']['login_id']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('title', __('update_by'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($forced['updateby']['login_id']), ['class' => 'input-group-addon']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <div class="form-group col-xs-3 col-lg-1">
            <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn bg-orange', 'formnovalidate' => true]) ?>
        </div>
    </div>
</div>
<style>
    label {
        margin-bottom: 0;
    }
</style>