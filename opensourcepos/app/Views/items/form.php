<?php
/**
 * @var object $item_info
 * @var array $categories
 * @var int $selected_category
 * @var bool $standard_item_locked
 * @var bool $item_kit_disabled
 * @var int $allow_temp_item
 * @var array $suppliers
 * @var int $selected_supplier
 * @var bool $use_destination_based_tax
 * @var float $default_tax_1_rate
 * @var float $default_tax_2_rate
 * @var string $tax_category
 * @var int $tax_category_id
 * @var bool $include_hsn
 * @var string $hsn_code
 * @var array $stock_locations
 * @var bool $logo_exists
 * @var string $image_path
 * @var string $selected_low_sell_item
 * @var int $selected_low_sell_item_id
 * @var string $controller_name
 * @var array $config
 */
?>

<?= form_open("items/save/$item_info->item_id", ['id' => 'item_form', 'enctype' => 'multipart/form-data', 'class' => 'space-y-6']) ?>
    <fieldset id="item_basic_info" class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="md:col-span-2">

            <ul id="error_message_box" class="error_message_box text-sm text-red-500"></ul>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Items.item_number'), 'item_number', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <?= form_input([
                    'name'  => 'item_number',
                    'id'    => 'item_number',
                    'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white ps-10 pe-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
                    'value' => $item_info->item_number
                ]) ?>
            </div>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Items.name'), 'name', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
            <?= form_input([
                'name'  => 'name',
                'id'    => 'name',
                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
                'value' => $item_info->name
            ]) ?>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Items.category'), 'category', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <?php
                if ($config['category_dropdown']) {
                    echo form_dropdown('category', $categories, $selected_category, ['class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white ps-10 pe-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2']);
                } else {
                    echo form_input([
                        'name'  => 'category',
                        'id'    => 'category',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white ps-10 pe-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
                        'value' => $item_info->category
                    ]);
                }
                ?>
            </div>
        </div>

        <div id="attributes" class="md:col-span-2">
            <script type="text/javascript">
                $('#attributes').load('<?= "items/attributes/$item_info->item_id" ?>');
            </script>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Items.stock_type'), 'stock_type', !empty($basic_version) ? ['class' => 'label text-sm font-medium text-slate-700 mb-2 block required'] : ['class' => 'label text-sm font-medium text-slate-700 mb-2 block']) ?>
            <div class="flex gap-4">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <?= form_radio([
                        'name'    => 'stock_type',
                        'type'    => 'radio',
                        'id'      => 'stock_type',
                        'value'   => 0,
                        'checked' => $item_info->stock_type == HAS_STOCK,
                        'class'   => 'text-indigo-600 focus:ring-indigo-500 border-gray-300'
                    ]) ?> 
                    <span class="text-sm text-slate-700"><?= lang('Items.stock') ?></span>
                </label>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <?= form_radio([
                        'name'    => 'stock_type',
                        'type'    => 'radio',
                        'id'      => 'stock_type',
                        'value'   => 1,
                        'checked' => $item_info->stock_type == HAS_NO_STOCK,
                        'class'   => 'text-indigo-600 focus:ring-indigo-500 border-gray-300'
                    ]) ?>
                    <span class="text-sm text-slate-700"><?= lang('Items.nonstock') ?></span>
                </label>
            </div>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Items.type'), 'item_type', !empty($basic_version) ? ['class' => 'label text-sm font-medium text-slate-700 mb-2 block required'] : ['class' => 'label text-sm font-medium text-slate-700 mb-2 block']) ?>
            <div class="flex flex-wrap gap-4">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <?php
                    $radio_button = [
                        'name'    => 'item_type',
                        'type'    => 'radio',
                        'id'      => 'item_type',
                        'value'   => 0,
                        'checked' => $item_info->item_type == ITEM,
                        'class'   => 'text-indigo-600 focus:ring-indigo-500 border-gray-300'
                    ];

                    if ($standard_item_locked) {
                        $radio_button['disabled'] = true;
                    }
                    echo form_radio($radio_button) ?> 
                    <span class="text-sm text-slate-700"><?= lang('Items.standard') ?></span>
                </label>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <?php
                    $radio_button = [
                        'name'    => 'item_type',
                        'type'    => 'radio',
                        'id'      => 'item_type',
                        'value'   => 1,
                        'checked' => $item_info->item_type == ITEM_KIT,
                        'class'   => 'text-indigo-600 focus:ring-indigo-500 border-gray-300'
                    ];

                    if ($item_kit_disabled) {
                        $radio_button['disabled'] = true;
                    }
                    echo form_radio($radio_button) ?> 
                    <span class="text-sm text-slate-700"><?= lang('Items.kit') ?></span>
                </label>
                <?php if ($config['derive_sale_quantity'] == '1') { ?>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <?= form_radio([
                            'name'    => 'item_type',
                            'type'    => 'radio',
                            'id'      => 'item_type',
                            'value'   => 2,
                            'checked' => $item_info->item_type == ITEM_AMOUNT_ENTRY,
                            'class'   => 'text-indigo-600 focus:ring-indigo-500 border-gray-300'
                        ]) ?>
                        <span class="text-sm text-slate-700"><?= lang('Items.amount_entry') ?></span>
                    </label>
                <?php } ?>
                <?php if ($allow_temp_item == 1) { ?>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <?= form_radio([
                            'name'    => 'item_type',
                            'type'    => 'radio',
                            'id'      => 'item_type',
                            'value'   => 3,
                            'checked' => $item_info->item_type == ITEM_TEMP,
                            'class'   => 'text-indigo-600 focus:ring-indigo-500 border-gray-300'
                        ]) ?> 
                        <span class="text-sm text-slate-700"><?= lang('Items.temp') ?></span>
                    </label>
                <?php } ?>
            </div>
        </div>

        <div class="form-group w-full md:col-span-2">
            <?= form_label(lang('Items.supplier'), 'supplier', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <?= form_dropdown('supplier_id', $suppliers, $selected_supplier, ['class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2']) ?>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Items.cost_price'), 'cost_price', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
            <div class="relative">
                <?php if (!is_right_side_currency_symbol()): ?>
                    <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-slate-500 text-sm font-medium"><?= esc($config['currency_symbol']) ?></span>
                <?php endif; ?>
                <?= form_input([
                    'name'    => 'cost_price',
                    'id'      => 'cost_price',
                    'class'   => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 ' . (!is_right_side_currency_symbol() ? 'pl-8' : '') . (is_right_side_currency_symbol() ? 'pr-8' : '') . ' text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                    'onClick' => 'this.select();',
                    'value'   => to_currency_no_money($item_info->cost_price)
                ]) ?>
                <?php if (is_right_side_currency_symbol()): ?>
                    <span class="absolute inset-y-0 end-0 flex items-center pr-3 text-slate-500 text-sm font-medium"><?= esc($config['currency_symbol']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Items.unit_price'), 'unit_price', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
            <div class="relative">
                <?php if (!is_right_side_currency_symbol()): ?>
                    <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-slate-500 text-sm font-medium"><?= esc($config['currency_symbol']) ?></span>
                <?php endif; ?>
                <?= form_input([
                    'name'    => 'unit_price',
                    'id'      => 'unit_price',
                    'class'   => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 ' . (!is_right_side_currency_symbol() ? 'pl-8' : '') . (is_right_side_currency_symbol() ? 'pr-8' : '') . ' text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                    'onClick' => 'this.select();',
                    'value'   => to_currency_no_money($item_info->unit_price)
                ]) ?>
                <?php if (is_right_side_currency_symbol()): ?>
                    <span class="absolute inset-y-0 end-0 flex items-center pr-3 text-slate-500 text-sm font-medium"><?= esc($config['currency_symbol']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!$use_destination_based_tax) { ?>
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tax 1 -->
                <div class="form-group w-full">
                    <?= form_label(lang('Items.tax_1'), 'tax_percent_1', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <div class="flex gap-2">
                        <div class="w-1/2">
                            <?= form_input([
                                'name'  => 'tax_names[]',
                                'id'    => 'tax_name_1',
                                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                                'value' => $item_tax_info[0]['name'] ?? $config['default_tax_1_name']
                            ]) ?>
                        </div>
                        <div class="w-1/2 relative">
                            <?= form_input([
                                'name'  => 'tax_percents[]',
                                'id'    => 'tax_percent_name_1',
                                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                                'value' => isset($item_tax_info[0]['percent']) ? to_tax_decimals($item_tax_info[0]['percent']) : to_tax_decimals($default_tax_1_rate)
                            ]) ?>
                            <span class="absolute inset-y-0 end-0 flex items-center pr-3 text-slate-500 text-sm font-medium">%</span>
                        </div>
                    </div>
                </div>

                <!-- Tax 2 -->
                <div class="form-group w-full">
                    <?= form_label(lang('Items.tax_2'), 'tax_percent_2', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <div class="flex gap-2">
                        <div class="w-1/2">
                            <?= form_input([
                                'name'  => 'tax_names[]',
                                'id'    => 'tax_name_2',
                                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                                'value' => $item_tax_info[1]['name'] ?? $config['default_tax_2_name']
                            ]) ?>
                        </div>
                        <div class="w-1/2 relative">
                            <?= form_input([
                                'name'  => 'tax_percents[]',
                                'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                                'id'    => 'tax_percent_name_2',
                                'value' => isset($item_tax_info[1]['percent']) ? to_tax_decimals($item_tax_info[1]['percent']) : to_tax_decimals($default_tax_2_rate)
                            ]) ?>
                            <span class="absolute inset-y-0 end-0 flex items-center pr-3 text-slate-500 text-sm font-medium">%</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ($use_destination_based_tax): ?>
            <div class="form-group w-full md:col-span-2">
                <?= form_label(lang('Taxes.tax_category'), 'tax_category', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                <div class="relative">
                    <?= form_input([
                        'name'  => 'tax_category',
                        'id'    => 'tax_category',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                        'size'  => '50',
                        'value' => $tax_category
                    ]) ?>
                    <?= form_hidden('tax_category_id', $tax_category_id) ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($include_hsn): ?>
            <div class="form-group w-full md:col-span-2">
                <?= form_label(lang('Items.hsn_code'), 'category', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                <div class="relative">
                    <?= form_input([
                        'name'  => 'hsn_code',
                        'id'    => 'hsn_code',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                        'value' => $hsn_code
                    ]) ?>
                </div>
            </div>
        <?php endif; ?>

        <?php foreach ($stock_locations as $key => $location_detail) { ?>
            <div class="form-group w-full">
                <?= form_label(lang('Items.quantity') . ' ' . $location_detail['location_name'], "quantity_$key", ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
                <?= form_input([
                    'name'    => "quantity_$key",
                    'id'      => "quantity_$key",
                    'class'   => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 required quantity',
                    'onClick' => 'this.select();',
                    'value'   => isset($item_info->item_id) ? to_quantity_decimals($location_detail['quantity']) : to_quantity_decimals(0)
                ]) ?>
            </div>
        <?php } ?>

        <div class="form-group w-full">
            <?= form_label(lang('Items.receiving_quantity'), 'receiving_quantity', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
            <?= form_input([
                'name'    => 'receiving_quantity',
                'id'      => 'receiving_quantity',
                'class'   => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 required',
                'onClick' => 'this.select();',
                'value'   => isset($item_info->item_id) ? to_quantity_decimals($item_info->receiving_quantity) : to_quantity_decimals(0)
            ]) ?>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Items.reorder_level'), 'reorder_level', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required']) ?>
            <?= form_input([
                'name'    => 'reorder_level',
                'id'      => 'reorder_level',
                'class'   => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                'onClick' => 'this.select();',
                'value'   => isset($item_info->item_id) ? to_quantity_decimals($item_info->reorder_level) : to_quantity_decimals(0)
            ]) ?>
        </div>

        <div class="form-group w-full md:col-span-2">
            <?= form_label(lang('Items.description'), 'description', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <?= form_textarea([
                'name'  => 'description',
                'id'    => 'description',
                'class' => 'flex min-h-[80px] w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                'value' => $item_info->description
            ]) ?>
        </div>

        <div class="form-group w-full md:col-span-2">
            <?= form_label(lang('Items.image'), 'items_image', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
            <div class="fileinput <?= $logo_exists ? 'fileinput-exists' : 'fileinput-new' ?>" data-provides="fileinput">
                <div class="fileinput-new thumbnail border border-slate-300 rounded-md bg-slate-50" style="width: 100px; height: 100px;"></div>
                <div class="fileinput-preview fileinput-exists thumbnail border border-slate-300 rounded-md overflow-hidden" style="max-width: 100px; max-height: 100px;">
                    <img data-src="holder.js/100%x100%" alt="<?= lang('Items.image') ?>"
                        src="<?= $image_path ?>"
                        style="max-height: 100%; max-width: 100%;">
                </div>
                <div class="mt-2">
                    <span class="btn btn-default btn-sm btn-file bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-3 py-1.5 rounded-md shadow-sm transition-colors cursor-pointer inline-block">
                        <span class="fileinput-new"><?= lang('Items.select_image') ?></span>
                        <span class="fileinput-exists"><?= lang('Items.change_image') ?></span>
                        <input type="file" name="items_image" accept="image/*">
                    </span>
                    <a href="#" class="btn btn-default btn-sm fileinput-exists bg-white border border-slate-300 hover:bg-slate-50 text-red-600 px-3 py-1.5 rounded-md shadow-sm transition-colors ms-2 no-underline" data-dismiss="fileinput"><?= lang('Items.remove_image') ?></a>
                </div>
            </div>
        </div>

        <div class="form-group w-full md:col-span-2 flex items-center gap-2">
            <?= form_checkbox([
                'name'    => 'allow_alt_description',
                'id'      => 'allow_alt_description',
                'value'   => 1,
                'checked' => $item_info->allow_alt_description == 1,
                'class'   => 'rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
            ]) ?>
            <?= form_label(lang('Items.allow_alt_description'), 'allow_alt_description', ['class' => 'text-sm text-slate-700 cursor-pointer mb-0']) ?>
        </div>

        <div class="form-group w-full md:col-span-2 flex items-center gap-2">
            <?= form_checkbox([
                'name'    => 'is_serialized',
                'id'      => 'is_serialized',
                'value'   => 1,
                'checked' => $item_info->is_serialized == 1,
                'class'   => 'rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
            ]) ?>
            <?= form_label(lang('Items.is_serialized'), 'is_serialized', ['class' => 'text-sm text-slate-700 cursor-pointer mb-0']) ?>
        </div>

        <?php if ($config['multi_pack_enabled'] == '1') { ?>
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
                <div class="form-group w-full">
                    <?= form_label(lang('Items.qty_per_pack'), 'qty_per_pack', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <?= form_input([
                        'name'  => 'qty_per_pack',
                        'id'    => 'qty_per_pack',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                        'value' => isset($item_info->item_id) ? to_quantity_decimals($item_info->qty_per_pack) : to_quantity_decimals(0)
                    ]) ?>
                </div>
                <div class="form-group w-full">
                    <?= form_label(lang('Items.pack_name'), 'name', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <?= form_input([
                        'name'  => 'pack_name',
                        'id'    => 'pack_name',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                        'value' => $item_info->pack_name
                    ]) ?>
                </div>
                <div class="form-group w-full md:col-span-2">
                    <?= form_label(lang('Items.low_sell_item'), 'low_sell_item_name', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <div class="relative">
                        <?= form_input([
                            'name'  => 'low_sell_item_name',
                            'id'    => 'low_sell_item_name',
                            'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                            'value' => $selected_low_sell_item
                        ]) ?>
                        <?= form_hidden('low_sell_item_id', $selected_low_sell_item_id) ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="form-group w-full md:col-span-2 flex items-center gap-2">
            <?= form_checkbox([
                'name'    => 'is_deleted',
                'id'      => 'is_deleted',
                'value'   => 1,
                'checked' => $item_info->deleted == 1,
                'class'   => 'rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50'
            ]) ?>
            <?= form_label(lang('Items.is_deleted'), 'is_deleted', ['class' => 'text-sm text-slate-700 cursor-pointer mb-0']) ?>
        </div>

    </fieldset>
<?= form_close() ?>

<script type="text/javascript">
    // Validation and submit handling
    $(document).ready(function() {
        $('#new').click(function() {
            let stay_open = true;
            $('#item_form').submit();
        });

        $('#submit').click(function() {
            let stay_open = false;
        });

        $("input[name='tax_category']").change(function() {
            !$(this).val() && $(this).val('');
        });

        var fill_tax_category_value = function(event, ui) {
            event.preventDefault();
            $("input[name='tax_category_id']").val(ui.item.value);
            $("input[name='tax_category']").val(ui.item.label);
        };

        $('#tax_category').autocomplete({
            source: "<?= 'taxes/suggestTaxCategories' ?>",
            minChars: 0,
            delay: 15,
            cacheLength: 1,
            appendTo: '.modal-content',
            select: fill_tax_category_value,
            focus: fill_tax_category_value
        });

        var fill_low_sell_value = function(event, ui) {
            event.preventDefault();
            $("input[name='low_sell_item_id']").val(ui.item.value);
            $("input[name='low_sell_item_name']").val(ui.item.label);
        };

        $('#low_sell_item_name').autocomplete({
            source: "<?= 'items/suggestLowSell' ?>",
            minChars: 0,
            delay: 15,
            cacheLength: 1,
            appendTo: '.modal-content',
            select: fill_low_sell_value,
            focus: fill_low_sell_value
        });

        $('#category').autocomplete({
            source: "<?= 'items/suggestCategory' ?>",
            delay: 10,
            appendTo: '.modal-content'
        });

        $('a.fileinput-exists').click(function() {
            $.ajax({
                type: 'GET',
                url: '<?= "$controller_name/removeLogo/$item_info->item_id" ?>',
                dataType: 'json'
            })
        });

        $.validator.addMethod('valid_chars', function(value, element) {
            return value.match(/(\||_)/g) == null;
        }, "<?= lang('Attributes.attribute_value_invalid_chars') ?>");

        var init_validation = function() {
            $('#item_form').validate($.extend({
                submitHandler: function(form, event) { // Event is not used as a parameter here
                    $(form).ajaxSubmit({
                        success: function(response) {
                            let stay_open = dialog_support.clicked_id() != 'submit';
                            if (stay_open) {
                                // Set action of item_form to url without item id, so a new one can be created
                                $('#item_form').attr('action', "<?= 'items/save/' ?>");
                                // Use a whitelist of fields to minimize unintended side effects
                                $(':text, :password, :file, #description, #item_form').not('.quantity, #reorder_level, #tax_name_1, #receiving_quantity, ' +
                                    '#tax_percent_name_1, #category, #reference_number, #name, #cost_price, #unit_price, #taxed_cost_price, #taxed_unit_price, #definition_name, [name^="attribute_links"]').val('');
                                // De-select any checkboxes, radios and drop-down menus
                                $(':input', '#item_form').removeAttr('checked').removeAttr('selected');
                            } else {
                                dialog_support.hide();
                            }
                            table_support.handle_submit('<?= 'items' ?>', response, stay_open);
                            init_validation();
                        },
                        dataType: 'json'
                    });
                },

                errorLabelContainer: '#error_message_box',

                rules: {
                    name: 'required',
                    category: 'required',
                    item_number: {
                        required: false,
                        remote: {
                            url: "<?= esc("$controller_name/checkItemNumber") ?>",
                            type: 'POST',
                            data: {
                                'item_id': "<?= $item_info->item_id ?>"
                                // item_number should be passed into the function by default
                            }
                        }
                    },
                    cost_price: {
                        required: true,
                        remote: "<?= esc("$controller_name/checkNumeric") ?>"
                    },
                    unit_price: {
                        required: true,
                        remote: "<?= esc("$controller_name/checkNumeric") ?>"
                    },
                    <?php foreach ($stock_locations as $key => $location_detail) { ?>
                        <?= 'quantity_' . $key ?>: {
                            required: true,
                            remote: "<?= esc("$controller_name/checkNumeric") ?>"
                        },
                    <?php } ?>
                    receiving_quantity: {
                        required: true,
                        remote: "<?= esc("$controller_name/checkNumeric") ?>"
                    },
                    reorder_level: {
                        required: true,
                        remote: "<?= esc("$controller_name/checkNumeric") ?>"
                    },
                    tax_percent: {
                        required: false,
                        remote: "<?= esc("$controller_name/checkNumeric") ?>"
                    }
                },

                messages: {
                    name: "<?= lang('Items.name_required') ?>",
                    item_number: "<?= lang('Items.item_number_duplicate') ?>",
                    category: "<?= lang('Items.category_required') ?>",
                    cost_price: {
                        required: "<?= lang('Items.cost_price_required') ?>",
                        number: "<?= lang('Items.cost_price_number') ?>"
                    },
                    unit_price: {
                        required: "<?= lang('Items.unit_price_required') ?>",
                        number: "<?= lang('Items.unit_price_number') ?>"
                    },
                    <?php foreach ($stock_locations as $key => $location_detail) { ?>
                        <?= esc("quantity_$key", 'js') ?>: {
                            required: "<?= lang('Items.quantity_required') ?>",
                            number: "<?= lang('Items.quantity_number') ?>"
                        },
                    <?php } ?>
                    receiving_quantity: {
                        required: "<?= lang('Items.quantity_required') ?>",
                        number: "<?= lang('Items.quantity_number') ?>"
                    },
                    reorder_level: {
                        required: "<?= lang('Items.reorder_level_required') ?>",
                        number: "<?= lang('Items.reorder_level_number') ?>"
                    },
                    tax_percent: {
                        number: "<?= lang('Items.tax_percent_number') ?>"
                    }
                }
            }, form_support.error))
        };

        init_validation();
    });
</script>
