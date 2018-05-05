<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('Charging log management'), 'title' => $title, 'icon' => 'fa fa-money']) ?>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= __('Search condition') ?></h3>
                <div class="box-tools pull-right">
                    <?= $this->Form->button('<i class="fa fa-minus"></i>', ['type' => 'button', 'class' => 'btn btn-box-tool', 'data-widget' => 'collapse']) ?>
                </div>
            </div>
            <div class="box-body">
                <?php echo $this->Form->create(null, ['url' => ['action' => 'search'], 'class' => 'form-horizontal']); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= $this->Form->label('user_account_id', __('account_id'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.user_account_id', ['class' => 'form-control input-sm', 'maxlength' => 255]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('billing_type', __('billing_type'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->select('search.billing_type', [1 => __('1 month'), 2 => __('3 months'), 3 => __('6 months'), 4 => __('one year')],
                                    ['class' => 'form-control input-sm', 'empty' => '--']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= $this->Form->label('billing_before_date', '～' . __('billing_datetime'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.billing_before_date', ['id' => 'billing_before_date' ,'class' => 'form-control input-sm', 'maxlength' => 255]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('billing_update_flg', __('billing_update_flg'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->select('search.billing_update_flg', [0 => __('Nothing'), 1 => __('Yes')], ['class' => 'form-control input-sm', 'empty' => '--']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= $this->Form->label('billing_after_date', __('billing_datetime') . '～', ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.billing_after_date', ['id' => 'billing_before_date', 'class' => 'form-control input-sm', 'maxlength' => 255]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-7">
                                    <?= $this->Form->button('<i class="fa fa-search"></i> ' . __('Search'), ['type' => 'submit', 'class' => 'btn btn-default', 'formnovalidate' => true]) ?>
                                    <?= $this->Html->link('<i class="fa fa-plus"></i> ' . __('Add new'), ['action' => 'add'], ['class' => 'btn btn-primary', 'escape' => false]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $title ?></h3>
                <div class="box-tools pull-right">
                    <?= $this->element('limit') ?>
                </div>
            </div>
            <div class="box-body">
                <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-striped dataTable">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?= $this->Paginator->sort('id', 'ID') ?></th>
                                        <th class="text-center"><?= __('account_id') ?></th>
                                        <th class="text-center"><?= __('billing_type') ?></th>
                                        <th class="text-center"><?= __('OS') ?></th>
                                        <th class="text-center"><?= __('billing_datetime') ?></th>
                                        <th class="text-center"><?= __('end_datetime') ?></th>
                                        <th class="text-center"><?= __('Action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(count($billings) > 0) { ?>
                                        <?php foreach ($billings as $key => $item) { ?>
                                            <tr>
                                                <td><?= h($item['id']) ?></td>
                                                <td class=""><?= $this->Html->link(h($item['user']['player_name']), ['controller' => 'Users', 'action' => 'show',$item['user_account_id']]) ?></td>
                                                <td class=""><?= $labelBill['billing_type'][h($item['billing_type'])] ?></td>
                                                <td class=""><?= $labelBill['device_OS'][h($item['device_os'])] ?></td>
                                                <td class="text-center"><?= h($item['billing_start_at']) ?></td>
                                                <td class="text-center"><?= h($item['billing_end_at']) ?></td>
                                                <td class="text-center">
                                                    <?php if(isset($item['billing_update_by'])) { ?>
                                                        <?= $this->Html->link('<i class="fa fa-fw fa-edit"></i>' . __('Edit'), ['action' => 'edit', $item['id']], ['class' => 'btn btn-info btn-xs', 'escape' => false]) ?>
                                                    <?php } else {?>
                                                        <?= $this->Html->link('<i class="fa fa-fw fa-eye"></i>' . __('Detail'), ['action' => 'show', $item['id']], ['class' => 'btn btn-success btn-xs', 'escape' => false]); ?>
                                                    <?php  }?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td class="dataTables_empty" colspan="8"><?= __('Record not found') ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?= $this->element('paginator') ?>
                </div>
            </div>
        </div>
    </div>
</div>