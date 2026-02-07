<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $filters
 * @var array $config
 */
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    $(document).ready(function () {
        // When any filter is clicked and the dropdown window is closed
        $('#filters').on('hidden.bs.select', function (e) {
            table_support.refresh();
        });

        // Load the preset datarange picker
        <?= view('partial/daterangepicker') ?>

        $("#daterangepicker").on('apply.daterangepicker', function (ev, picker) {
            table_support.refresh();
        });

        <?= view('partial/bootstrap_tables_locale') ?>

        table_support.init({
            resource: '<?= esc($controller_name) ?>',
            headers: <?= $table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'expense_id',
            onLoadSuccess: function (response) {
                if ($("#table tbody tr").length > 1) {
                    $("#payment_summary").html(response.payment_summary);
                    $("#table tbody tr:last td:first").html("");
                    $("#table tbody tr:last").css('font-weight', 'bold');
                }
            },
            queryParams: function () {
                return $.extend(arguments[0], {
                    "start_date": start_date,
                    "end_date": end_date,
                    "filters": $("#filters").val()
                });
            }
        });
    });
</script>

<?= view('partial/print_receipt', ['print_after_sale' => false, 'selected_printer' => 'takings_printer']) ?>

<div id="title_bar" class="flex justify-between items-center mb-6 print:hidden">
    <h2 class="text-2xl font-bold text-slate-800"><?= lang('Module.' . $controller_name) ?></h2>
    <div class="flex gap-2">
        <button id="manage_categories" class="btn btn-default btn-sm flex items-center gap-2"
            title="<?= lang('Expenses_categories.manage') ?>">
            <i data-lucide="tag" class="w-4 h-4"></i>
            <span><?= lang('Expenses_categories.manage') ?></span>
        </button>
        <button onclick="javascript:printdoc()" class="btn btn-default btn-sm flex items-center gap-2">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span><?= lang('Common.print') ?></span>
        </button>
        <button class="btn btn-info btn-sm flex items-center gap-2 modal-dlg"
            data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= "$controller_name/view" ?>"
            title="<?= lang(ucfirst($controller_name) . '.new') ?>">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span><?= lang(ucfirst($controller_name) . '.new') ?></span>
        </button>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#manage_categories').click(function (e) {
            e.preventDefault();

            // Show loading spinner first
            var $dialog = BootstrapDialog.show({
                title: '<?= lang('Expenses_categories.manage') ?>',
                message: '<div class="flex justify-center items-center p-10"><i data-lucide="loader-2" class="w-10 h-10 animate-spin text-slate-400"></i><span class="ml-3 text-slate-500 font-medium"><?= lang('Common.loading') ?>...</span></div>',
                size: BootstrapDialog.SIZE_NORMAL, // Smaller than wide
                cssClass: 'modal-md', // Medium size
                closeByBackdrop: false,
                closeByKeyboard: false,
                onshown: function (dialog) {
                    // Load the content
                    $.get('<?= site_url('expenses_categories/manageModal') ?>', function (html) {
                        dialog.getModalBody().html(html);
                        // Re-run lucide in case the loaded content has icons
                        if (window.lucide) window.lucide.createIcons({ root: dialog.getModalBody()[0] });
                    });
                },
                buttons: [{
                    label: '<?= lang('Common.close') ?>',
                    cssClass: 'btn-default',
                    action: function (dialog) {
                        dialog.close();
                        table_support.refresh();
                    }
                }]
            });
        });
    });
</script>

<div id="toolbar" class="flex flex-wrap gap-3 mb-6 items-center print:hidden">
    <div class="flex gap-2" role="toolbar">
        <button id="delete"
            class="btn btn-default btn-sm flex items-center gap-2 text-red-600 hover:bg-red-50 hover:border-red-200">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
            <span><?= lang('Common.delete') ?></span>
        </button>
    </div>
    <div class="flex gap-2 items-center">
        <?= form_input(['name' => 'daterangepicker', 'class' => 'form-control input-sm max-w-[200px]', 'id' => 'daterangepicker']) ?>
        <?= form_multiselect('filters[]', esc($filters), [''], [
            'id' => 'filters',
            'data-none-selected-text' => lang('Common.none_selected_text'),
            'class' => 'selectpicker show-menu-arrow',
            'data-selected-text-format' => 'count > 1',
            'data-style' => 'btn-default btn-sm',
            'data-width' => 'fit'
        ]) ?>
    </div>
</div>

<div id="table_holder">
    <table id="table"></table>
</div>

<div id="payment_summary"></div>

<?= view('partial/footer') ?>