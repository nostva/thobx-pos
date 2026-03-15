<?= view('partial/header') ?>

<div class="max-w-2xl mx-auto mt-6 animate-in fade-in slide-up">
    <!-- Header Actions -->
    <div class="flex justify-between items-center mb-6 print_hide">
        <h2 class="text-2xl font-bold text-slate-800"><?= lang('Reports.financial_summary') ?></h2>
        <div class="flex gap-2">
            <button onclick="exportReport('excel')" class="btn btn-default btn-sm flex items-center gap-2">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span>Excel</span>
            </button>
            <button onclick="window.print()" class="btn btn-default btn-sm flex items-center gap-2">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span><?= lang('Common.print') ?></span>
            </button>
        </div>
    </div>

    <!-- Report Card -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">

        <div class="p-8 space-y-8">

            <!-- Revenue Section -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">
                    <?= lang('Reports.revenue') ?>
                </h3>
                <div class="space-y-3">
                    <!-- Total Sales -->
                    <div class="flex justify-between items-center text-sm py-2 border-b border-slate-50">
                        <span class="text-slate-600 font-medium"><?= lang('Reports.sales') ?></span>
                        <span class="text-slate-900 font-bold text-base"><?= to_currency($total_sales) ?></span>
                    </div>
                    <!-- Wholesale Cost -->
                    <div class="flex justify-between items-center text-sm py-2 border-b border-slate-50">
                        <span class="text-slate-600 font-medium"><?= lang('Reports.cost') ?></span>
                        <span class="text-slate-900 font-bold text-base"><?= to_currency($total_cost) ?></span>
                    </div>
                    <!-- Gross Profit -->
                    <div class="flex justify-between items-center text-sm py-2">
                        <span class="text-emerald-700 font-bold"><?= lang('Reports.profit') ?>
                            (<?= lang('Reports.total') ?>)</span>
                        <div class="text-right">
                            <div class="text-emerald-700 font-bold text-lg"><?= to_currency($gross_profit) ?></div>
                            <div class="text-sm text-emerald-500 font-medium"><?= $profit_margin ?>%
                                <?= lang('Reports.margin') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expenses Section -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">
                    <?= lang('Reports.expenses') ?>
                </h3>
                <div class="space-y-3">
                    <?php if (!empty($expenses_data)): ?>
                        <?php foreach ($expenses_data as $expense): ?>
                            <div class="flex justify-between items-center text-sm py-2 border-b border-slate-50">
                                <span class="text-slate-600 font-medium"><?= $expense['category_name'] ?></span>
                                <span class="text-slate-900 font-semibold"><?= to_currency($expense['total_amount']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4 text-slate-400 italic text-sm">
                            <?= lang('Reports.no_expenses_to_display') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Total Expenses -->
                    <div
                        class="flex justify-between items-center text-sm py-3 mt-2 bg-rose-50/50 -mx-2 px-2 rounded-lg">
                        <span class="text-rose-700 font-bold"><?= lang('Reports.total_expenses') ?></span>
                        <span class="text-rose-700 font-bold text-lg"><?= to_currency($total_expenses) ?></span>
                    </div>
                </div>
            </div>

            <!-- Net Profit Section -->
            <div class="pt-6 border-t-2 border-slate-100">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-black text-slate-800"><?= lang('Reports.net_profit') ?></span>
                    <span class="text-2xl font-black <?= $net_profit >= 0 ? 'text-emerald-600' : 'text-rose-600' ?>">
                        <?= to_currency($net_profit) ?>
                    </span>
                </div>
            </div>

        </div>

        <!-- Print Footer -->
        <div class="bg-slate-50 p-4 text-center text-sm text-slate-400 print_hide">
            <?= date('d/m/Y H:i') ?>
        </div>
    </div>
</div>

<!-- Hidden Table for Export -->
<table id="export_table" style="display: none;">
    <thead>
        <tr>
            <th><?= lang('Reports.category') ?? 'Category' ?></th>
            <th><?= lang('Reports.amount') ?? 'Amount' ?></th>
        </tr>
    </thead>
    <tbody>
        <tr><td><?= lang('Reports.sales') ?></td><td><?= to_currency($total_sales) ?></td></tr>
        <tr><td><?= lang('Reports.cost') ?></td><td><?= to_currency($total_cost) ?></td></tr>
        <tr><td><?= lang('Reports.profit') ?></td><td><?= to_currency($gross_profit) ?></td></tr>
        <tr><td><?= lang('Reports.margin') ?></td><td><?= $profit_margin ?>%</td></tr>
        <?php if (!empty($expenses_data)): ?>
            <?php foreach ($expenses_data as $expense): ?>
                <tr><td><?= lang('Reports.expenses') ?>: <?= $expense['category_name'] ?></td><td><?= to_currency($expense['total_amount']) ?></td></tr>
            <?php endforeach; ?>
        <?php endif; ?>
        <tr><td><?= lang('Reports.total_expenses') ?></td><td><?= to_currency($total_expenses) ?></td></tr>
        <tr><td><?= lang('Reports.net_profit') ?></td><td><?= to_currency($net_profit) ?></td></tr>
    </tbody>
</table>

<script type="text/javascript">
    function exportReport(format) {
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
</script>

<?= view('partial/footer') ?>