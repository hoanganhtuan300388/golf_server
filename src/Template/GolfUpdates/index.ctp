<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('Notice of change in par'), 'title' => $title, 'icon' => 'fa fa-exchange']) ?>
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
                                <?= $this->Form->label('account_id', __('keyword'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.account_id', ['class' => 'form-control input-sm', 'maxlength' => 255]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('golf_field_id', __('update_golf_field_id'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.golf_field_id', ['class' => 'form-control input-sm']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= $this->Form->label('notice_after_date', __('update') . '～', ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.notice_after_date', ['class' => 'form-control input-sm', 'id' => 'par_notice_after_date']); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('notice_before_date', '～' . __('update'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.notice_before_date', ['class' => 'form-control input-sm', 'id' => 'par_notice_before_date']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= $this->Form->label('status', __('status'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->select('search.status', [0 => __('Not compatible'), 1 => __('Corresponding')], ['class' => 'form-control input-sm', 'empty' => '--']) ?>
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
                                            <th class="text-center"><?= __('account_id') ?></th>
                                            <th class="text-center"><?= __('update_golf_field_id') ?></th>
                                            <th class="text-center"><?= __('update_course_id') ?></th>
                                            <th class="text-center"><?= __('update_hole_id') ?></th>
                                            <th class="text-center"><?= __('update_at') ?></th>
                                            <th class="text-center"><?= __('par_count') ?></th>
                                            <th class="text-center"><?= __('new_par_num') ?></th>
                                            <th class="text-center"><?= __('status') ?></th>
                                            <th class="text-center" width="160"><?= __('Action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($par_notices) > 0) { ?>
                                            <?php foreach($par_notices as $key => $item) { ?>
                                                <tr>
                                                    <td><?= h($item['id']) ?></td>
                                                    <td class="text-right"><?= $this->Html->link(__($item['update_by']), ['controller' => 'users', 'action' => 'show', $item['update_by']]) ?></td>
                                                    <td class="text-right"><?= $this->Html->link(__($item['update_golf_field_id']), ['controller' => 'golfs', 'action' => 'edit', '?' => ['field_id' => $item['update_golf_field_id']]]) ?></td>
                                                    <td class="text-right"><?= h($item['update_course_id']) ?></td>
                                                    <td class="text-right"><?= h($item['update_hole_id']) ?></td>
                                                    <td class="text-center"><?= h($item['update_at']) ?></td>
                                                    <td class="text-right"><?= h($item['old_par_num']) ?></td>
                                                    <td class="text-right"><?= h($item['new_par_num']) ?></td>
                                                    <td class=""><?php if(isset($labelGolf['status'][$item['status']])){ echo $labelGolf['status'][$item['status']]; } ?></td>
                                                    <td class="text-center">
                                                        <?php if($item['status'] == 1 || $item['status'] != 2){?>
                                                            <?= $this->Html->link(__('Approval'), ['action' => 'edit',$item['id'],2], ['class' => 'btn btn-danger btn-xs', 'escape' => false]) ?>
                                                        <?php } ?>
                                                        <?php if($item['status'] == 1 || $item['status'] != 3){?>
                                                            <?= $this->Html->link(__('On hold'), ['action' => 'edit',$item['id'],3], ['class' => 'btn btn-info btn-xs', 'escape' => false]) ?>
                                                        <?php } ?>
                                                        <?php if($item['status'] == 1 || $item['status'] != 4){?>
                                                            <?= $this->Html->link(__('Dismissal'), ['action' => 'edit',$item['id'],4], ['class' => 'btn btn-primary btn-xs', 'escape' => false]) ?>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td class="dataTables_empty" colspan="10"><?= __('Record not found') ?></td>
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