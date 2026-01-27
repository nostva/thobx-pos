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
        <p class="text-slate-500">Back office overview and management.</p>
    </div>

    <!-- Statistics Grid (Same for UI consistency) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <i data-lucide="package" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-slate-900"><?= number_format($stats['total_items']) ?></div>
            <div class="text-sm text-slate-500 mt-1">Total Items</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-slate-900"><?= number_format($stats['sales_today']) ?></div>
            <div class="text-sm text-slate-500 mt-1">Today's Sales</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-slate-900"><?= number_format($stats['total_customers']) ?></div>
            <div class="text-sm text-slate-500 mt-1">Total Customers</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-rose-50 rounded-lg text-rose-600">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-slate-900"><?= number_format($stats['total_sales']) ?></div>
            <div class="text-sm text-slate-500 mt-1">Global Sales</div>
        </div>
    </div>

    <!-- Modules Grid -->
    <div class="space-y-4">
        <h3 class="text-xl font-semibold text-slate-900">Office Modules</h3>
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
