<?php
/**
 * @var object $person_info
 * @var array $config
 */
?>

<?php
/**
 * @var object $person_info
 * @var array $config
 */
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="form-group w-full">
        <?= form_label(lang('Common.first_name'), 'first_name', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
        <?= form_input([
            'name'  => 'first_name',
            'id'    => 'first_name',
            'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
            'value' => $person_info->first_name
        ]) ?>
    </div>

    <div class="form-group w-full">
        <?= form_label(lang('Common.last_name'), 'last_name', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
        <?= form_input([
            'name'  => 'last_name',
            'id'    => 'last_name',
            'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
            'value' => $person_info->last_name
        ]) ?>
    </div>

    <div class="form-group w-full">
        <?= form_label(lang('Common.gender'), 'gender', !empty($basic_version) ? ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required'] : ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
        <div class="flex items-center gap-4 h-10">
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <?= form_radio([
                    'name'    => 'gender',
                    'type'    => 'radio',
                    'id'      => 'gender',
                    'value'   => 1,
                    'checked' => $person_info->gender === '1',
                    'class'   => 'text-indigo-600 focus:ring-indigo-500 border-gray-300'
                ]) ?>
                <span class="text-sm text-slate-700"><?= lang('Common.gender_male') ?></span>
            </label>
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <?= form_radio([
                    'name'    => 'gender',
                    'type'    => 'radio',
                    'id'      => 'gender',
                    'value'   => 0,
                    'checked' => $person_info->gender === '0',
                    'class'   => 'text-indigo-600 focus:ring-indigo-500 border-gray-300'
                ]) ?>
                <span class="text-sm text-slate-700"><?= lang('Common.gender_female') ?></span>
            </label>
        </div>
    </div>

    <div class="form-group w-full">
        <?= form_label(lang('Common.email'), 'email', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                <i data-lucide="mail" class="w-4 h-4"></i>
            </div>
            <?= form_input([
                'name'  => 'email',
                'id'    => 'email',
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
                'name'  => 'phone_number',
                'id'    => 'phone_number',
                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white ps-10 pe-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                'value' => $person_info->phone_number
            ]) ?>
        </div>
    </div>

    <div class="form-group w-full md:col-span-2">
        <?= form_label(lang('Common.address_1'), 'address_1', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
        <?= form_input([
            'name'  => 'address_1',
            'id'    => 'address_1',
            'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
            'value' => $person_info->address_1
        ]) ?>
    </div>

    <div class="form-group w-full md:col-span-2">
        <?= form_label(lang('Common.address_2'), 'address_2', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
        <?= form_input([
            'name'  => 'address_2',
            'id'    => 'address_2',
            'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
            'value' => $person_info->address_2
        ]) ?>
    </div>

    <div class="form-group w-full">
        <?= form_label(lang('Common.city'), 'city', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
        <?= form_input([
            'name'  => 'city',
            'id'    => 'city',
            'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
            'value' => $person_info->city
        ]) ?>
    </div>

    <div class="form-group w-full">
        <?= form_label(lang('Common.state'), 'state', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
        <?= form_input([
            'name'  => 'state',
            'id'    => 'state',
            'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
            'value' => $person_info->state
        ]) ?>
    </div>

    <div class="form-group w-full">
        <?= form_label(lang('Common.zip'), 'zip', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
        <?= form_input([
            'name'  => 'zip',
            'id'    => 'postcode',
            'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
            'value' => $person_info->zip
        ]) ?>
    </div>

    <div class="form-group w-full">
        <?= form_label(lang('Common.country'), 'country', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
        <?= form_input([
            'name'  => 'country',
            'id'    => 'country',
            'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
            'value' => $person_info->country
        ]) ?>
    </div>

    <div class="form-group w-full md:col-span-2">
        <?= form_label(lang('Common.comments'), 'comments', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
        <?= form_textarea([
            'name'  => 'comments',
            'id'    => 'comments',
            'class' => 'flex min-h-[80px] w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
            'rows'  => '3',
            'value' => $person_info->comments
        ]) ?>
    </div>
</div>

<script type="text/javascript">
    // Validation and submit handling
    $(document).ready(function() {
        nominatim.init({
            fields: {
                postcode: {
                    dependencies: ["postcode", "city", "state", "country"],
                    response: {
                        field: 'postalcode',
                        format: ["postcode", "village|town|hamlet|city_district|city", "state", "country"]
                    }
                },

                city: {
                    dependencies: ["postcode", "city", "state", "country"],
                    response: {
                        format: ["postcode", "village|town|hamlet|city_district|city", "state", "country"]
                    }
                },

                state: {
                    dependencies: ["state", "country"]
                },

                country: {
                    dependencies: ["state", "country"]
                }
            },
            language: '<?= current_language_code() ?>',
            country_codes: '<?= esc($config['country_codes'], 'js') ?>'
        });
    });
</script>
