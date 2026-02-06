<?php
/**
 * @var string $controller_name
 * @var object $person_info
 * @var array $categories
 */
?>

<ul id="error_message_box" class="error_message_box text-sm text-red-500 mb-4"></ul>

<?= form_open("$controller_name/save/$person_info->person_id", ['id' => 'supplier_form', 'class' => 'space-y-6']) ?>
<fieldset id="supplier_basic_info" class="flex flex-col gap-2">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">

        <div class="form-group w-full">
            <?= form_label(lang('Suppliers.company_name'), 'company_name', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
            <?= form_input([
                'name' => 'company_name',
                'id' => 'company_name_input',
                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                'value' => html_entity_decode($person_info->company_name)
            ]) ?>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Suppliers.category'), 'category', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
            <?= form_dropdown('category', $categories, $person_info->category, ['class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2', 'id' => 'category']) ?>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Suppliers.agency_name'), 'agency_name', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <?= form_input([
                'name' => 'agency_name',
                'id' => 'agency_name_input',
                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                'value' => $person_info->agency_name
            ]) ?>
        </div>


        <div class="hidden">
            <div class="form-group w-full">
                <?= form_label(lang('Common.gender'), 'gender', !empty($basic_version) ? ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required text-start'] : ['class' => 'label text-sm font-medium text-slate-700 mb-1 block text-start']) ?>
                <div class="flex items-center gap-4 h-10">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <?= form_radio([
                            'name' => 'gender',
                            'type' => 'radio',
                            'id' => 'gender',
                            'value' => 1,
                            'checked' => $person_info->gender === '1',
                            'class' => 'w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300'
                        ]) ?>
                        <span class="text-sm text-slate-700">
                            <?= lang('Common.gender_male') ?>
                        </span>
                    </label>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <?= form_radio([
                            'name' => 'gender',
                            'type' => 'radio',
                            'id' => 'gender',
                            'value' => 0,
                            'checked' => $person_info->gender === '0',
                            'class' => 'w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300'
                        ]) ?>
                        <span class="text-sm text-slate-700">
                            <?= lang('Common.gender_female') ?>
                        </span>
                    </label>
                </div>
            </div>

            <div class="form-group w-full md:col-span-2">
                <?= form_label(lang('Common.address_1'), 'address_1', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                <?= form_input([
                    'name' => 'address_1',
                    'id' => 'address_1',
                    'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                    'value' => $person_info->address_1
                ]) ?>
            </div>

            <div class="form-group w-full md:col-span-2">
                <?= form_label(lang('Common.address_2'), 'address_2', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                <?= form_input([
                    'name' => 'address_2',
                    'id' => 'address_2',
                    'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                    'value' => $person_info->address_2
                ]) ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <div class="form-group w-full">
                    <?= form_label(lang('Common.city'), 'city', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <?= form_input([
                        'name' => 'city',
                        'id' => 'city',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                        'value' => $person_info->city
                    ]) ?>
                </div>

                <div class="form-group w-full">
                    <?= form_label(lang('Common.state'), 'state', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <?= form_input([
                        'name' => 'state',
                        'id' => 'state',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                        'value' => $person_info->state
                    ]) ?>
                </div>

                <div class="form-group w-full">
                    <?= form_label(lang('Common.zip'), 'zip', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <?= form_input([
                        'name' => 'zip',
                        'id' => 'postcode',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                        'value' => $person_info->zip
                    ]) ?>
                </div>

                <div class="form-group w-full">
                    <?= form_label(lang('Common.country'), 'country', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <?= form_input([
                        'name' => 'country',
                        'id' => 'country',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                        'value' => $person_info->country
                    ]) ?>
                </div>
            </div>

            <div class="form-group w-full md:col-span-2">
                <?= form_label(lang('Common.comments'), 'comments', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                <?= form_textarea([
                    'name' => 'comments',
                    'id' => 'comments',
                    'class' => 'flex min-h-[80px] w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                    'rows' => '3',
                    'value' => $person_info->comments
                ]) ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <div class="form-group w-full">
            <?= form_label(lang('Common.first_name'), 'first_name', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required text-start']) ?>
            <?= form_input([
                'name' => 'first_name',
                'id' => 'first_name',
                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 text-start',
                'value' => $person_info->first_name
            ]) ?>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Common.last_name'), 'last_name', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required text-start']) ?>
            <?= form_input([
                'name' => 'last_name',
                'id' => 'last_name',
                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 text-start',
                'value' => $person_info->last_name
            ]) ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <div class="form-group w-full">
            <?= form_label(lang('Common.email'), 'email', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                </div>
                <?= form_input([
                    'name' => 'email',
                    'id' => 'email',
                    'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white ps-10 pe-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                    'value' => $person_info->email
                ]) ?>
            </div>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Common.phone_number'), 'phone_number', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                    <i data-lucide="phone" class="w-4 h-4"></i>
                </div>
                <?= form_input([
                    'name' => 'phone_number',
                    'id' => 'phone_number',
                    'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white ps-10 pe-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                    'value' => $person_info->phone_number
                ]) ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <div class="form-group w-full">
            <?= form_label(lang('Suppliers.account_number'), 'account_number', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <?= form_input([
                'name' => 'account_number',
                'id' => 'account_number',
                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                'value' => $person_info->account_number
            ]) ?>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Suppliers.tax_id'), 'tax_id', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <?= form_input([
                'name' => 'tax_id',
                'id' => 'tax_id',
                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                'value' => $person_info->tax_id
            ]) ?>
        </div>
    </div>



</fieldset>
<?= form_close() ?>

<script type="text/javascript">
    // Validation and submit handling
    $(document).ready(function () {
        $('#supplier_form').validate($.extend({
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