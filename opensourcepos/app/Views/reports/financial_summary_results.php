 <style>
    @media print {
        .print_only { display: block !important; }
    }
    @media screen {
        .print_only { display: none !important; }
    }
</style>

<!-- Print Header -->
<div class="print_only mb-8">
    <div class="text-center">
        <h2 class="text-sm font-bold mb-2"><?= lang('Reports.financial_summary') ?></h2>
        <div class="space-y-1">
            <p class="text-sm"><?= lang('Reports.date_range') ?>: <?= esc($subtitle) ?></p>
            <p class="text-sm"><?= lang('Reports.sale_type') ?>: <?= isset($sale_type) ? lang('Reports.' . $sale_type) : lang('Reports.complete') ?></p>
            <p class="text-sm"><?= lang('Reports.date') ?>: <?= date('d/m/Y H:i') ?></p>
        </div>
    </div>
</div>

<!-- Report Card -->
<div class="financial-summary-card bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">

    <div class="financial-summary-content p-8 space-y-8">

        <!-- Revenue Section -->
        <div>
            <h3 class="text-sm font-bold uppercase mb-4">
                <?= lang('Reports.revenue') ?>
            </h3>
            <div class="space-y-3">
                <!-- Total Sales -->
                <div class="financial-summary-row flex justify-between items-center py-2 border-b border-slate-50">
                    <span class="text-sm">
                        <?= lang('Reports.sales') ?>
                    </span>
                    <span class="text-sm font-bold">
                        <?= to_currency($total_sales) ?>
                    </span>
                </div>
                <!-- Wholesale Cost -->
                <div class="financial-summary-row flex justify-between items-center py-2 border-b border-slate-50">
                    <span class="text-sm">
                        <?= lang('Reports.cost') ?>
                    </span>
                    <span class="text-sm font-bold">
                        <?= to_currency($total_cost) ?>
                    </span>
                </div>
                <!-- Gross Profit -->
                <div class="financial-summary-row flex justify-between items-center py-2">
                    <span class="text-sm font-bold">
                        <?= lang('Reports.profit') ?> (
                        <?= lang('Reports.total') ?>)
                    </span>
                    <div class="text-end">
                        <div class="text-sm font-bold">
                            <?= to_currency($gross_profit) ?>
                        </div>
                        <div class="text-xs">
                            <?= $profit_margin ?>%
                            <?= lang('Reports.margin') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expenses Section -->
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider mb-4">
                <?= lang('Reports.expenses') ?>
            </h3>
            <div class="space-y-3">
                <?php if (!empty($expenses_data)): ?>
                    <?php foreach ($expenses_data as $expense): ?>
                        <div class="financial-summary-row flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="text-sm">
                                <?= $expense['category_name'] ?>
                            </span>
                            <span class="text-sm font-bold">
                                <?= to_currency($expense['total_amount']) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-xs text-center py-4 italic">
                        <?= lang('Reports.no_expenses_to_display') ?>
                    </div>
                <?php endif; ?>

                <!-- Total Expenses -->
                <div class="financial-summary-expenses-total flex justify-between items-center py-3 mt-2">
                    <span class="text-sm font-bold">
                        <?= lang('Reports.total_expenses') ?>
                    </span>
                    <span class="text-sm font-bold">
                        <?= to_currency($total_expenses) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Net Profit Section -->
        <div class="financial-summary-net-profit pt-6 border-t-2 border-slate-100">
            <div class="flex justify-between items-center">
                <span class="text-sm font-bold">
                    <?= lang('Reports.net_profit') ?>
                </span>
                <span class="text-sm font-bold <?= $net_profit >= 0 ? 'text-emerald-600' : 'text-rose-600' ?>">
                    <?= to_currency($net_profit) ?>
                </span>
            </div>
        </div>

    </div>

    <!-- Print Footer -->
    <div class="bg-slate-50 p-4 text-center text-xs text-slate-400 border-t border-slate-100 print_hide">
        <?= date('d/m/Y H:i') ?>
    </div>
</div>

<table id="export_table" style="display: none;">
    <tbody>
        <tr><td><b><?= lang('Reports.revenue') ?></b></td><td></td></tr>
        <tr><td><?= lang('Reports.sales') ?></td><td><?= to_currency($total_sales) ?></td></tr>
        <tr><td><?= lang('Reports.cost') ?></td><td><?= to_currency($total_cost) ?></td></tr>
        
        <tr><td></td><td></td></tr>

        <tr><td><b><?= lang('Reports.profit') ?> (<?= lang('Reports.total') ?>)</b></td><td><b><?= to_currency($gross_profit) ?></b></td></tr>
        <tr><td><?= lang('Reports.margin') ?></td><td><?= $profit_margin ?></td></tr>
        
        <tr><td></td><td></td></tr>

        <tr><td><b><?= lang('Reports.expenses') ?></b></td><td></td></tr>
        <?php if (!empty($expenses_data)): ?>
            <?php foreach ($expenses_data as $expense): ?>
                <tr><td><?= $expense['category_name'] ?></td><td><?= to_currency($expense['total_amount']) ?></td></tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td><?= lang('Reports.no_expenses_to_display') ?></td><td></td></tr>
        <?php endif; ?>
        <tr><td><b><?= lang('Reports.total_expenses') ?></b></td><td><b><?= to_currency($total_expenses) ?></b></td></tr>
        
        <tr><td></td><td></td></tr>

        <tr><td><b><?= lang('Reports.net_profit') ?></b></td><td><b><?= to_currency($net_profit) ?></b></td></tr>
    </tbody>
</table>