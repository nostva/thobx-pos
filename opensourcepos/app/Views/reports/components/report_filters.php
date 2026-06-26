<?php
/**
 * Report Filters Component - Reusable filter form
 * @var string $report_name - Used for form ID
 * @var array $sale_type_options
 * @var string $default_sale_type
 * @var string $default_date_display
 * @var array|null $stock_locations - Optional stock location dropdown
 */
?>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
    <?= form_open('#', ['id' => 'report_filter_form', 'class' => 'space-y-4']) ?>
    <div
        class="grid grid-cols-1 md:grid-cols-<?= isset($stock_locations) && count($stock_locations) > 2 ? '3' : '2' ?> gap-4">
        <!-- Date Range Picker -->
        <?php if (!isset($hide_date_range) || !$hide_date_range): ?>
            <div class="flex flex-col gap-2">
                <?= form_label(lang('Reports.date_range'), 'report_date_range_label', ['class' => 'text-sm font-semibold text-slate-700 required']) ?>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <?= form_input([
                        'name' => 'daterangepicker',
                        'class' => 'flex h-11 w-full rounded-xl border border-slate-200 bg-white ps-10 pe-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all',
                        'id' => 'daterangepicker',
                    ]) ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Sale Type Dropdown (if applicable) -->
        <?php if (isset($sale_type_options) && !empty($sale_type_options)): ?>
            <div class="flex flex-col gap-2">
                <?= form_label(lang('Reports.sale_type'), 'reports_sale_type_label', ['class' => 'text-sm font-semibold text-slate-700 required']) ?>
                <?= form_dropdown(
                    'sale_type',
                    $sale_type_options,
                    $default_sale_type ?? 'complete',
                    ['id' => 'sale_type', 'class' => 'flex h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all']
                ) ?>
            </div>
        <?php endif; ?>

        <!-- Discount Type Dropdown (if applicable) -->
        <?php if (isset($discount_type_options) && !empty($discount_type_options)): ?>
            <div class="flex flex-col gap-2">
                <?= form_label(lang('Reports.discount_type'), 'reports_discount_type_label', ['class' => 'text-sm font-semibold text-slate-700']) ?>
                <?= form_dropdown(
                    'discount_type',
                    $discount_type_options,
                    $default_discount_type ?? '0',
                    ['id' => 'discount_type', 'class' => 'flex h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all']
                ) ?>
            </div>
        <?php endif; ?>

        <!-- Receiving Type Dropdown (if applicable) -->
        <?php if (isset($receiving_type_options) && !empty($receiving_type_options)): ?>
            <div class="flex flex-col gap-2">
                <?= form_label(lang('Reports.receiving_type'), 'reports_receiving_type_label', ['class' => 'text-sm font-semibold text-slate-700']) ?>
                <?= form_dropdown(
                    'receiving_type',
                    $receiving_type_options,
                    $default_receiving_type ?? 'all',
                    ['id' => 'receiving_type', 'class' => 'flex h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all']
                ) ?>
            </div>
        <?php endif; ?>

        <!-- Specific Input Dropdown (if applicable) -->
        <?php if (isset($specific_input_data) && !empty($specific_input_data)): ?>
            <div class="flex flex-col gap-2">
                <?= form_label($specific_input_name ?? '', 'specific_input_data_label', ['class' => 'text-sm font-semibold text-slate-700']) ?>
                <?= form_dropdown(
                    'specific_input_data',
                    $specific_input_data,
                    $default_specific_input_data ?? '',
                    ['id' => 'specific_input_data', 'class' => 'flex h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all']
                ) ?>
            </div>
        <?php endif; ?>

        <!-- Stock Location Dropdown (if applicable) -->
        <?php if (isset($stock_locations) && count($stock_locations) > 2): ?>
            <div class="flex flex-col gap-2">
                <?= form_label(lang('Reports.stock_location'), 'reports_stock_location_label', ['class' => 'text-sm font-semibold text-slate-700 required']) ?>
                <?= form_dropdown(
                    'stock_location',
                    $stock_locations,
                    'all',
                    ['id' => 'location_id', 'class' => 'flex h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all']
                ) ?>
            </div>
        <?php endif; ?>
    </div>



    <!-- Payment Type Dropdown (if applicable) -->
    <?php if (isset($payment_type_options) && !empty($payment_type_options)): ?>
        <div class="flex flex-col gap-2">
            <?= form_label(lang('Reports.payment_type'), 'reports_payment_type_label', ['class' => 'text-sm font-semibold text-slate-700']) ?>
            <?= form_dropdown(
                'payment_type',
                $payment_type_options,
                $default_payment_type ?? 'all',
                ['id' => 'payment_type', 'class' => 'flex h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all']
            ) ?>
        </div>
    <?php endif; ?>

    <!-- Item Count Dropdown (if applicable) -->
    <?php if (isset($item_count_options) && !empty($item_count_options)): ?>
        <div class="flex flex-col gap-2">
            <?= form_label(lang('Reports.item_count'), 'reports_item_count_label', ['class' => 'text-sm font-semibold text-slate-700']) ?>
            <?= form_dropdown(
                'item_count',
                $item_count_options,
                $default_item_count ?? 'all',
                ['id' => 'item_count', 'class' => 'flex h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all']
            ) ?>
        </div>
    <?php endif; ?>

    <!-- Generate Button -->
    <div class="pt-2">
        <?= form_button([
            'name' => 'generate_report',
            'id' => 'generate_report_btn',
            'type' => 'submit',
            'content' => '<i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>' . lang('Reports.generate_report'),
            'class' => 'flex items-center justify-center h-11 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-200 px-6 gap-2'
        ]) ?>
    </div>
    <?= form_close() ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        // Initialize daterangepicker
        <?= view('partial/daterangepicker') ?>
    });
</script>