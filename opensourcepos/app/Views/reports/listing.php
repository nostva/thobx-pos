<?php
/**
 * @var int   $person_id
 * @var array $permission_ids
 * @var array $grants
 */

$detailed_reports = [
    'reports_sales'      => 'detailed',
    'reports_receivings' => 'detailed',
    'reports_customers'  => 'specific',
    'reports_discounts'  => 'specific',
    'reports_employees'  => 'specific',
    'reports_suppliers'  => 'specific',
];
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<?php
if (isset($error)) {
    echo '<div class="alert alert-dismissible alert-danger">' . esc($error) . '</div>';
}
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8 animate-in fade-in slide-up">
    
    <!-- Graphical Reports -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-50 bg-slate-50/50 flex items-center gap-2">
            <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
            </div>
            <h3 class="font-bold text-slate-800"><?= lang('Reports.graphical_reports') ?></h3>
        </div>
        <div class="p-2">
            <?php foreach ($permission_ids as $permission_id) {
                if (can_show_report($permission_id, ['inventory', 'receiving'])) {
                    $link = get_report_link($permission_id, 'graphical_summary');
            ?>
                    <a class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-all font-medium" href="<?= $link['path'] ?>">
                        <i data-lucide="pie-chart" class="w-4 h-4 text-slate-400"></i>
                        <?= $link['label'] ?>
                    </a>
            <?php
                }
            }
            ?>
        </div>
    </div>

    <!-- Summary Reports -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-50 bg-slate-50/50 flex items-center gap-2">
            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                <i data-lucide="list" class="w-5 h-5"></i>
            </div>
            <h3 class="font-bold text-slate-800"><?= lang('Reports.summary_reports') ?></h3>
        </div>
        <div class="p-2">

            <!-- Financial Summary Report (Manual Link) -->
            <?php if (in_array('reports_sales', $permission_ids, true)): ?>
                <a class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition-all font-medium" href="<?= site_url('reports/financial_summary') ?>">
                    <i data-lucide="calculator" class="w-4 h-4 text-slate-400"></i>
                    <?= lang('Reports.financial_summary') ?>
                </a>
            <?php endif; ?>

            <?php foreach ($permission_ids as $permission_id) {
                if (can_show_report($permission_id, ['inventory', 'receiving'])) {
                    $link = get_report_link($permission_id, 'summary');
            ?>
                    <a class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition-all font-medium" href="<?= $link['path'] ?>">
                        <i data-lucide="file-minus" class="w-4 h-4 text-slate-400"></i>
                         <?= $link['label'] ?>
                    </a>
            <?php
                }
            }
            ?>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="space-y-8">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-50 bg-slate-50/50 flex items-center gap-2">
                <div class="p-2 bg-purple-100 text-purple-600 rounded-lg">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                </div>
                <h3 class="font-bold text-slate-800"><?= lang('Reports.detailed_reports') ?></h3>
            </div>
            <div class="p-2">
                <?php foreach ($detailed_reports as $report_name => $prefix) {
                    if (in_array($report_name, $permission_ids, true)) {
                        $link = get_report_link($report_name, $prefix);
                ?>
                        <a class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-600 hover:bg-purple-50 hover:text-purple-600 transition-all font-medium" href="<?= $link['path'] ?>">
                            <i data-lucide="align-justify" class="w-4 h-4 text-slate-400"></i>
                            <?= $link['label'] ?>
                        </a>
                <?php
                    }
                }
                ?>
            </div>
        </div>

        <?php if (in_array('reports_inventory', $permission_ids, true)) { ?>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-50 bg-slate-50/50 flex items-center gap-2">
                    <div class="p-2 bg-orange-100 text-orange-600 rounded-lg">
                        <i data-lucide="book-open" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-slate-800"><?= lang('Reports.inventory_reports') ?></h3>
                </div>
                <div class="p-2">
                    <?php
                    $inventory_low_report = get_report_link('reports_inventory_low');
                    $inventory_summary_report = get_report_link('reports_inventory_summary');
                    ?>
                    <a class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-600 hover:bg-orange-50 hover:text-orange-600 transition-all font-medium" href="<?= $inventory_low_report['path'] ?>">
                        <i data-lucide="arrow-down-circle" class="w-4 h-4 text-slate-400"></i>
                        <?= $inventory_low_report['label'] ?>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-600 hover:bg-orange-50 hover:text-orange-600 transition-all font-medium" href="<?= $inventory_summary_report['path'] ?>">
                        <i data-lucide="clipboard-list" class="w-4 h-4 text-slate-400"></i>
                        <?= $inventory_summary_report['label'] ?>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?= view('partial/footer') ?>
