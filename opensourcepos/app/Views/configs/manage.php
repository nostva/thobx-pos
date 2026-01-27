<?= view('partial/header') ?>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<div class="flex flex-col md:flex-row gap-8 animate-in fade-in slide-up">
    <!-- Settings Sidebar -->
    <div class="w-full md:w-64 flex-shrink-0">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sticky top-24">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 px-3"><?= lang('Config.system_conf') ?></h3>
            <ul class="nav flex-col space-y-1" id="config-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#info_tab" class="px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all font-medium aria-selected:bg-emerald-50 aria-selected:text-emerald-600">
                        <i data-lucide="info"></i>
                        <?= lang('Config.info') ?>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#general_tab" class="px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all font-medium">
                        <i data-lucide="sliders"></i>
                        <?= lang('Config.general') ?>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tax_tab" class="px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all font-medium">
                        <i data-lucide="percent"></i>
                        <?= lang('Config.tax') ?>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#locale_tab" class="px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all font-medium">
                        <i data-lucide="globe"></i>
                        <?= lang('Config.locale') ?>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#barcode_tab" class="px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all font-medium">
                        <i data-lucide="scan"></i>
                        <?= lang('Config.barcode') ?>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#stock_tab" class="px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all font-medium">
                        <i data-lucide="package"></i>
                        <?= lang('Config.location') ?>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#receipt_tab" class="px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all font-medium">
                        <i data-lucide="receipt"></i>
                        <?= lang('Config.receipt') ?>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#invoice_tab" class="px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all font-medium">
                        <i data-lucide="file-text"></i>
                        <?= lang('Config.invoice') ?>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#reward_tab" class="px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all font-medium">
                        <i data-lucide="gift"></i>
                        <?= lang('Config.reward') ?>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#table_tab" class="px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all font-medium">
                        <i data-lucide="utensils"></i>
                        <?= lang('Config.table') ?>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#system_tab" class="px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-all font-medium">
                        <i data-lucide="cpu"></i>
                        <?= lang('Config.system_conf') ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Settings Content -->
    <div class="flex-1 bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
        <div class="tab-content">
            <div class="tab-pane fade in active" id="info_tab">
                <?= view('configs/info_config') ?>
            </div>
            <div class="tab-pane" id="general_tab">
                <?= view('configs/general_config') ?>
            </div>
            <div class="tab-pane" id="tax_tab">
                <?= view('configs/tax_config') ?>
            </div>
            <div class="tab-pane" id="locale_tab">
                <?= view('configs/locale_config') ?>
            </div>
            <div class="tab-pane" id="barcode_tab">
                <?= view('configs/barcode_config') ?>
            </div>
            <div class="tab-pane" id="stock_tab">
                <?= view('configs/stock_config') ?>
            </div>
            <div class="tab-pane" id="receipt_tab">
                <?= view('configs/receipt_config') ?>
            </div>
            <div class="tab-pane" id="invoice_tab">
                <?= view('configs/invoice_config') ?>
            </div>
            <div class="tab-pane" id="reward_tab">
                <?= view('configs/reward_config') ?>
            </div>
            <div class="tab-pane" id="table_tab">
                <?= view('configs/table_config') ?>
            </div>
            <div class="tab-pane" id="system_tab">
                <?= view('configs/system_config') ?>
            </div>
        </div>
    </div>
</div>

<style>
    /* Config Page Overrides to hide default bootstrap tab styling */
    #config-tabs li.active a {
        background-color: #ecfdf5; /* Emerald 50 */
        color: #059669; /* Emerald 600 */
    }
    .check-gap-radio {
        display: flex !important;
        gap: 1rem !important;
        align-items: center !important;
    }
</style>

<?= view('partial/footer') ?>
