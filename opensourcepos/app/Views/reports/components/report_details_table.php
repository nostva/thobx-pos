<?php
/**
 * Report Details Table Component
 * @var array $overall_summary_data
 * @var array $details_data
 * @var array $headers
 * @var array $summary_data
 * @var array $config
 * @var string $editable - Optional, for detailed sales/receivings identifying the edit endpoint
 */
?>

<!-- Table Card -->
<div id="table_holder">
    <table id="table" class="w-full"></table>
</div>

<!-- Summary Stats Card -->
<div id="report_summary" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-6">
    <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-6 flex items-center gap-2">
        <i data-lucide="sigma" class="w-4 h-4 text-emerald-500"></i>
        <?= lang('Reports.summary') ?>
    </h4>
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
        <?php foreach ($overall_summary_data as $name => $value): ?>
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100/50">
                <div class="text-sm font-medium text-slate-500 uppercase tracking-wide mb-1">
                    <?= lang("Reports.$name") ?>
                </div>
                <div class="text-xl font-bold text-slate-800">
                    <?= to_currency($value) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        <?= view('partial/bootstrap_tables_locale') ?>

        var details_data = <?= json_encode(esc($details_data)) ?>;
        <?php if (isset($details_data_rewards) && $config['customer_reward_enable'] && !empty($details_data_rewards)) { ?>
            var details_data_rewards = <?= json_encode(esc($details_data_rewards)) ?>;
        <?php } ?>

        var init_dialog = function () {
            <?php if (isset($editable)) { ?>
                table_support.submit_handler('<?= esc(site_url("reports/get_detailed_$editable" . '_row')) ?>');
                dialog_support.init("a.modal-dlg");
            <?php } ?>
        };

        $('#table')
            .addClass("table-striped")
            .addClass("table-bordered")
            .bootstrapTable({
                columns: <?= transform_headers(esc($headers['summary']), true) ?>,
                stickyHeader: true,
                pageSize: <?= $config['lines_per_page'] ?>,
                pagination: true,
                sortable: true,
                showColumns: true,
                uniqueId: 'id',
                showExport: true,
                exportDataType: 'all',
                exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
                data: <?= json_encode($summary_data) ?>,
                iconSize: 'sm',
                paginationVAlign: 'bottom',
                detailView: true,
                escape: true,
                search: true,
                onPageChange: init_dialog,
                onPostBody: function () {
                    dialog_support.init("a.modal-dlg");
                },
                onExpandRow: function (index, row, $detail) {
                    $detail.html('<table></table>').find("table").bootstrapTable({
                        columns: <?= transform_headers_readonly(esc($headers['details'])) ?>,
                        data: details_data[(!isNaN(row.id) && row.id) || $(row[0] || row.id).text().replace(/(POS|RECV)\s*/g, '')]
                    });

                    <?php if (isset($details_data_rewards) && $config['customer_reward_enable'] && !empty($details_data_rewards)) { ?>
                        $detail.append('<table></table>').find("table").bootstrapTable({
                            columns: <?= transform_headers_readonly(esc($headers['details_rewards'])) ?>,
                            data: details_data_rewards[(!isNaN(row.id) && row.id) || $(row[0] || row.id).text().replace(/(POS|RECV)\s*/g, '')]
                        });
                    <?php } ?>
                }
            });

        init_dialog();
    });
</script>