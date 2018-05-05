<?php $this->assign('title', $title);?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('Maintenance notification management'), 'title' => $title, 'icon' => 'fa-legal']) ?>
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
                        <?= $this->Form->label(null, h($maintenance['id']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('title', __('title'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($maintenance['title']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('title', __('body'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($maintenance['body']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('title', __('status'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, $maintenance['status'] == MAINTENANCE_STATUS_ENABLE ? __('Disabled') : __('Enabled'), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('title', __('start_at'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($maintenance['start_at']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('title', __('end_at'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($maintenance['end_at']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('title', __('create_by'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($maintenance['createby']['login_id']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('title', __('update_by'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($maintenance['updateby']['login_id']), ['class' => 'input-group-addon']) ?>
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