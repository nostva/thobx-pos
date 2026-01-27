<?php
/**
 * @var string $controller_name
 * @var object $person_info
 * @var array $categories
 */
?>

<ul id="error_message_box" class="error_message_box text-sm text-red-500 mb-4"></ul>

<?= form_open("$controller_name/save/$person_info->person_id", ['id' => 'supplier_form', 'class' => 'space-y-6']) ?>
    <fieldset id="supplier_basic_info" class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="form-group w-full">
            <?= form_label(lang('Suppliers.company_name'), 'company_name', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
            <?= form_input([
                'name'  => 'company_name',
                'id'    => 'company_name_input',
                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                'value' => html_entity_decode($person_info->company_name)
            ]) ?>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Suppliers.category'), 'category', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
            <?= form_dropdown('category', $categories, $person_info->category, ['class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2', 'id' => 'category']) ?>
        </div>

        <div class="form-group w-full md:col-span-2">
            <?= form_label(lang('Suppliers.agency_name'), 'agency_name', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <?= form_input([
                'name'  => 'agency_name',
                'id'    => 'agency_name_input',
                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                'value' => $person_info->agency_name
            ]) ?>
        </div>

        <?= view('people/form_basic_info') ?>

        <div class="form-group w-full">
            <?= form_label(lang('Suppliers.account_number'), 'account_number', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <?= form_input([
                'name'  => 'account_number',
                'id'    => 'account_number',
                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                'value' => $person_info->account_number
            ]) ?>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Suppliers.tax_id'), 'tax_id', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <?= form_input([
                'name'  => 'tax_id',
                'id'    => 'tax_id',
                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                'value' => $person_info->tax_id
            ]) ?>
        </div>

    </fieldset>
<?= form_close() ?>

<script type="text/javascript">
    // Validation and submit handling
    $(document).ready(function() {
        $('#supplier_form').validate($.extend({
            submitHandler: function(form) {
                $(form).ajaxSubmit({
                    success: function(response) {
                        dialog_support.hide();
                        table_support.handle_submit("<?= esc($controller_name) ?>", response);
                    },
                    dataType: 'json'
                });
            },

            errorLabelContainer: '#error_message_box',

            rules: {
                company_name: 'required',
                first_name: 'required',
                last_name: 'required',
                email: 'email'
            },

            messages: {
                company_name: "<?= lang('Suppliers.company_name_required') ?>",
                first_name: "<?= lang('Common.first_name_required') ?>",
                last_name: "<?= lang('Common.last_name_required') ?>",
                email: "<?= lang('Common.email_invalid_format') ?>"
            }
        }, form_support.error));
    });
</script>
