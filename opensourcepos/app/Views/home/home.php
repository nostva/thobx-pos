<?php
/**
 * @var array $allowed_modules
 * @var array $stats
 */

$icon_map = [
    'items' => 'box',
    'sales' => 'shopping-cart',
    'receivings' => 'truck',
    'customers' => 'users',
    'suppliers' => 'briefcase',
    'employees' => 'user-check',
    'reports' => 'bar-chart-2',
    'config' => 'settings',
    'giftcards' => 'credit-card',
    'messages' => 'mail',
    'expenses' => 'dollar-sign',
    'taxes' => 'percent',
    'home' => 'home',
    'office' => 'building',
    'cashups' => 'banknote',
    'item_kits' => 'layers'
];

$color_map = [
    'items' => 'bg-blue-50 text-blue-600',
    'sales' => 'bg-emerald-50 text-emerald-600',
    'receivings' => 'bg-orange-50 text-orange-600',
    'customers' => 'bg-purple-50 text-purple-600',
    'suppliers' => 'bg-indigo-50 text-indigo-600',
    'reports' => 'bg-rose-50 text-rose-600',
    'expenses' => 'bg-amber-50 text-amber-600'
];
?>

<?= view('partial/header') ?>

<div class="space-y-8 animate-page-entry">
    <!-- Welcome Header -->
    <div class="flex flex-col gap-1">
        <h2 class="text-3xl font-bold tracking-tight text-slate-900"><?= lang('Common.welcome_message') ?></h2>
        <p class="text-slate-500"><?= lang('Common.business_overview') ?></p>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <i data-lucide="package" class="w-5 h-5"></i>
                </div>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Total</span>
            </div>
            <div class="text-2xl font-bold text-slate-900"><?= number_format($stats['total_items']) ?></div>
            <div class="text-sm text-slate-500 mt-1"><?= lang('Common.total_items') ?></div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                </div>
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Today</span>
            </div>
            <div class="text-2xl font-bold text-slate-900"><?= number_format($stats['sales_today']) ?></div>
            <div class="text-sm text-slate-500 mt-1"><?= lang('Common.todays_sales') ?></div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Active</span>
            </div>
            <div class="text-2xl font-bold text-slate-900"><?= number_format($stats['total_customers']) ?></div>
            <div class="text-sm text-slate-500 mt-1"><?= lang('Common.total_customers') ?></div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-rose-50 rounded-lg text-rose-600">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                </div>
                <span class="text-xs font-medium text-rose-600 bg-rose-50 px-2 py-1 rounded-full">Global</span>
            </div>
            <div class="text-2xl font-bold text-slate-900"><?= number_format($stats['total_sales']) ?></div>
            <div class="text-sm text-slate-500 mt-1"><?= lang('Common.global_sales') ?></div>
        </div>
    </div>

    <!-- Financial Summary Card -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-semibold text-slate-900"><?= lang('Common.current_month_summary') ?></h3>
            <a href="<?= site_url('reports/financial_summary') ?>" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium flex items-center gap-1">
                <?= lang('Reports.financial_summary') ?>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="text-center">
                    <div class="text-xs text-slate-500 mb-2 font-medium"><?= lang('Reports.sales') ?></div>
                    <div class="text-xl font-bold text-slate-900"><?= to_currency($financial_summary['total_sales']) ?></div>
                </div>
                <div class="text-center">
                    <div class="text-xs text-slate-500 mb-2 font-medium"><?= lang('Reports.cost') ?></div>
                    <div class="text-xl font-bold text-slate-900"><?= to_currency($financial_summary['total_cost']) ?></div>
                </div>
                <div class="text-center">
                    <div class="text-xs text-emerald-600 mb-2 font-medium"><?= lang('Reports.profit') ?></div>
                    <div class="text-xl font-bold text-emerald-600"><?= to_currency($financial_summary['gross_profit']) ?></div>
                </div>
                <div class="text-center">
                    <div class="text-xs text-rose-600 mb-2 font-medium"><?= lang('Reports.expenses') ?></div>
                    <div class="text-xl font-bold text-rose-600"><?= to_currency($financial_summary['total_expenses']) ?></div>
                </div>
                <div class="text-center">
                    <div class="text-xs text-slate-700 mb-2 font-bold"><?= lang('Reports.net_profit') ?></div>
                    <div class="text-2xl font-black <?= $financial_summary['net_profit'] >= 0 ? 'text-emerald-600' : 'text-rose-600' ?>"><?= to_currency($financial_summary['net_profit']) ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modules Grid -->
    <div class="space-y-4">
        <h3 class="text-xl font-semibold text-slate-900"><?= lang('Common.explore_modules') ?></h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            <?php foreach($allowed_modules as $module) { 
                $mid = $module->module_id;
                $icon = $icon_map[$mid] ?? 'circle';
                $colors = $color_map[$mid] ?? 'bg-slate-50 text-slate-600';
            ?>
                <a href="<?= base_url($mid) ?>" 
                   class="group bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-slate-900 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center gap-3">
                    <div class="p-3 <?= $colors ?> rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="<?= $icon ?>" class="w-8 h-8"></i>
                    </div>
                    <div class="text-slate-900 text-sm tracking-tight"><?= lang("Module.$mid") ?></div>
                </a>
            <?php } ?>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
