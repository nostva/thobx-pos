<?php
/**
 * @var string $controller_name
 */
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<div class="px-4 pt-4">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Tabs Header -->
        <div class="border-b border-slate-200 bg-slate-50/50">
            <ul class="flex flex-wrap -mb-px px-4" data-tabs="tabs">
                <li class="active group mr-2" role="presentation">
                    <a data-toggle="tab" href="#tax_codes_tab" title="<?= lang(ucfirst($controller_name) . '.tax_codes_configuration') ?>" class="inline-flex items-center gap-2 py-4 px-4 text-sm font-medium text-center border-b-2 border-emerald-500 text-emerald-600 active group-[.active]:border-emerald-500 group-[.active]:text-emerald-600 hover:text-emerald-500 hover:border-emerald-300 transition-colors">
                        <i data-lucide="tag" class="w-4 h-4"></i>
                        <?= lang(ucfirst($controller_name) . '.tax_codes') ?>
                    </a>
                </li>
                <li class="group mr-2" role="presentation">
                    <a data-toggle="tab" href="#tax_jurisdictions_tab" title="<?= lang(ucfirst($controller_name) . '.tax_jurisdictions_configuration') ?>" class="inline-flex items-center gap-2 py-4 px-4 text-sm font-medium text-center border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 active group-[.active]:border-emerald-500 group-[.active]:text-emerald-600 transition-colors">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        <?= lang(ucfirst($controller_name) . '.tax_jurisdictions') ?>
                    </a>
                </li>
                <li class="group mr-2" role="presentation">
                    <a data-toggle="tab" href="#tax_categories_tab" title="<?= lang(ucfirst($controller_name) . '.tax_categories_configuration') ?>" class="inline-flex items-center gap-2 py-4 px-4 text-sm font-medium text-center border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 active group-[.active]:border-emerald-500 group-[.active]:text-emerald-600 transition-colors">
                        <i data-lucide="folder-tree" class="w-4 h-4"></i>
                        <?= lang(ucfirst($controller_name) . '.tax_categories') ?>
                    </a>
                </li>
                <li class="group mr-2" role="presentation">
                    <a data-toggle="tab" href="#tax_rates_tab" title="<?= lang(ucfirst($controller_name) . '.tax_rate_configuration') ?>" class="inline-flex items-center gap-2 py-4 px-4 text-sm font-medium text-center border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 active group-[.active]:border-emerald-500 group-[.active]:text-emerald-600 transition-colors">
                        <i data-lucide="percent" class="w-4 h-4"></i>
                        <?= lang(ucfirst($controller_name) . '.tax_rates') ?>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Tabs Content -->
        <div class="tab-content p-6 bg-white min-h-[400px]">
            <div class="tab-pane fade in active" id="tax_codes_tab">
                <?= view('taxes/tax_codes') ?>
            </div>
            <div class="tab-pane" id="tax_jurisdictions_tab">
                <?= view('taxes/tax_jurisdictions') ?>
            </div>
            <div class="tab-pane" id="tax_categories_tab">
                <?= view('taxes/tax_categories') ?>
            </div>
            <div class="tab-pane" id="tax_rates_tab">
                <?= view('taxes/tax_rates') ?>
            </div>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
