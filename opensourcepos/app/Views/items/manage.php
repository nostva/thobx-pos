<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $filters
 * @var array $stock_locations
 * @var int $stock_location
 * @var array $config
 */

use App\Models\Employee;
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#generate_barcodes').click(function() {
            window.open(
                'index.php/items/generateBarcodes/' + table_support.selected_ids().join(':'),
                '_blank'
            );
        });

        // When any filter is clicked and the dropdown window is closed
        $('#filters').on('hidden.bs.select', function(e) {
            table_support.refresh();
        });

        // Load the preset daterange picker
        <?= view('partial/daterangepicker') ?>
        // Set the beginning of time as starting date
        $('#daterangepicker').data('daterangepicker').setStartDate("<?= date($config['dateformat'], mktime(0, 0, 0, 01, 01, 2010)) ?>");
        // Update the hidden inputs with the selected dates before submitting the search data
        var start_date = "<?= date('Y-m-d', mktime(0, 0, 0, 01, 01, 2010)) ?>";
        $("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
            table_support.refresh();
        });

        $("#stock_location").change(function() {
            table_support.refresh();
        });

        <?php
        echo view('partial/bootstrap_tables_locale');
        $employee = model(Employee::class);
        ?>

        table_support.init({
            employee_id: <?= $employee->get_logged_in_employee_info()->person_id ?>,
            resource: '<?= esc($controller_name) ?>',
            headers: <?= $table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'items.item_id',
            queryParams: function() {
                return $.extend(arguments[0], {
                    "start_date": start_date,
                    "end_date": end_date,
                    "stock_location": $("#stock_location").val(),
                    "filters": $("#filters").val()
                });
            },
            onLoadSuccess: function(response) {
                $('a.rollover').imgPreview({
                    imgCSS: {
                        width: 200
                    },
                    distanceFromCursor: {
                        top: 10,
                        left: -210
                    }
                })
            }
        });
    });
</script>

<div id="title_bar" class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-slate-800"><?= lang('Module.' . $controller_name) ?></h2>
    <div class="flex gap-2">
        <button class="btn btn-info btn-sm flex items-center gap-2 modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= "$controller_name/csvImport" ?>" title="<?= lang('Items.import_items_csv') ?>">
            <i data-lucide="file-up" class="w-4 h-4"></i>
            <span><?= lang('Common.import_csv') ?></span>
        </button>

        <button class="btn btn-info btn-sm flex items-center gap-2 modal-dlg" data-btn-new="<?= lang('Common.new') ?>" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= "$controller_name/view" ?>" title="<?= lang(ucfirst($controller_name) . '.new') ?>">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span><?= lang(ucfirst($controller_name) . '.new') ?></span>
        </button>
    </div>
</div>

<div id="toolbar" class="flex flex-wrap gap-3 mb-6 items-center">
    <div class="flex gap-2" role="toolbar">
        <button id="delete" class="btn btn-default btn-sm flex items-center gap-2 text-red-600 hover:bg-red-50 hover:border-red-200">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
            <span><?= lang('Common.delete') ?></span>
        </button>
        <button id="bulk_edit" class="btn btn-default btn-sm flex items-center gap-2 modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= "items/bulkEdit" ?>" title="<?= lang('Items.edit_multiple_items') ?>">
            <i data-lucide="edit-3" class="w-4 h-4"></i>
            <span><?= lang('Items.bulk_edit') ?></span>
        </button>
        <button id="generate_barcodes" class="btn btn-default btn-sm flex items-center gap-2" data-href="<?= "$controller_name/generateBarcodes" ?>" title="<?= lang('Items.generate_barcodes') ?>">
            <i data-lucide="barcode" class="w-4 h-4"></i>
            <span><?= lang('Items.generate_barcodes') ?></span>
        </button>
    </div>
    <div class="flex gap-2 items-center">
        <?= form_input(['name' => 'daterangepicker', 'class' => 'form-control input-sm max-w-[200px]', 'id' => 'daterangepicker']) ?>
        <?= form_multiselect('filters[]', $filters, [''], [
            'id'                        => 'filters',
            'class'                     => 'selectpicker show-menu-arrow',
            'data-none-selected-text'   => lang('Common.none_selected_text'),
            'data-selected-text-format' => 'count > 1',
            'data-style'                => 'btn-default btn-sm',
            'data-width'                => 'fit'
        ]) ?>
        <?php
        if (count($stock_locations) > 1) {
            echo form_dropdown(
                'stock_location',
                $stock_locations,
                $stock_location,
                [
                    'id'         => 'stock_location',
                    'class'      => 'selectpicker show-menu-arrow',
                    'data-style' => 'btn-default btn-sm',
                    'data-width' => 'fit'
                ]
            );
        }
        ?>
    </div>
</div>

<div id="table_holder">
    <table id="table"></table>
</div>

<?= view('partial/footer') ?>
