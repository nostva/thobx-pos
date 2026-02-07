<?php
/**
 * @var object $expenses_info
 * @var array $payment_options
 * @var array $expense_categories
 * @var array $employees
 * @var string $controller_name
 * @var array $config
 */
?>

<?= form_open("expenses/save/$expenses_info->expense_id", ['id' => 'expenses_edit_form', 'class' => 'space-y-6']) ?>
<fieldset id="item_basic_info" class="flex flex-col gap-2">

    <div class="md:col-span-2">
        <ul id="error_message_box" class="error_message_box text-sm text-red-500"></ul>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <div class="form-group w-full">
            <?= form_label(lang('Expenses.info'), 'expenses_info', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <div class="text-sm font-bold text-slate-800 p-2 bg-slate-50 rounded border border-slate-200">
                <?= !empty($expenses_info->expense_id) ? lang('Expenses.expense_id') . " $expenses_info->expense_id" : lang('Expenses.new') ?>
            </div>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Expenses.date'), 'date', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <?= form_input([
                    'name' => 'date',
                    'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white ps-10 pe-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 datetime',
                    'value' => to_datetime(strtotime($expenses_info->date)),
                    'readonly' => 'readonly'
                ]) ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <div class="form-group w-full">
            <?= form_label(lang('Expenses.supplier_name'), 'supplier_name', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <div class="flex gap-2">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                        <i data-lucide="truck" class="w-4 h-4"></i>
                    </div>
                    <?= form_input([
                        'name' => 'supplier_name',
                        'id' => 'supplier_name',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white ps-10 pe-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
                        'value' => lang('Expenses.start_typing_supplier_name')
                    ]);
                    echo form_input([
                        'type' => 'hidden',
                        'name' => 'supplier_id',
                        'id' => 'supplier_id'
                    ]) ?>
                </div>
                <button type="button" id="remove_supplier_button"
                    class="btn btn-default btn-sm text-red-600 border-red-200 hover:bg-red-50 px-3 hidden"
                    title="Remove Supplier">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Expenses.supplier_tax_code'), 'supplier_tax_code', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <?= form_input([
                'name' => 'supplier_tax_code',
                'id' => 'supplier_tax_code',
                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                'value' => $expenses_info->supplier_tax_code
            ]) ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <div class="form-group w-full">
            <?= form_label(lang('Expenses.amount'), 'amount', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
            <div class="relative">
                <?php if (!is_right_side_currency_symbol()): ?>
                    <span
                        class="absolute inset-y-0 start-0 flex items-center ps-3 text-slate-500 text-sm font-medium"><?= esc($config['currency_symbol']) ?></span>
                <?php endif; ?>
                <?= form_input([
                    'name' => 'amount',
                    'id' => 'amount',
                    'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 ' . (!is_right_side_currency_symbol() ? 'pl-8' : '') . (is_right_side_currency_symbol() ? 'pr-8' : '') . ' text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                    'value' => to_currency_no_money($expenses_info->amount)
                ]) ?>
                <?php if (is_right_side_currency_symbol()): ?>
                    <span
                        class="absolute inset-y-0 end-0 flex items-center pe-3 text-slate-500 text-sm font-medium"><?= esc($config['currency_symbol']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Expenses.tax_amount'), 'tax_amount', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <div class="relative">
                <?php if (!is_right_side_currency_symbol()): ?>
                    <span
                        class="absolute inset-y-0 start-0 flex items-center ps-3 text-slate-500 text-sm font-medium"><?= esc($config['currency_symbol']) ?></span>
                <?php endif; ?>
                <?= form_input([
                    'name' => 'tax_amount',
                    'id' => 'tax_amount',
                    'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 ' . (!is_right_side_currency_symbol() ? 'pl-8' : '') . (is_right_side_currency_symbol() ? 'pr-8' : '') . ' text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                    'value' => to_currency_no_money($expenses_info->tax_amount)
                ]) ?>
                <?php if (is_right_side_currency_symbol()): ?>
                    <span
                        class="absolute inset-y-0 end-0 flex items-center pe-3 text-slate-500 text-sm font-medium"><?= esc($config['currency_symbol']) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
        <div class="form-group w-full">
            <?= form_label(lang('Expenses.payment'), 'payment_type', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <?= form_dropdown('payment_type', $payment_options, $expenses_info->payment_type, ['class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2', 'id' => 'payment_type']) ?>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Expenses_categories.name'), 'category', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <?= form_dropdown('expense_category_id', $expense_categories, $expenses_info->expense_category_id, ['class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2', 'id' => 'category']) ?>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Expenses.employee'), 'employee', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <?= form_dropdown('employee_id', $employees, $expenses_info->employee_id, 'id="employee_id" class="flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2"') ?>
        </div>
    </div>

    <div class="form-group w-full">
        <?= form_label(lang('Expenses.description'), 'description', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
        <?= form_textarea([
            'name' => 'description',
            'id' => 'description',
            'class' => 'flex min-h-[80px] w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
            'value' => $expenses_info->description
        ]) ?>
    </div>

    <?php if (!empty($expenses_info->expense_id)) { ?>
        <div class="form-group w-full">
            <div class="flex items-center gap-2">
                <?= form_checkbox([
                    'name' => 'deleted',
                    'id' => 'deleted',
                    'value' => 1,
                    'checked' => $expenses_info->deleted == 1,
                    'class' => 'rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50'
                ]) ?>
                <?= form_label(lang('Expenses.is_deleted'), 'deleted', ['class' => 'text-sm text-slate-700 cursor-pointer mb-0']) ?>
            </div>
        </div>
    <?php } ?>

</fieldset>
<?= form_close() ?>

<script type="text/javascript">
    // Validation and submit handling
    $(document).ready(function () {
        <?= view('partial/datepicker_locale') ?>

        $('#supplier_name').click(function () {
            $(this).attr('value', '');
        });

        $('#supplier_name').autocomplete({
            source: '<?= "suppliers/suggest" ?>',
            minChars: 0,
            delay: 10,
            appendTo: '.modal-content',
            select: function (event, ui) {
                $('#supplier_id').val(ui.item.value);
                $(this).val(ui.item.label);
                $(this).attr('readonly', 'readonly');
                $('#remove_supplier_button').removeClass('hidden');
                return false;
            }
        });

        $('#supplier_name').blur(function () {
            $(this).attr('value', "<?= lang('Expenses.start_typing_supplier_name') ?>");
        });

        $('#remove_supplier_button').click(function () {
            $('#supplier_id').val('');
            $('#supplier_name').removeAttr('readonly');
            $('#supplier_name').val('');
            $(this).addClass('hidden');
        });

        <?php if ($expenses_info->expense_id != -1) { ?>
            $('#supplier_id').val('<?= $expenses_info->supplier_id ?>');
            $('#supplier_name').val('<?= esc($expenses_info->supplier_name, 'js') ?>').attr('readonly', 'readonly');
            $('#remove_supplier_button').removeClass('hidden');
        <?php } ?>

        $('#expenses_edit_form').validate($.extend({
            submitHandler: function (form) {
                $(form).ajaxSubmit({
                    success: function (response) {
                        dialog_support.hide();
                        table_support.handle_submit("<?= esc($controller_name) ?>", response);
                    },
                    dataType: 'json'
                });
            },

            errorLabelContainer: '#error_message_box',

            ignore: '',

            rules: {
                supplier_name: 'required',
                category: 'required',
                expense_category_id: 'required',
                date: {
                    required: true
                },
                amount: {
                    required: true,
                    remote: "<?= "$controller_name/checkNumeric" ?>"
                },
                tax_amount: {
                    remote: "<?= "$controller_name/checkNumeric" ?>"
                }
            },

            messages: {
                category: "<?= lang('Expenses.category_required') ?>",
                expense_category_id: "<?= lang('Expenses_categories.category_name_required') ?>",
                date: {
                    required: "<?= lang('Expenses.date_required') ?>"

                },
                amount: {
                    required: "<?= lang('Expenses.amount_required') ?>",
                    remote: "<?= lang('Expenses.amount_number') ?>"
                },
                tax_amount: {
                    remote: "<?= lang('Expenses.tax_amount_number') ?>"
                }
            }
        }, form_support.error));
    });
</script>