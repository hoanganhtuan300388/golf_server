<?php $this->assign('title', $title);?>
<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('Golf course management'), 'title' => $title, 'icon' => 'fa fa-table']) ?>
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
                <input type="hidden" id="url_pre" name="" value="<?php echo $url_pre; ?>" >
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $this->Form->label('field_name', __('field_name'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('search.field_name', ['class' => 'form-control input-sm', 'maxlength' => 255]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('service_status', __('service_status'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->select('search.service_status', $labelGolf['service_status'], ['class' => 'form-control input-sm', 'maxlength' => 255, 'empty' => '--']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $this->Form->label('nation', __('nation'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->select('search.nation', $list_nation, ['class' => 'form-control input-sm', 'empty' => '---', 'id' => 'nation']) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('prefecture', __('prefecture'), ['class' => 'col-sm-4 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->select('search.prefecture', $list_pre, ['class' => 'form-control input-sm', 'empty' => '---', 'id' => 'prefecture']) ?>
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
                                            <th class="text-center"><?= $this->Paginator->sort('field_id', 'ID') ?></th>
                                            <th class="text-center"><?= __('field_name') ?></th>
                                            <th class="text-center"><?= __('address') ?></th>
                                            <th class="text-center"><?= __('course_count') ?></th>
                                            <th class="text-center"><?= __('hole_count') ?></th>
                                            <th class="text-center"><?= __('par_count') ?></th>
                                            <th class="text-center"><?= __('service_status') ?></th>
                                            <th class="text-center"><?= __('Action') ?></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($data) > 0) { ?>
                                            <?php foreach($data as $key => $item) { ?>
                                                <tr>
                                                    <td><?= h($item['field_id']) ?></td>
                                                    <td><?= h($item['field_name']) ?></td>
                                                    <td><?= h($item['address']) ?></td>
                                                    <td class="text-right"><?= count($item['courses']) ?></td>
                                                    <?php
                                                        $hole = 0; $par = 0;
                                                        foreach($item['courses'] as $course) {
                                                            $hole += $course['hole_num'];
                                                            $par += $course['par_num'];
                                                        }
                                                    ?>
                                                    <td class="text-right"><?= $hole ?></td>
                                                    <td class="text-right"><?= $par ?></td>
                                                    <td><?= $labelGolf['service_status'][$item['service_status']] ?></td>
                                                    <td class="text-center">
                                                        <?= $this->Html->link('<i class="fa fa-fw fa-edit"></i>' . __('編集'), ['action' => 'edit', '?' => ['field_id' => $item['field_id']]], ['class' => 'btn btn-info btn-xs', 'escape' => false]) ?>
                                                        <?= $this->Html->link('<i class="fa fa-fw fa-remove"></i>' . __('Delete'), ['action' => 'delete', '?' => ['field_id' => $item['field_id']]], ['class' => 'btn btn-danger btn-xs', 'escape' => false, 'confirm' => __('Certainly deleted data?')]) ?>
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