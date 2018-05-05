<?= $this->Form->create(null, ['url' => ['action' => 'search'], 'class' => 'form-horizontal', 'id' => 'frm-display-list']) ?>
    表示件数 <?= $this->Form->select('display.limit', [10 => 10, 15 => 15, 20 => 20, 30 => 30, 50 => 50, 100 => 100], ['class' => 'input-sm', 'onchange' => "$('#frm-display-list').submit()"]) ?> 件
<?= $this->Form->end() ?>