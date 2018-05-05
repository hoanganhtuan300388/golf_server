<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('Maintenance notification management'), 'title' => $title, 'icon' => 'fa-legal']) ?>
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
                                <?= $this->Form->label('id', __('ID'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.id', ['class' => 'form-control input-sm', 'maxlength' => 20]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('keyword', __('keyword'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.keyword', ['class' => 'form-control input-sm', 'maxlength' => 128]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= $this->Form->label('start_at', __('start_at'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.start_at', ['class' => 'form-control input-sm input-date-format-full', 'maxlength' => 20]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('end_at', __('end_at'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.end_at', ['class' => 'form-control input-sm input-date-format-full', 'maxlength' => 20]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= $this->Form->label('status', __('status'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->select('search.status', [0 => __('Disabled'), 1 => __('Enabled')], ['class' => 'form-control input-sm', 'empty' => '--']) ?>
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
                                            <th class="text-center"><?= $this->Paginator->sort('id', __('ID')) ?></th>
                                            <th class="text-center"><?= __('title') ?></th>
                                            <th class="text-center"><?= __('body') ?></th>
                                            <th class="text-center"><?= __('start_at') ?></th>
                                            <th class="text-center"><?= __('end_at') ?></th>
                                            <th class="text-center"><?= __('state') ?></th>
                                            <th class="text-center"><?= __('Action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($maintenances) > 0) { ?>
                                            <?php foreach($maintenances as $key => $item) { ?>
                                                <tr>
                                                    <td><?= $item['id'] ?></td>
                                                    <td><?= $this->Html->link(h($item['title']), ['action' => 'view', $item['id']]) ?></td>
                                                    <td><?= nl2br(h($item['body'])) ?></td>
                                                    <td class="text-center"><?= h($item['start_at']) ?></td>
                                                    <td class="text-center"><?= h($item['end_at']) ?></td>
                                                    <td class="text-center"><?= $item['status'] == FORCED_STATUS_ENABLE ? __('Enabled') : __('Disabled') ?></td>
                                                    <td>
                                                        <?php if($item['status'] == FORCED_STATUS_ENABLE) { ?>
                                                            <?= $this->Html->link('<i class="fa fa-fw fa-edit"></i>' . __('Edit'), ['action' => 'edit', $item['id']], ['class' => 'btn btn-info btn-xs', 'escape' => false]) ?>
                                                        <?php } ?>
                                                        <?= $this->Html->link('<i class="fa fa-fw fa-remove"></i>' . __('Delete'), ['action' => 'delete', $item['id']], ['class' => 'btn btn-danger btn-xs', 'escape' => false, 'confirm' => __('Certainly deleted data?')]) ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td class="dataTables_empty" colspan="7"><?= __('Record not found') ?></td>
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