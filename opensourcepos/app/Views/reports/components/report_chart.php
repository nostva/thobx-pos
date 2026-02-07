<?php
/**
 * Report Chart Component
 * @var string $chart_type - Path to the specific chart view (e.g. 'reports/graphs/pie')
 * @var array $summary_data - Data for summary text
 * @var array $config - Global config
 * ... plus chart-specific variables ($labels_1, $series_data_1, etc.)
 */
?>

<!-- Chart Container -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
    <div class="ct-chart ct-golden-section" id="chart1"></div>

    <!-- Render specific chart initialization script -->
    <?= view($chart_type, array_merge(get_defined_vars(), ['config' => $config])) ?>
</div>

<!-- Summary Stats -->
<div id="chart_report_summary" class="mt-6 bg-slate-50 rounded-xl p-4 border border-slate-100">
    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wide mb-3">
        <?= lang('Reports.summary') ?>
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php foreach ($summary_data as $name => $value): ?>
            <div class="flex flex-col">
                <span class="text-xs text-slate-500 uppercase">
                    <?= lang("Reports.$name") ?>
                </span>
                <span class="text-lg font-bold text-slate-800">
                    <?= to_currency($value) ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
</div>