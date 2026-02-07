<?php
/**
 * Report Table Component - Reusable table display
 * @var string $title
 * @var string $subtitle
 * @var array $headers
 * @var array $data
 * @var array $summary_data
 * @var array $config
 */
?>

<!-- Table Card -->
<div class="mb-6">
    <div id="table_holder">
        <table id="report_table"></table>
    </div>
</div>

<?php if (!empty($summary_data)): ?>
    <!-- Summary Stats Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-6 flex items-center gap-2">
            <i data-lucide="sigma" class="w-4 h-4 text-emerald-500"></i>
            <?= lang('Reports.summary') ?>
        </h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($summary_data as $name => $value): ?>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100/50">
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wide mb-1">
                        <?= lang("Reports.$name") ?>
                    </div>
                    <div class="text-xl font-bold text-slate-900">
                        <?= $name == "total_quantity" ? $value : to_currency($value) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Print Footer -->
<div class="mt-8 text-center text-sm text-slate-400 hidden print:block">
    <?= date('d/m/Y H:i') ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        <?= view('partial/bootstrap_tables_locale') ?>

        $('#report_table')
            .addClass("table-striped")
            .addClass("table-bordered")
            .bootstrapTable({
                columns: <?= transform_headers(esc($headers), true, false) ?>,
                stickyHeader: true,
                pageSize: <?= $config['lines_per_page'] ?>,
                sortable: true,
                showExport: true,
                exportDataType: 'all',
                exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
                pagination: true,
                showColumns: true,
                data: <?= json_encode($data) ?>,
                iconSize: 'sm',
                paginationVAlign: 'bottom',
                escape: true,
                search: true
            });

        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>