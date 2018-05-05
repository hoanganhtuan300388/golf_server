<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('Help management'), 'title' => $title, 'icon' => 'fa fa-question-circle-o']) ?>
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
                                <?= $this->Form->label('keyword', __('keyword'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.keyword', ['class' => 'form-control input-sm', 'maxlength' => 255]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('category', __('category'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->select('search.category', $list_category, ['class' => 'form-control input-sm', 'empty' => '--']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $this->Form->label('public_flg', __('status'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->select('search.public_flg', $label['public_flg'], ['class' => 'form-control input-sm', 'empty' => '--']) ?>
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
                                            <th class="text-center" width="50"><?= $this->Paginator->sort('id', 'ID') ?></th>
                                            <th class="text-center"><?= __('question') ?></th>
                                            <th class="text-center" width="80"><?= $this->Paginator->sort('order_by', __('order_by')) ?></th>
                                            <th class="text-center" width="140"><?= __('type') ?></th>
                                            <th class="text-center" width="100"><?= __('date_of_create') ?></th>
                                            <th class="text-center" width="80"><?= __('state') ?></th>
                                            <th class="text-center" width="120"><?= __('Action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($data) > 0) { ?>
                                            <?php foreach($data as $key => $item) { ?>
                                                <tr>
                                                    <td><?= h($item['id']) ?></td>
                                                    <td><?= h($item['title']) ?></td>
                                                    <td><?= !empty($item['order_by']) ? $item['order_by'] : '-' ?></td>
                                                    <td><?= $list_category[$item['category_id']] ?></td>
                                                    <td class="text-center"><?= h($item['created']) ?></td>
                                                    <td class="text-center"><?= $label['public_flg'][$item['public_flg']] ?></td>
                                                    <td class="text-center">
                                                        <?= $this->Html->link('<i class="fa fa-fw fa-edit"></i>' . __('Edit'), ['action' => 'edit', $item['id']], ['class' => 'btn btn-info btn-xs', 'escape' => false]) ?>
                                                        <?= $this->Html->link('<i class="fa fa-fw fa-remove"></i>' . __('Delete'), ['action' => 'delete', $item['id']], ['class' => 'btn btn-danger btn-xs', 'escape' => false, 'confirm' => __('Certainly deleted data?')]) ?>
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