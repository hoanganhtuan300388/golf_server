<div class="row">
    <div class="col-sm-5">
        <div class="dataTables_info">
            <?= $this->Paginator->counter('{{count}}の項目の{{start}}に{{end}}のを示す。') ?>
        </div>
    </div>
    <div class="col-sm-7">
        <div class="dataTables_paginate paging_simple_numbers">
            <ul class="pagination">
                <?= $this->Paginator->prev(__('Prev')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('Next')) ?>
            </ul>
        </div>
    </div>
</div>