<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('General user management'), 'title' => $title, 'icon' => 'fa fa-users']) ?>
<?php $this->end(); ?>

<div class="box" id="user-info">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= $title ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('user_account_id', __('account_id'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['id'], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('player_name', __('user_name'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['player_name'], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('email', __('email'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['email'], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('sex', __('sex'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $labelUser['sex'][$user['sex']], ['class' => 'input-group-addon']) ?>
                            </div>

                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('birthday', __('date_of_birth'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['birthday'], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('height', __('height'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['height'], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('weight', __('weight'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['weight'], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('right_left_hander', __('beaters'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $labelUser['right_left_hander'][$user['right_left_hander']], ['class' => 'input-group-addon']) ?>
                            </div>
                            <!--<div class="input-group col-sm-9">
                                <?= $this->Form->label('profile_picture', __('profile_picture'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['profile_picture'], ['class' => 'input-group-addon']) ?>
                            </div>-->
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('facebook_id', __('FacebookID'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['facebook_id'], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('twitter_id', __('TwitterID'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['twitter_id'], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('session_id', __('session_id'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['session_id'], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('pwd_reset_code', __('pass_forget_code'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['pwd_reset_code'], ['class' => 'input-group-addon']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __('Current state of charge') ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('billing_id', __('billing_log_id'), ['class' => 'input-group-addon']) ?>
                                <?php if(!empty($user['Billings']['billing_update_by'])) {
                                     echo $this->Html->link($user['Billings']['id'], ['controller' => 'billings', 'action' => 'edit',$user['Billings']['id']], ['class' => 'input-group-addon']);
                                }else {
                                     echo $this->Html->link($user['Billings']['id'], ['controller' => 'billings', 'action' => 'show',$user['Billings']['id']], ['class' => 'input-group-addon']);
                                }?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('billing_at', __('billing_datetime'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['Billings']['billing_start_at'], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('billing_type', __('billing_type'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $labelBill['billing_type'][$user['Billings']['billing_type']], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('billing_end_at', __('billing_end_at'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['Billings']['billing_end_at'], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('billing_update_flg', __('billing_update_flg'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['Billings']['billing_update_flg'], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('billing_update_reason', __('billing_update_reason'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['Billings']['billing_update_reason'], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('billing_update_by', __('billing_update_by'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $user['Billings']['billing_update_by'], ['class' => 'input-group-addon']) ?>
                            </div>
                            <div class="input-group col-sm-9">
                                <?= $this->Form->label('device_os', __('device_os'), ['class' => 'input-group-addon']) ?>
                                <?= $this->Form->label(null, $labelBill['device_OS'][$user['Billings']['device_os']], ['class' => 'input-group-addon']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <?= $this->Html->Link(__('Back'), ['action' => 'index'], ['class' => 'btn bg-orange']) ?>
    </div>
</div>
<style>
label {
  margin-bottom: 0;
}
</style>

