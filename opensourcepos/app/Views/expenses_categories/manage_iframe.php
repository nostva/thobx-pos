<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $config
 */
?>

<?= view('partial/header_popup') ?>

<script type="text/javascript">
    $(document).ready(function () {
        <?= view('partial/bootstrap_tables_locale') ?>

            table_support.init({
                resource: '<?= esc($controller_name) ?>',
                headers: <?= $table_headers ?>,
                pageSize: <?= $config['lines_per_page'] ?>,
                uniqueId: 'expense_category_id',
            });

        // When any filter is clicked and the dropdown window is closed
        $('#filters').on('hidden.bs.select', function (e) {
            table_support.refresh();
        });
    });
</script>

<div id="title_bar" class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-slate-800">
        <?= lang('Module.' . $controller_name) ?>
    </h2>
    <div class="flex gap-2">
        <button class="btn btn-info btn-sm flex items-center gap-2 modal-dlg"
            data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= esc("$controller_name/view") ?>" title="
            <?= lang(ucfirst($controller_name) . '.new') ?>">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>
                <?= lang(ucfirst($controller_name) . '.new') ?>
            </span>
        </button>
    </div>
</div>

<div id="toolbar">
    <div class="pull-left form-inline" role="toolbar">
        <button id="delete"
            class="btn btn-default btn-sm flex items-center gap-2 text-red-600 hover:bg-red-50 hover:border-red-200">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
            <span>
                <?= lang('Common.delete') ?>
            </span>
        </button>
    </div>
</div>

<div id="table_holder">
    <table id="table"></table>
</div>

<?php
// Inline footer functionality since we stripped the main footer
?>
<script>
    if (window.lucide) {
        lucide.createIcons();
    }
</script>
</body>

</html>