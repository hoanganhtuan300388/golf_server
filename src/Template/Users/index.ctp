<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('General user management'), 'title' => $title, 'icon' => 'fa fa-users']) ?>
<?php $this->end(); ?>

<div id="users-management">
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
                    <?= $this->Form->create(null, ['url' => ['action' => 'search'], 'class' => 'form-horizontal']) ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= $this->Form->label('id', __('user_id'), ['class' => 'col-sm-4 control-label']) ?>
                                    <div class="col-sm-7">
                                        <?= $this->Form->text('search.id', ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?= $this->Form->label('player_name', __('user_name'), ['class' => 'col-sm-4 control-label']) ?>
                                    <div class="col-sm-7">
                                        <?= $this->Form->text('search.player_name', ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= $this->Form->label('status', __('billing_status'), ['class' => 'col-sm-4 control-label']) ?>
                                    <div class="col-sm-7">
                                        <?= $this->Form->select('search.status', [1 => __('Charging'), 0 => __('Non charge')], ['class' => 'form-control input-sm', 'empty' => '--']) ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?= $this->Form->label('billing_type', __('billing_type'), ['class' => 'col-sm-4 control-label']) ?>
                                    <div class="col-sm-7">
                                    <?= $this->Form->select('search.billing_type', [1 => __('1 Month'), 2 => __('3 Month'), 3 => __('6 Month'), 4 => __('1 Year')], ['class' => 'form-control', 'empty' => '--']) ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-7">
                                        <?= $this->Form->button('<i class="fa fa-search"></i> ' . __('Search'), ['type' => 'submit', 'class' => 'btn btn-default', 'formnovalidate' => true]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?= $this->Form->end() ?>
                </div>
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
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-bordered table-striped dataTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><?= $this->Paginator->sort('id', 'ID') ?></th>
                                            <th class="text-center"><?= $this->Paginator->sort('player_name', __('player_name')) ?></th>
                                            <th class="text-center"><?= __('email') ?></th>
                                            <th class="text-center"><?= __('billing_status') ?></th>
                                            <th class="text-center"><?= __('billing_type') ?></th>
                                            <th class="text-center"><?= __('billing_start') ?></th>
                                            <th class="text-center"><?= __('billing_end') ?></th>
                                            <th class="text-center"><?= __('Action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($users) > 0) { ?>
                                            <?php foreach ($users as $key => $item) {
                                                if(!isset($item['premium_end_at']) || $item['premium_end_at']->isPast()) {
                                                    $item['billing_status'] = __('Not Purchase');
                                                } else {
                                                    $item['billing_status'] = __('Purchased');
                                                }
                                            ?>
                                                <tr>
                                                    <td><?= $this->Html->link(h($item['id']), ['action' => 'show', $item['id']]) ?></td>
                                                    <td class=""><?= h($item['player_name']) ?></td>
                                                    <td class=""><?= h($item['email']) ?></td>
                                                    <td class=""><?= h($item['billing_status']) ?></td>
                                                    <td class=""><?= $label[$item['Billings']['billing_type']] ?></td>
                                                    <td class="text-center"><?= h($item['Billings']['billing_start_at']) ?></td>
                                                    <td class="text-center"><?= h($item['Billings']['billing_end_at']) ?></td>
                                                    <td class="text-center">
                                                        <?= $this->Html->link('<i class="fa fa-fw fa-eye"></i>' . __('Detail'), ['action' => 'show', $item['id']], ['class' => 'btn btn-success btn-xs', 'escape' => false]) ?>
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
                    </div>
                    <?= $this->element('paginator') ?>
                </div>
            </div>
        </div>
    </div>
</div>
