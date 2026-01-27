<?php
/**
 * @var array $sale_type_options
 * @var array $config
 */
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>


<div class="max-w-xl mx-auto mt-8 animate-in slide-up fade-in">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
        <div class="mb-6 border-b border-slate-50 pb-4">
            <h2 class="text-xl font-bold text-slate-800"><?= lang('Reports.report_input') ?></h2>
            <p class="text-sm text-slate-400 mt-1"><?= lang('Common.fill_required_fields') ?></p>
        </div>

        <?= form_open('#', ['id' => 'item_form', 'enctype' => 'multipart/form-data', 'class' => 'space-y-6']) ?>
        
            <div class="flex flex-col gap-2">
                <?= form_label(lang('Reports.date_range'), 'report_date_range_label', ['class' => 'text-sm font-semibold text-slate-700 required']) ?>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <?= form_input(['name' => 'daterangepicker', 'class' => 'flex h-11 w-full rounded-xl border border-slate-200 bg-white ps-10 pe-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all', 'id' => 'daterangepicker']) ?>
                </div>
            </div>

            <?php if (!empty($mode)) { ?>
                <div class="flex flex-col gap-2">
                    <?php if ($mode == 'sale') { ?>
                        <?= form_label(lang('Reports.sale_type'), 'reports_sale_type_label', ['class' => 'text-sm font-semibold text-slate-700 required']) ?>
                        <?= form_dropdown('sale_type', $sale_type_options, 'complete', ['id' => 'input_type', 'class' => 'flex h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all']) ?>
                    <?php } elseif ($mode == 'receiving') { ?>
                        <?= form_label(lang('Reports.receiving_type'), 'reports_receiving_type_label', ['class' => 'text-sm font-semibold text-slate-700 required']) ?>
                        <?= form_dropdown(
                            'receiving_type',
                            [
                                'all'          => lang('Reports.all'),
                                'receiving'    => lang('Reports.receivings'),
                                'returns'      => lang('Reports.returns'),
                                'requisitions' => lang('Reports.requisitions')
                            ],
                            'all',
                            ['id' => 'input_type', 'class' => 'flex h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all']
                        ) ?>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if (isset($discount_type_options)) { ?>
                <div class="flex flex-col gap-2">
                    <?= form_label(lang('Reports.discount_type'), 'reports_discount_type_label', ['class' => 'text-sm font-semibold text-slate-700 required']) ?>
                    <?= form_dropdown('discount_type', $discount_type_options, $config['default_sales_discount_type'], ['id' => 'discount_type_id', 'class' => 'flex h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all']) ?>
                </div>
            <?php } ?>

            <?php if (!empty($stock_locations) && count($stock_locations) > 2) { ?>
                <div class="flex flex-col gap-2">
                    <?= form_label(lang('Reports.stock_location'), 'reports_stock_location_label', ['class' => 'text-sm font-semibold text-slate-700 required']) ?>
                    <?= form_dropdown('stock_location', $stock_locations, 'all', ['id' => 'location_id', 'class' => 'flex h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all']) ?>
                </div>
            <?php } ?>

            <div class="pt-4">
                <?php
                echo form_button(
                    [
                        'name'    => 'generate_report',
                        'id'      => 'generate_report',
                        'type'    => 'button', // Changed to button to prevent premature submit if JS handles it
                        'content' => '<i data-lucide="file-check" class="w-4 h-4 mr-2"></i>' . lang('Common.submit'),
                        'class'   => 'flex items-center justify-center w-full h-11 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-200'
                    ]
                );
                ?>
            </div>
        
        <?= form_close() ?>
    </div>
</div>

<?= view('partial/footer') ?>

<script type="text/javascript">
    $(document).ready(function() {
        <?= view('partial/daterangepicker') ?>

        $("#generate_report").click(function() {
            window.location = [window.location, start_date, end_date, $("#input_type").val() || 0, $("#location_id").val() || 'all', $("#discount_type_id").val() || 0].join("/");
        });
    });
</script>
