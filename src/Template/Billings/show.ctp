<?php $this->assign('title', $title);?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('Charging log management'), 'title' => $title, 'icon' => 'fa fa-money']) ?>
<?php $this->end(); ?>

<div class="box" id="billing-details">
    <div class="box-header with-border">
        <h3 class="box-title"><?= __('課金詳細情報') ?></h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-9">
                <div class="form-group">
                    <div class="input-group">
                        <?= $this->Form->label('id', __('ID'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($billing['id']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('account_id', __('account_id'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($billing['user_account_id']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('billing_date', __('billing_start'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($billing['billing_start_at']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('billing_type', __('billing_type'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, $labelBill['billing_type'][$billing['billing_type']], ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('billing_end_date', __('billing_final'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($billing['billing_end_at']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('billing_status', __('billing_status'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($billing['status']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('combined_object_flg', __('combined_object_flg'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, $labelBill['combined_object_flg'][$billing['combined_object_flg']], ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('billing_end_date', __('billing_end_at'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($billing['billing_end_date']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('billing_update_flg', __('billing_update_flg'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($billing['billing_update_flg']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('billing_update_reason', __('billing_update_reason'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($billing['billing_update_reason']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('billing_update_at', __('billing_update_at'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($billing['billing_update_at']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('billing_update_by', __('billing_update_by'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, h($billing['billing_update_by']), ['class' => 'input-group-addon']) ?>
                    </div>
                    <div class="input-group">
                        <?= $this->Form->label('device_os', __('device_os'), ['class' => 'input-group-addon']) ?>
                        <?= $this->Form->label(null, $labelBill['device_OS'][$billing['device_os']], ['class' => 'input-group-addon']) ?>
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