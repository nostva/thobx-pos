<!-- Report Card -->
<div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden print:shadow-none print:border-none">

    <div class="p-8 space-y-8">

        <!-- Revenue Section -->
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">
                <?= lang('Reports.revenue') ?>
            </h3>
            <div class="space-y-3">
                <!-- Total Sales -->
                <div class="flex justify-between items-center text-sm py-2 border-b border-slate-50">
                    <span class="text-slate-600 font-medium">
                        <?= lang('Reports.sales') ?>
                    </span>
                    <span class="text-slate-900 font-bold text-base">
                        <?= to_currency($total_sales) ?>
                    </span>
                </div>
                <!-- Wholesale Cost -->
                <div class="flex justify-between items-center text-sm py-2 border-b border-slate-50">
                    <span class="text-slate-600 font-medium">
                        <?= lang('Reports.cost') ?>
                    </span>
                    <span class="text-slate-900 font-bold text-base">
                        <?= to_currency($total_cost) ?>
                    </span>
                </div>
                <!-- Gross Profit -->
                <div class="flex justify-between items-center text-sm py-2">
                    <span class="text-emerald-700 font-bold">
                        <?= lang('Reports.profit') ?> (
                        <?= lang('Reports.total') ?>)
                    </span>
                    <div class="text-right">
                        <div class="text-emerald-700 font-bold text-lg">
                            <?= to_currency($gross_profit) ?>
                        </div>
                        <div class="text-xs text-emerald-500 font-medium">
                            <?= $profit_margin ?>%
                            <?= lang('Reports.margin') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expenses Section -->
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">
                <?= lang('Reports.expenses') ?>
            </h3>
            <div class="space-y-3">
                <?php if (!empty($expenses_data)): ?>
                    <?php foreach ($expenses_data as $expense): ?>
                        <div class="flex justify-between items-center text-sm py-2 border-b border-slate-50">
                            <span class="text-slate-600 font-medium">
                                <?= $expense['category_name'] ?>
                            </span>
                            <span class="text-slate-900 font-semibold">
                                <?= to_currency($expense['total_amount']) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4 text-slate-400 italic text-sm">
                        <?= lang('Reports.no_expenses_to_display') ?>
                    </div>
                <?php endif; ?>

                <!-- Total Expenses -->
                <div class="flex justify-between items-center text-sm py-3 mt-2 bg-rose-50/50 -mx-2 px-2 rounded-lg">
                    <span class="text-rose-700 font-bold">
                        <?= lang('Reports.total_expenses') ?>
                    </span>
                    <span class="text-rose-700 font-bold text-lg">
                        <?= to_currency($total_expenses) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Net Profit Section -->
        <div class="pt-6 border-t-2 border-slate-100">
            <div class="flex justify-between items-center">
                <span class="text-xl font-black text-slate-800">
                    <?= lang('Reports.net_profit') ?>
                </span>
                <span class="text-2xl font-black <?= $net_profit >= 0 ? 'text-emerald-600' : 'text-rose-600' ?>">
                    <?= to_currency($net_profit) ?>
                </span>
            </div>
        </div>

    </div>

    <!-- Print Footer -->
    <div class="bg-slate-50 p-4 text-center text-xs text-slate-400 border-t border-slate-100">
        <?= date('d/m/Y H:i') ?>
    </div>
</div>