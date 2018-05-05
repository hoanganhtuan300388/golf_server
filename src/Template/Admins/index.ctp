<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('Administrator management'), 'title' => $title, 'icon' => 'fa-user-secret']) ?>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $this->Form->label('login_id', __('login_id'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.login_id', ['class' => 'form-control input-sm', 'maxlength' => 255]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('name', __('管理者名'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.admin_name', ['class' => 'form-control input-sm', 'maxlength' => 255]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $this->Form->label('status', __('state'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->select('search.status', [1 => __('Enabled'), 0 => __('Disabled')], ['class' => 'form-control input-sm', 'empty' => '--']) ?>
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
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-bordered table-striped dataTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><?= $this->Paginator->sort('id', 'ID') ?></th>
                                            <th class="text-center"><?= $this->Paginator->sort('login_id', __('login_id')) ?></th>
                                            <th class="text-center"><?= $this->Paginator->sort('admin_name', '管理者名') ?></th>
                                            <th class="text-center"><?= $this->Paginator->sort('status', __('state')) ?></th>
                                            <th class="text-center" width="120"><?= $this->Paginator->sort('last_login_time', '最後ログイン') ?></th>
                                            <th class="text-center" width="110"><?= __('Action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($admins) > 0) { ?>
                                            <?php foreach($admins as $key => $item) { ?>
                                                <tr>
                                                    <td><?= $this->Html->link(h($item['id']), ['action' => 'edit', $item['id']]) ?></td>
                                                    <td><?= h($item['login_id']) ?></td>
                                                    <td><?= h($item['admin_name']) ?></td>
                                                    <td><?= h($item['status']) == 1 ? __('Enabled') : __('Disabled') ?></td>
                                                    <td><?= h($item['last_login_time']) ?></td>
                                                    <td class="text-center">
                                                        <?= $this->Html->link('<i class="fa fa-fw fa-edit"></i>' . __('Edit'), ['action' => 'edit', $item['id']], ['class' => 'btn btn-info btn-xs', 'escape' => false]) ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td class="dataTables_empty" colspan="6"><?= __('Record not found') ?></td>
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