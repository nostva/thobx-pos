<?= view('partial/header') ?>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<div class="max-w-6xl mx-auto mt-6 animate-in fade-in slide-up">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 print_hide">
        <h2 class="text-2xl font-bold text-slate-800">
            <?= lang('Reports.financial_summary') ?>
        </h2>
        
        <!-- Export & Print Actions -->
        <div class="flex flex-wrap gap-2">
            <button onclick="exportReport('excel')" class="btn btn-default btn-sm flex items-center gap-2">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Excel</span>
            </button>
            <button onclick="window.print()" class="btn btn-default btn-sm flex items-center gap-2">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span><?= lang('Common.print') ?></span>
            </button>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6 print_hide">
        <?= form_open('#', ['id' => 'report_form', 'class' => 'space-y-4']) ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Date Range Picker -->
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
                        'value' => $default_date_display ?? ''
                    ]) ?>
                </div>
            </div>

            <!-- Sale Type Dropdown -->
            <div class="flex flex-col gap-2">
                <?= form_label(lang('Reports.sale_type'), 'reports_sale_type_label', ['class' => 'text-sm font-semibold text-slate-700 required']) ?>
                <?= form_dropdown(
                    'sale_type',
                    $sale_type_options,
                    $default_sale_type ?? 'complete',
                    ['id' => 'sale_type', 'class' => 'flex h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all']
                ) ?>
            </div>
        </div>

        <!-- Generate Button -->
        <div class="pt-2">
            <?= form_button([
                'name' => 'generate_report',
                'id' => 'generate_report',
                'type' => 'button',
                'content' => '<i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>' . lang('Reports.generate_report'),
                'class' => 'flex items-center justify-center h-11 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-200 px-6 gap-2'
            ]) ?>
        </div>
        <?= form_close() ?>
    </div>

    <!-- Results Container -->
    <div id="results_container">
        <!-- Initial results will be loaded here -->
        <?php if (isset($show_initial_results) && $show_initial_results): ?>
            <?= view('reports/financial_summary_results', [
                'title' => $title,
                'subtitle' => $subtitle,
                'total_sales' => $total_sales,
                'total_cost' => $total_cost,
                'gross_profit' => $gross_profit,
                'profit_margin' => $profit_margin,
                'total_expenses' => $total_expenses,
                'expenses_data' => $expenses_data,
                'net_profit' => $net_profit
            ]) ?>
        <?php endif; ?>
    </div>
</div>

<?= view('partial/footer') ?>

<script type="text/javascript">
    function exportReport(format) {
        if ($('#export_table').length === 0) {
            console.error("No data available to export.");
            return;
        }
        let fileName = "financial_summary_" + new Date().toISOString().slice(0, 10);
        let $table = $('#export_table');
        $table.show();
        $table.tableExport({
            type: format,
            fileName: fileName,
            escape: false,
            exportHiddenCells: true
        });
        $table.hide();
    }

    $(document).ready(function () {
        // Initialize daterangepicker
        <?= view('partial/daterangepicker') ?>

        // Handle generate report button click
        $("#generate_report").click(function (e) {
            e.preventDefault();

            // Show loading state
            $("#results_container").html(`
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-12 text-center">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-500 mb-4"></div>
                    <p class="text-slate-600"><?= lang('Reports.loading_report') ?></p>
                </div>
            `);

            // Disable button during load
            $(this).prop('disabled', true).addClass('opacity-50');

            // Build URL with parameters
            var url = "<?= site_url('reports/financial_summary') ?>/" +
                encodeURIComponent(start_date) + "/" +
                encodeURIComponent(end_date) + "/" +
                ($("#sale_type").val() || 'complete');

            // Fetch results via AJAX
            $.ajax({
                url: url,
                type: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function (response) {
                    $("#results_container").html(response);
                    // Re-initialize Lucide icons for new content
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function (xhr, status, error) {
                    $("#results_container").html(`
                        <div class="bg-white rounded-xl shadow-lg border border-red-200 p-8">
                            <div class="flex items-center gap-3 text-red-600 mb-2">
                                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                                <h3 class="font-bold"><?= lang('Reports.error_loading_report') ?></h3>
                            </div>
                            <p class="text-slate-600"><?= lang('Reports.please_try_again') ?></p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                complete: function () {
                    // Re-enable button
                    $("#generate_report").prop('disabled', false).removeClass('opacity-50');
                }
            });
        });

        // Load initial results if not already shown
        <?php if (!isset($show_initial_results) || !$show_initial_results): ?>
            $("#generate_report").trigger('click');
        <?php endif; ?>
    });
</script>