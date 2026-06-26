<?php
/**
 * @var string $controller_name
 * @var array $modes
 * @var array $mode
 * @var array $empty_tables
 * @var array $selected_table
 * @var array $stock_locations
 * @var array $stock_location
 * @var array $cart
 * @var bool $items_module_allowed
 * @var bool $change_price
 * @var int $customer_id
 * @var int $customer_discount_type
 * @var float $customer_discount
 * @var float $customer_total
 * @var string $customer_required
 * @var float|int $item_count
 * @var float|int $total_units
 * @var float $subtotal
 * @var array $taxes
 * @var float $total
 * @var float $payments_total
 * @var float $amount_due
 * @var bool $payments_cover_total
 * @var array $payment_options
 * @var array $selected_payment_type
 * @var bool $pos_mode
 * @var array $payments
 * @var string $mode_label
 * @var string $comment
 * @var bool $print_after_sale
 * @var bool $email_receipt
 * @var bool $price_work_orders
 * @var string $invoice_number
 * @var int $cash_mode
 * @var float $non_cash_total
 * @var float $cash_amount_due
 * @var array $config
 */

use App\Models\Employee;

?>

<?= view('partial/header') ?>

<?php
if (isset($error)) {
    echo '<div class="alert alert-dismissible alert-danger">' . esc($error) . '</div>';
}

if (!empty($warning)) {
    echo '<div class="alert alert-dismissible alert-warning">' . esc($warning) . '</div>';
}

if (isset($success)) {
    echo '<div class="alert alert-dismissible alert-success">' . esc($success) . '</div>';
}
?>

<div class="register-layout animate-page-entry">
    <?php $tabindex = 0; ?>
    <div class="flex flex-col gap-3">
        <!-- Top register controls -->
        <div class="register-card">
            <?= form_open("$controller_name/changeMode", ['id' => 'mode_form', 'class' => 'flex flex-wrap items-center justify-between gap-3']) ?>
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1.5">
                    <label
                        class="text-sm font-semibold text-slate-700"><?= lang(ucfirst($controller_name) . '.mode') ?>:</label>
                    <?= form_dropdown('mode', $modes, $mode, ['onchange' => "$('#mode_form').submit();", 'class' => 'flex h-8 w-fit rounded-md border border-slate-300 bg-white px-2 py-0.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400']) ?>
                </div>

                <?php if ($config['dinner_table_enable']) { ?>
                    <div class="flex items-center gap-1.5">
                        <label
                            class="text-sm font-semibold text-slate-700"><?= lang(ucfirst($controller_name) . '.table') ?>:</label>
                        <?= form_dropdown('dinner_table', $empty_tables, $selected_table, ['onchange' => "$('#mode_form').submit();", 'class' => 'flex h-8 w-fit rounded-md border border-slate-300 bg-white px-2 py-0.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400']) ?>
                    </div>
                <?php } ?>

                <?php if (count($stock_locations) > 1) { ?>
                    <div class="flex items-center gap-1.5">
                        <label
                            class="text-sm font-semibold text-slate-700"><?= lang(ucfirst($controller_name) . '.stock_location') ?>:</label>
                        <?= form_dropdown('stock_location', $stock_locations, $stock_location, ['onchange' => "$('#mode_form').submit();", 'class' => 'flex h-8 w-fit rounded-md border border-slate-300 bg-white px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400']) ?>
                    </div>
                <?php } ?>
            </div>

            <div class="flex items-center gap-2">
                <button class="btn btn-default btn-sm modal-dlg inline-flex items-center gap-2"
                    id="show_suspended_sales_button" data-href="<?= esc("$controller_name/suspended") ?>"
                    title="<?= lang(ucfirst($controller_name) . '.suspended_sales') ?>">
                    <i data-lucide="list" class="w-4 h-4"></i>
                    <?= lang(ucfirst($controller_name) . '.suspended_sales') ?>
                </button>

                <?php
                $employee = model(Employee::class);
                if ($employee->has_grant('reports_sales', session('person_id'))) {
                    ?>
                    <a href="<?= site_url("$controller_name/manage") ?>"
                        class="btn btn-primary btn-sm inline-flex items-center gap-2" id="sales_takings_button">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        <?= lang(ucfirst($controller_name) . '.takings') ?>
                    </a>
                <?php } ?>
            </div>
            <?= form_close() ?>
        </div>

        <!-- Find Item Area -->
        <div class="register-card">
            <?= form_open("$controller_name/add", ['id' => 'add_item_form', 'class' => 'flex items-center gap-2']) ?>
            <div class="relative flex-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <?= form_input([
                    'name' => 'item',
                    'id' => 'item',
                    'class' => 'flex h-10 w-full min-w-0 rounded-lg border border-slate-300 bg-white ps-10 pe-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400',
                    'placeholder' => lang(ucfirst($controller_name) . '.find_or_scan_item_or_receipt'),
                    'tabindex' => ++$tabindex
                ]) ?>
            </div>
            <button id="new_item_button"
                class="btn btn-info action-btn w-fit modal-dlg inline-flex items-center gap-2 h-10 px-4"
                data-btn-new="<?= lang('Common.new') ?>" data-btn-submit="<?= lang('Common.submit') ?>"
                data-href="<?= "items/view" ?>" title="<?= lang(ucfirst($controller_name) . ".new_item") ?>">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span class="hidden sm:inline"><?= lang(ucfirst($controller_name) . ".new_item") ?></span>
            </button>
            <?= form_close() ?>
        </div>

        <!-- Product Grid Section -->
        <div class="register-card">
            <div class="product-grid-container">
                <div class="product-grid-header">
                    <div class="product-grid-title">
                        <i data-lucide="grid-3x3" class="w-5 h-5"></i>
                        <span id="grid-title"><?= lang('Sales.product_categories') ?></span>
                    </div>
                    <button class="back-to-categories-btn hidden" id="back-to-categories">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <?= lang('Sales.back_to_categories') ?>
                    </button>
                </div>

                <!-- Loading State -->
                <div class="grid-loading hidden" id="grid-loading">
                    <div class="grid-spinner"></div>
                    <div class="grid-loading-text">Loading...</div>
                </div>

                <!-- Empty State -->
                <div class="grid-empty hidden" id="grid-empty">
                    <i data-lucide="inbox" class="w-12 h-12"></i>
                    <div class="grid-empty-text">No items found</div>
                </div>

                <!-- Category Grid -->
                <div class="category-grid" id="category-grid"></div>

                <!-- Product Grid -->
                <div class="product-grid hidden" id="product-grid"></div>
            </div>
        </div>



        <!-- Cart Table inside a card -->
        <div class="register-card overflow-hidden">


            <!-- <?= lang('Sales.sale_items_list') ?> -->

            <table class="table" id="register">
                <thead class="bg-slate-50">
                    <tr>

                        <th class="w-[10%]"><?= lang(ucfirst($controller_name) . '.item_number') ?></th>
                        <th class="w-[20%]"><?= lang(ucfirst($controller_name) . '.item_name') ?></th>
                        <th class="w-[10%]"><?= lang('Sales.cost_price') ?></th>
                        <th class="w-[10%]"><?= lang(ucfirst($controller_name) . '.price') ?></th>
                        <th class="w-[10%] text-center"><?= lang(ucfirst($controller_name) . '.quantity') ?></th>
                        <th class="w-[12%]"><?= lang(ucfirst($controller_name) . '.discount') ?></th>
                        <th class="w-[10%] text-end"><?= lang(ucfirst($controller_name) . '.total') ?></th>
                        <th class="w-[5%] text-center"><?= lang(ucfirst($controller_name) . '.update') ?></th>
                        <th class="w-[5%] text-center"><?= lang('Common.delete') ?></th>
                    </tr>
                </thead>

                <tbody id="cart_contents">
                    <?php if (count($cart) == 0) { ?>
                        <tr>
                            <td colspan="9">
                                <div class="flex flex-col items-center gap-2 text-slate-400">
                                    <i data-lucide="shopping-cart" class="w-12 h-12 opacity-20"></i>
                                    <span class="text-sm font-medium"><?= lang('Sales.no_items_in_cart') ?></span>
                                </div>
                            </td>
                        </tr>
                        <?php
                    } else {
                        foreach (array_reverse($cart, true) as $line => $item) {
                            ?>
                            <?= form_open("$controller_name/editItem/$line", ['class' => 'form-horizontal', 'id' => "cart_$line"]) ?>
                            <tr class="group cart-row" data-line="<?= $line ?>">
                                <?php if ($item['item_type'] == ITEM_TEMP) { ?>
                                    <td>
                                        <?php
                                        echo form_hidden('location', (string) $item['item_location']);
                                        echo form_input(['type' => 'hidden', 'name' => 'item_id', 'value' => $item['item_id']]);
                                        ?>
                                        <?= form_input(['name' => 'item_number', 'id' => 'item_number', 'class' => 'form-control input-sm', 'value' => $item['item_number'], 'tabindex' => ++$tabindex]) ?>
                                    </td>
                                    <td>
                                        <?= form_input(['name' => 'name', 'id' => 'name', 'class' => 'form-control input-sm', 'value' => $item['name'], 'tabindex' => ++$tabindex]) ?>
                                    </td>
                                <?php } else { ?>
                                    <td>
                                        <?php
                                        echo form_hidden('location', (string) $item['item_location']);
                                        echo form_input(['type' => 'hidden', 'name' => 'item_id', 'value' => $item['item_id']]);
                                        ?>
                                        <?= esc($item['item_number']) ?>
                                    </td>
                                    <td class="text-start">
                                        <?= esc($item['name']) . ' ' . implode(' ', [$item['attribute_values'], $item['attribute_dtvalues']]) ?>
                                        <br>
                                        <?php if ($item['stock_type'] == '0'):
                                            echo '[' . to_quantity_decimals($item['in_stock']) . ' in ' . $item['stock_name'] . ']';
                                        endif; ?>
                                    </td>
                                <?php } ?>

                                <td>
                                    <?php
                                    if ($items_module_allowed && $change_price) {
                                        echo form_input(['name' => 'cost_price', 'class' => 'form-control input-sm', 'value' => to_currency_no_money($item['cost_price']), 'tabindex' => ++$tabindex, 'onClick' => 'this.select();']);
                                    } else {
                                        echo to_currency($item['cost_price']);
                                        echo form_hidden('cost_price', to_currency_no_money($item['cost_price']));
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if ($items_module_allowed && $change_price) {
                                        echo form_input(['name' => 'price', 'class' => 'form-control input-sm', 'value' => to_currency_no_money($item['price']), 'tabindex' => ++$tabindex, 'onClick' => 'this.select();']);
                                    } else {
                                        echo to_currency($item['price']);
                                        echo form_hidden('price', to_currency_no_money($item['price']));
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if ($item['is_serialized']) {
                                        echo to_quantity_decimals($item['quantity']);
                                        echo form_hidden('quantity', $item['quantity']);
                                    } else {
                                        echo form_input(['name' => 'quantity', 'class' => 'form-control input-sm', 'value' => to_quantity_decimals($item['quantity']), 'tabindex' => ++$tabindex, 'onClick' => 'this.select();']);
                                    }
                                    ?>
                                </td>

                                <td>
                                    <div class="flex items-center gap-1 min-w-[120px]">
                                        <?= form_input(['name' => 'discount', 'class' => 'flex h-8 w-16 rounded-lg border border-slate-300 bg-white px-2 py-1 text-xs font-bold focus:outline-none focus:ring-1 focus:ring-slate-400', 'value' => $item['discount_type'] ? to_currency_no_money($item['discount']) : to_decimals($item['discount']), 'tabindex' => ++$tabindex, 'onClick' => 'this.select();']) ?>
                                        <div class="discount-toggle-wrapper flex-shrink-0">
                                            <?= form_checkbox(['id' => 'discount_toggle', 'name' => 'discount_toggle', 'value' => 1, 'data-toggle' => "toggle", 'data-size' => 'small', 'data-onstyle' => 'success', 'data-on' => '<b>' . $config['currency_symbol'] . '</b>', 'data-off' => '<b>%</b>', 'data-line' => $line, 'checked' => $item['discount_type'] == 1]) ?>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <?php
                                    if ($item['item_type'] == ITEM_AMOUNT_ENTRY) {    // TODO: === ?
                                        echo form_input(['name' => 'discounted_total', 'class' => 'form-control input-sm', 'value' => to_currency_no_money($item['discounted_total']), 'tabindex' => ++$tabindex, 'onClick' => 'this.select();']);
                                    } else {
                                        echo to_currency($item['discounted_total']);
                                    }
                                    ?>
                                </td>

                                <td class="text-center">
                                    <a href="javascript:document.getElementById('<?= "cart_$line" ?>').submit();"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-colors"
                                        title="<?= lang(ucfirst($controller_name) . '.update') ?>">
                                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                    </a>
                                </td>

                                <td class="text-center">
                                    <?= anchor("$controller_name/deleteItem/$line", '<i data-lucide="trash-2" class="w-4 h-4 text-rose-500 hover:text-rose-700 transition-colors"></i>', ['title' => lang('Common.delete')]) ?>
                                </td>
                            </tr>
                            <tr>
                                <?php if ($item['item_type'] == ITEM_TEMP) { ?>
                                    <td><?= form_input(['type' => 'hidden', 'name' => 'item_id', 'value' => $item['item_id']]) ?>
                                    </td>
                                    <td style="align: center;" colspan="6">
                                        <?= form_input(['name' => 'item_description', 'id' => 'item_description', 'class' => 'form-control input-sm', 'value' => $item['description'], 'tabindex' => ++$tabindex]) ?>
                                    </td>
                                    <td> </td>
                                <?php } else { ?>
                                    <td>&nbsp;</td>
                                    <?php if ($item['allow_alt_description']) { ?>
                                        <td style="color: #2F4F4F;"><?= lang(ucfirst($controller_name) . '.description_abbrv') ?></td>
                                    <?php } ?>

                                    <td colspan="2" style="text-align: left;">
                                        <?php
                                        if ($item['allow_alt_description']) {
                                            echo form_input(['name' => 'description', 'class' => 'form-control input-sm', 'value' => $item['description'], 'onClick' => 'this.select();']);
                                        } else {
                                            if ($item['description'] != '') {
                                                echo $item['description'];
                                                echo form_hidden('description', $item['description']);
                                            } else {
                                                echo lang(ucfirst($controller_name) . '.no_description');
                                                echo form_hidden('description', '');
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>&nbsp;</td>
                                    <td style="color: #2F4F4F;">
                                        <?php
                                        if ($item['is_serialized']) {
                                            echo lang(ucfirst($controller_name) . '.serial');
                                        }
                                        ?>
                                    </td>
                                    <td colspan="4" style="text-align: left;">
                                        <?php
                                        if ($item['is_serialized']) {
                                            echo form_input(['name' => 'serialnumber', 'class' => 'form-control input-sm', 'value' => $item['serialnumber'], 'onClick' => 'this.select();']);
                                        } else {
                                            echo form_hidden('serialnumber', '');
                                        }
                                        ?>
                                    </td>
                                <?php } ?>
                            </tr>
                            <?= form_close() ?>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div> <!-- End Cart Card -->

        <!-- Thobe Detail Section -->
        <?php if (isset($thobe_detail_enable) && $thobe_detail_enable && isset($customer)): ?>
            <div class="register-card">
                <div class="flex items-center justify-between cursor-pointer group" id="thobe_toggle_header">
                    <div class="flex items-center gap-2 text-slate-400">
                        <i data-lucide="scissors" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span class="text-sm font-bold uppercase tracking-wider
                            text-emerald-600"><?= lang('Thobe.thobe_detail') ?>
                        </span>

                    </div>
                    <div class="flex items-center gap-2">
                        <label class="switch mb-0">
                            <input type="checkbox" id="thobe_active_toggle" <?= isset($thobe_active) && $thobe_active ? 'checked' : '' ?>>
                            <span class="slider round"></span>
                        </label>
                        <i data-lucide="chevron-up" class="w-4 h-4 text-slate-400 transition-transform"
                            id="thobe_chevron"></i>
                    </div>
                </div>

                <div id="thobe_detail_body" class="pt-4 mt-3 border-t border-slate-100">
                    <form id="thobe_detail_form" class="space-y-4">
                        <!-- Fabric -->
                        <div>
                            <h6 class="text-xs font-bold text-slate-500 uppercase mb-2"><?= lang('Thobe.fabric') ?></h6>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="thobe_data[fabric_item_number]"
                                    placeholder="<?= lang('Thobe.fabric_item_number') ?>"
                                    class="form-control rounded-md text-sm border-slate-300 h-9"
                                    value="<?= esc($thobe_data['fabric_item_number'] ?? '') ?>">
                                <div class="relative">
                                    <input type="text" inputmode="decimal"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                        placeholder="<?= lang('Thobe.fabric_quantity') ?>"
                                        name="thobe_data[fabric_quantity]"
                                        class="form-control rounded-md text-sm border-slate-300 h-9 pe-8 rtl:pl-8"
                                        value="<?= isset($thobe_data['fabric_quantity']) && $thobe_data['fabric_quantity'] !== '' ? esc((float) $thobe_data['fabric_quantity']) : '' ?>">
                                    <span class="absolute end-3 top-2 text-slate-400 text-sm">
                                        <?= lang('Thobe.m') ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Measurements -->
                        <?php if (!empty($thobe_measurements)): ?>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h6 class="text-xs font-bold text-slate-500 uppercase mb-0">
                                        <?= lang('Thobe.measurement_fields') ?>
                                    </h6>
                                    <?php if (isset($customer_id) && $customer_id != -1): ?>
                                        <div class="flex items-center gap-1">
                                            <button type="button"
                                                class="btn btn-info btn-xs modal-dlg select-customer-profile-btn no-print"
                                                data-href="<?= site_url("sales/thobeCustomerProfiles/" . $customer_id) ?>"
                                                title="<?= lang('Thobe.select_customer_profile') . " " . $customer ?>"
                                                style="margin: 0; padding: 2px 8px; font-size: 11px; font-weight: bold; border-radius: 4px;">
                                                <?= lang('Thobe.select_customer_profile') . " " . $customer ?>
                                            </button>
                                            <button type="button" id="save_customer_profile_btn"
                                                class="btn btn-default btn-xs no-print"
                                                style="margin: 0; padding: 2px 8px; font-size: 11px; font-weight: bold; border-radius: 4px;">
                                                <?= lang('Thobe.save_profile') ?>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="relative">
                                        <label
                                            class="text-[10px] font-bold text-slate-400 absolute top-1 start-2 uppercase"><?= esc(lang("Thobe.profile_name")) ?></label>
                                        <input type="text" name="thobe_data[measurements_profile_name]"
                                            class="form-control rounded-md text-sm border-slate-300 h-12 pt-6 pb-1 px-2 w-full"
                                            value="<?= esc($thobe_data['measurements_profile_name'] ?? lang("Thobe.default")) ?>">
                                    </div>

                                    <?php foreach ($thobe_measurements as $m): ?>
                                        <?php $m_val = $thobe_data['measurements'][$m['measurement_id']] ?? ''; ?>
                                        <?php if ($m['value_type'] == 'boolean'): ?>
                                            <div
                                                class="col-span-2 flex items-center gap-2 p-2 border border-slate-200 rounded-md bg-slate-50">
                                                <input type="hidden" name="thobe_data[measurements][<?= $m['measurement_id'] ?>]"
                                                    value="0">
                                                <input type="checkbox" id="m_<?= $m['measurement_id'] ?>"
                                                    name="thobe_data[measurements][<?= $m['measurement_id'] ?>]" value="1" <?= $m_val ? 'checked' : '' ?>
                                                    class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                                <label for="m_<?= $m['measurement_id'] ?>"
                                                    class="text-sm text-slate-700 cursor-pointer font-medium"><?= esc($m['label']) ?></label>
                                            </div>
                                        <?php else: ?>
                                            <div class="relative">
                                                <label class="text-[10px] font-bold text-slate-400 absolute top-1 start-2 uppercase">
                                                    <?= esc($m['label']) ?>
                                                </label>
                                                <input type="text" <?= $m['value_type'] == 'number'
                                                    ? 'inputmode="decimal"
                                                        oninput="
                                                            this.value = this.value
                                                                .replace(/[^0-9.]/g, \'\')
                                                                .replace(/(\..*)\./g, \'$1\');
                                                        "'
                                                    : '' ?> name="thobe_data[measurements][<?= $m['measurement_id'] ?>]"
                                                    class="form-control rounded-md text-sm border-slate-300 h-12 pt-6 pb-1 px-2 w-full"
                                                    value="<?= esc($m_val) ?>">
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Options -->
                        <?php if (!empty($thobe_options)): ?>
                            <div>
                                <h6 class="text-xs font-bold text-slate-500 uppercase mb-2"><?= lang('Thobe.style_options') ?>
                                </h6>
                                <div class="space-y-4">
                                    <?php foreach ($thobe_options as $group): ?>
                                        <?php $g_val = $thobe_data['options'][$group['option_group_id']] ?? ''; ?>
                                        <div class="border border-slate-100 rounded-lg p-3 bg-slate-50/50">
                                            <span
                                                class="text-xs font-bold text-slate-600 block mb-2"><?= esc($group['name']) ?></span>
                                            <div class="flex flex-wrap gap-2">
                                                <!-- None Option -->
                                                <label
                                                    class="flex items-center gap-2 px-3 py-2 border <?= empty($g_val) ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200 bg-white' ?> rounded-lg hover:bg-slate-50 cursor-pointer text-sm font-medium transition-all thobe-radio-label min-h-[64px]">
                                                    <input type="radio" name="thobe_data[options][<?= $group['option_group_id'] ?>]"
                                                        value="" <?= empty($g_val) ? 'checked' : '' ?>
                                                        class="text-emerald-600 focus:ring-emerald-500 thobe-radio-input"
                                                        style="margin:0;">
                                                    <span><?= lang('Thobe.none') ?></span>
                                                </label>
                                                <?php foreach ($group['values'] as $val): ?>
                                                    <?php $is_selected = ($g_val == $val['option_value_id']); ?>
                                                    <label
                                                        class="flex items-center gap-2 px-3 py-2 border <?= $is_selected ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200 bg-white' ?> rounded-lg hover:bg-slate-50 cursor-pointer text-sm font-medium transition-all thobe-radio-label min-h-[64px]">
                                                        <input type="radio" name="thobe_data[options][<?= $group['option_group_id'] ?>]"
                                                            value="<?= $val['option_value_id'] ?>" <?= $is_selected ? 'checked' : '' ?>
                                                            class="text-emerald-600 focus:ring-emerald-500 thobe-radio-input"
                                                            style="margin:0;">
                                                        <?php if (!empty($val['image'])): ?>
                                                            <img src="<?= base_url('uploads/thobe_options/' . $val['image']) ?>"
                                                                class="w-12 h-12 object-cover rounded-md border border-slate-200">
                                                        <?php endif; ?>
                                                        <span><?= esc($val['name']) ?></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Notes -->
                        <div>
                            <textarea name="thobe_data[notes]" placeholder="<?= lang('Thobe.tailoring_notes') ?>"
                                class="form-control rounded-md text-sm border-slate-300 w-full resize-none"
                                rows="2"><?= esc($thobe_data['notes'] ?? '') ?></textarea>
                        </div>
                    </form>
                    <button type="button" id="reset_thobe_btn"
                        class="ms-2 px-2 py-0.5 text-[10px] font-bold text-red-500 hover:bg-red-50 rounded transition-colors group-hover:block"
                        onclick="event.stopPropagation(); resetThobeForm();">
                        <?= lang('Thobe.reset') ?>
                    </button>
                </div>
            </div>
            <script>
                function resetThobeForm() {
                    if (confirm('<?= lang('Thobe.clear_tailoring_confirm') ?>')) {
                        $('#thobe_detail_form')[0].reset();
                        // Uncheck checkboxes & radio buttons
                        $('#thobe_detail_form input[type="checkbox"]').prop('checked', false);
                        $('#thobe_detail_form input[type="radio"]').prop('checked', false);
                        // Check the 'None' radio buttons
                        $('#thobe_detail_form input[type="radio"][value=""]').prop('checked', true);
                        // Reset label borders
                        $('.thobe-radio-label').removeClass('border-emerald-500 bg-emerald-50/50').addClass('border-slate-200 bg-white');
                        $('.thobe-radio-input[value=""]').closest('.thobe-radio-label').removeClass('border-slate-200 bg-white').addClass('border-emerald-500 bg-emerald-50/50');
                        // Reset measurements inputs
                        $('#thobe_detail_form input[name^="thobe_data[measurements]"]').val('');
                        $('#thobe_detail_form input[name^="thobe_data[measurements_profile_name]"]').val('');
                        // Reset selected profile id
                        let data = $('#thobe_detail_form').serializeArray();
                        data.push({
                            name: 'thobe_data[selected_profile_id]',
                            value: ""
                        });

                        $.post('<?= site_url("sales/setThobeData") ?>', data);
                        $.notify({ message: '<?= lang('Thobe.clear_tailoring_success') ?>' }, { type: 'success' });
                    }
                }

                $(document).ready(function () {
                    function updateThobeState(active) {
                        let hasCustomer = <?= isset($customer) ? 'true' : 'false' ?>;
                        if (active) {
                            $('#thobe_detail_body').removeClass('opacity-50 pointer-events-none grayscale');
                            $('#thobe_detail_form').find('input, select, textarea').prop('disabled', false);

                            if (!hasCustomer) {
                                $('#finish_sale_container').addClass('hidden');
                                $('#finish_invoice_quote_container').addClass('hidden');
                                $('#thobe_customer_warning').removeClass('hidden');
                            }
                        } else {
                            $('#thobe_detail_body').addClass('opacity-50 pointer-events-none grayscale');
                            $('#thobe_detail_form').find('input, select, textarea').prop('disabled', true);

                            $('#finish_sale_container').removeClass('hidden');
                            $('#finish_invoice_quote_container').removeClass('hidden');
                            $('#thobe_customer_warning').addClass('hidden');
                        }
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    }

                    // Init state
                    updateThobeState($('#thobe_active_toggle').is(':checked'));

                    $('#thobe_active_toggle').change(function (e) {
                        let active = $(this).is(':checked');
                        updateThobeState(active);
                        $.post('<?= site_url("sales/toggleThobeDetail") ?>', { active: active });
                    });

                    $('#thobe_toggle_header').click(function (e) {
                        if ($(e.target).closest('.switch').length) return; // Ignore clicks on the toggle switch
                        $('#thobe_detail_body').slideToggle();
                        $('#thobe_chevron').toggleClass('rotate-180');
                    });

                    $('#save_customer_profile_btn').click(function () {
                        var btn = $(this);
                        btn.prop('disabled', true);
                        $.post('<?= site_url("sales/saveThobeCustomerProfile/" . ($customer_id ?? -1)) ?>', function (response) {
                            btn.prop('disabled', false);
                            if (response.success) {
                                $.notify({ message: response.message }, { type: 'success' });
                            } else {
                                $.notify({ message: response.message }, { type: 'danger' });
                            }
                        }, 'json');
                    });

                    $(document).on('change', '.thobe-radio-input', function () {
                        let groupName = $(this).attr('name');
                        // Reset all borders in the same group
                        $(`input[name="${groupName}"]`).closest('.thobe-radio-label')
                            .removeClass('border-emerald-500 bg-emerald-50/50')
                            .addClass('border-slate-200 bg-white');
                        // Add active border to selected
                        $(this).closest('.thobe-radio-label')
                            .removeClass('border-slate-200 bg-white')
                            .addClass('border-emerald-500 bg-emerald-50/50');
                    });

                    let thobeTimeout = null;
                    $('#thobe_detail_form input, #thobe_detail_form select, #thobe_detail_form textarea').on('change input', function () {
                        clearTimeout(thobeTimeout);
                        thobeTimeout = setTimeout(function () {
                            $.post('<?= site_url("sales/setThobeData") ?>', $('#thobe_detail_form').serialize());
                        }, 500);
                    });
                });
            </script>
            <style>
                .switch {
                    position: relative;
                    display: inline-block;
                    width: 34px;
                    height: 20px;
                }

                .switch input {
                    opacity: 0;
                    width: 0;
                    height: 0;
                }

                .slider {
                    position: absolute;
                    cursor: pointer;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: #cbd5e1;
                    transition: .4s;
                }

                .slider:before {
                    position: absolute;
                    content: "";
                    height: 16px;
                    width: 16px;
                    left: 2px;
                    bottom: 2px;
                    background-color: white;
                    transition: .4s;
                }

                input:checked+.slider {
                    background-color: #059669;
                }

                input:checked+.slider:before {
                    transform: translateX(14px);
                }

                .slider.round {
                    border-radius: 20px;
                }

                .slider.round:before {
                    border-radius: 50%;
                }

                #thobe_toggle_header .rotate-180 {
                    transform: rotate(180deg);
                }
            </style>
        <?php endif; ?>
    </div> <!-- End Left Column -->

    <!-- Overall Sale -->

    <div class="flex flex-col gap-3">
        <!-- Customer Section -->
        <div class="register-card">
            <?= form_open("$controller_name/selectCustomer", ['id' => 'select_customer_form', 'class' => 'space-y-2']) ?>
            <div class="flex items-center justify-between mb-1">
                <div class="flex items-center gap-1.5 text-slate-400">
                    <i data-lucide="users" class="w-3.5 h-3.5"></i>
                    <span
                        class="text-sm font-bold uppercase tracking-wider"><?= lang(ucfirst($controller_name) . '.customer') ?></span>
                </div>
                <?php if (isset($customer)) { ?>
                    <?= anchor(
                        "$controller_name/removeCustomer",
                        '<i data-lucide="user-minus" class="w-4 h-4"></i>',
                        ['class' => 'text-rose-500 hover:text-rose-700 transition-colors', 'id' => 'remove_customer_button', 'title' => lang('Common.remove') . ' ' . lang('Customers.customer')]
                    ) ?>
                <?php } ?>
            </div>

            <?php if (isset($customer)) { ?>
                <div class="space-y-2 pt-1 border-t border-slate-100">
                    <div class="flex flex-col gap-2 p-3 rounded-lg bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between w-full">
                            <span
                                class="text-sm font-bold text-slate-900 truncate"><?= anchor("customers/view/$customer_id", $customer, ['class' => 'modal-dlg hover:text-emerald-600 transition-colors', 'data-btn-submit' => lang('Common.submit'), 'title' => lang('Customers.update')]) ?></span>
                            <?= anchor("customers/view/$customer_id", '<i data-lucide="edit-2" class="w-3.5 h-3.5 me-1"></i> ' . lang('Common.edit'), ['class' => 'btn btn-sm btn-outline-secondary modal-dlg inline-flex items-center', 'data-btn-submit' => lang('Common.submit'), 'title' => lang('Customers.update')]) ?>
                        </div>

                        <?php if (!empty($customer_phone) || !empty($customer_email)) { ?>
                            <div class="flex flex-col gap-1 text-xs text-slate-500">
                                <?php if (!empty($customer_phone)) { ?>
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                        <span><?= esc($customer_phone) ?></span>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($customer_email)) { ?>
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                        <span><?= esc($customer_email) ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                            <span
                                class="text-slate-500 block"><?= lang(ucfirst($controller_name) . '.customer_discount') ?></span>
                            <span
                                class="font-bold text-slate-900"><?= ($customer_discount_type == FIXED) ? to_currency($customer_discount) : $customer_discount . '%' ?></span>
                        </div>
                        <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                            <span
                                class="text-slate-500 block"><?= lang(ucfirst($controller_name) . '.customer_total') ?></span>
                            <span class="font-bold text-slate-900"><?= to_currency($customer_total) ?></span>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="space-y-2">
                    <button type="button" id="open-customer-dialog"
                        class="btn btn-default w-full inline-flex items-center justify-center gap-2 h-9 text-sm">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        <?= lang('Sales.select_customer_dialog') ?>
                    </button>
                    <div class="flex gap-2 mt-2">
                        <button
                            class="btn btn-emerald-soft w-full modal-dlg inline-flex items-center justify-center gap-1.5 h-8 text-sm"
                            data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= "customers/view" ?>"
                            title="<?= lang(ucfirst($controller_name) . ".new_customer") ?>">
                            <i data-lucide="user-plus" class="w-3 h-3"></i>
                            <?= lang(ucfirst($controller_name) . ".new_customer") ?>
                        </button>
                    </div>
                </div>
            <?php } ?>
            <?= form_close() ?>
        </div> <!-- End Customer Section -->



        <!-- Summary Section -->
        <div class="register-card">
            <div class="register-summary">
                <div class="summary-row">
                    <span
                        class="text-slate-500"><?= lang(ucfirst($controller_name) . '.quantity_of_items', [$item_count]) ?></span>
                    <span class="font-semibold text-slate-900"><?= $total_units ?></span>
                </div>
                <div class="summary-row">
                    <span class="text-slate-500"><?= lang(ucfirst($controller_name) . '.sub_total') ?></span>
                    <span class="font-semibold text-slate-900"><?= to_currency($subtotal) ?></span>
                </div>
                <?php foreach ($taxes as $tax_group_index => $tax) { ?>
                    <div class="summary-row border-none !py-0.5">
                        <span
                            class="text-slate-500 text-sm"><?= (float) $tax['tax_rate'] . '% ' . $tax['tax_group'] ?></span>
                        <span
                            class="font-semibold text-slate-900 text-sm"><?= to_currency_tax($tax['sale_tax_amount']) ?></span>
                    </div>
                <?php } ?>
                <div class="summary-row total !py-0.5 border-t border-slate-100 mt-1">
                    <span class="text-sm font-bold"><?= lang(ucfirst($controller_name) . '.total') ?></span>
                    <span id="sale_total" class="text-emerald-600 font-bold text-sm"><?= to_currency($total) ?></span>
                </div>

                <?php if (count($cart) > 0) { ?>
                    <div class="summary-row !py-0.5">
                        <span
                            class="text-slate-500 text-sm"><?= lang(ucfirst($controller_name) . '.payments_total') ?></span>
                        <span class="font-semibold text-slate-900 text-sm"><?= to_currency($payments_total) ?></span>
                    </div>
                    <div class="summary-row total due !py-0.5 border-t border-slate-100 mt-1">
                        <span
                            class="text-rose-500 text-sm font-bold"><?= lang(ucfirst($controller_name) . '.amount_due') ?></span>
                        <span id="sale_amount_due"
                            class="text-rose-600 font-bold text-sm"><?= to_currency($amount_due) ?></span>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- Payment Section -->
        <?php if (count($cart) > 0) { ?>
            <div class="register-card">
                <div id="payment_details" class="space-y-2">
                    <?php if ($payments_cover_total) { ?>
                        <div
                            class="p-2 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center gap-2 text-emerald-700">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            <span
                                class="text-sm font-semibold"><?= lang(ucfirst($controller_name) . '.payment_received') ?></span>
                        </div>
                    <?php } else { ?>
                        <?= form_open("$controller_name/addPayment", ['id' => 'add_payment_form', 'class' => 'space-y-3']) ?>
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="space-y-1">
                                <label
                                    class="text-sm font-bold text-slate-500 uppercase"><?= lang(ucfirst($controller_name) . '.payment_type') ?></label>
                                <?= form_dropdown('payment_type', $payment_options, $selected_payment_type, ['id' => 'payment_types', 'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400']) ?>
                            </div>
                            <div class="space-y-1">
                                <label
                                    class="text-sm font-bold text-slate-500 uppercase"><?= lang(ucfirst($controller_name) . '.amount') ?></label>
                                <?= form_input(['name' => 'amount_tendered', 'id' => 'amount_tendered', 'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-1 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-slate-400 non-giftcard-input', 'value' => to_currency_no_money($amount_due), 'onClick' => 'this.select();']) ?>
                                <?= form_input(['name' => 'amount_tendered', 'id' => 'amount_tendered', 'class' => 'hidden giftcard-input', 'disabled' => true, 'value' => to_currency_no_money($amount_due)]) ?>
                            </div>
                        </div>
                        <button type="button"
                            class="btn btn-primary action-btn w-full inline-flex items-center justify-center gap-2"
                            id="add_payment_button">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <?= lang(ucfirst($controller_name) . '.add_payment') ?>
                        </button>
                        <?= form_close() ?>
                    <?php } ?>

                    <?php if (count($payments) > 0) { ?>
                        <div class="space-y-1.5 mt-2">
                            <?php foreach ($payments as $payment_id => $payment) { ?>
                                <div
                                    class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-100 group">
                                    <div class="flex items-center gap-2">
                                        <?= anchor("$controller_name/deletePayment/" . base64_encode($payment_id), '<i data-lucide="x-circle" class="w-3.5 h-3.5 text-slate-400 hover:text-rose-500 transition-colors"></i>') ?>
                                        <span class="text-sm font-semibold text-slate-700"><?= $payment['payment_type'] ?></span>
                                    </div>
                                    <span
                                        class="text-sm font-bold text-slate-900"><?= to_currency($payment['payment_amount']) ?></span>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>

                <!-- Final Actions -->
                <div>
                    <?php
                    $thobe_active = $thobe_active ?? false;
                    $hide_complete_for_thobe = ($thobe_active && !isset($customer));

                    // Only show this part if in sale or return mode
                    if ($pos_mode && $payments_cover_total) {
                        $due_payment = false;

                        if (count($payments) > 0) {
                            foreach ($payments as $payment_id => $payment) {
                                if ($payment['payment_type'] == lang(ucfirst($controller_name) . '.due')) {
                                    $due_payment = true;
                                }
                            }
                        }
                        ?>
                        <div id="finish_sale_container" class="<?= ($hide_complete_for_thobe) ? 'hidden' : '' ?>">
                            <?php if (!$due_payment || ($due_payment && isset($customer))) { ?>
                                <button class="btn btn-success action-btn mb-2 w-full inline-flex items-center justify-center gap-2"
                                    id="finish_sale_button" tabindex="<?= ++$tabindex ?>">
                                    <i data-lucide="check-check" class="w-5 h-5"></i>
                                    <?= lang(ucfirst($controller_name) . '.complete_sale') ?>
                                </button>
                            <?php } ?>
                        </div>
                        <div id="thobe_customer_warning"
                            class="alert alert-danger text-center p-2 mb-2 text-sm font-semibold <?= ($hide_complete_for_thobe) ? '' : 'hidden' ?>">
                            <i data-lucide="alert-circle" class="w-4 h-4 inline me-1"></i>
                            <?= lang('Thobe.customer_required') ?>
                        </div>
                        <?php
                    } elseif (!$pos_mode && isset($customer)) { ?>
                        <div id="finish_invoice_quote_container" class="<?= ($hide_complete_for_thobe) ? 'hidden' : '' ?>">
                            <button class="btn btn-success action-btn mb-2 w-full inline-flex items-center justify-center gap-2"
                                id="finish_invoice_quote_button">
                                <i data-lucide="file-check" class="w-5 h-5"></i>
                                <?= esc($mode_label) ?>
                            </button>
                        </div>
                    <?php } ?>

                    <?= form_open("$controller_name/cancel", ['id' => 'buttons_form']) ?>
                    <div class="grid grid-cols-2 gap-3 mb-4" id="buttons_sale">
                        <div class="btn btn-default action-btn w-full inline-flex items-center justify-center gap-2"
                            id="suspend_sale_button">
                            <i data-lucide="pause-circle" class="w-4 h-4"></i>
                            <?= lang(ucfirst($controller_name) . '.suspend_sale') ?>
                        </div>
                        <div class="btn bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-100 hover:border-rose-200 transition-all action-btn w-full inline-flex items-center justify-center gap-2"
                            id="cancel_sale_button">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            <?= lang(ucfirst($controller_name) . '.cancel_sale') ?>
                        </div>
                    </div>
                    <?= form_close() ?>

                    <!-- Comments & Settings -->
                    <div class="mt-4 space-y-3">
                        <div class="space-y-1">
                            <?= form_label(lang('Common.comments'), 'comments', ['class' => 'text-sm font-bold text-slate-500 uppercase']) ?>
                            <?= form_textarea(['name' => 'comment', 'id' => 'comment', 'class' => 'flex w-full rounded-md border border-slate-300 bg-white px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400', 'value' => $comment, 'rows' => '1']) ?>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <label
                                class="flex items-center gap-1.5 cursor-pointer p-1.5 rounded-lg border border-slate-100 bg-slate-50/50 hover:bg-slate-100 transition-colors">
                                <?= form_checkbox(['name' => 'sales_print_after_sale', 'id' => 'sales_print_after_sale', 'class' => 'w-3.5 h-3.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500', 'value' => 1, 'checked' => $print_after_sale]) ?>
                                <span
                                    class="text-sm font-semibold text-slate-600"><?= lang(ucfirst($controller_name) . '.print_after_sale') ?></span>
                            </label>

                            <?php if (!empty($customer_email)) { ?>
                                <label
                                    class="flex items-center gap-2 cursor-pointer p-2 rounded-lg border border-slate-100 bg-slate-50/50 hover:bg-slate-100 transition-colors">
                                    <?= form_checkbox(['name' => 'email_receipt', 'id' => 'email_receipt', 'class' => 'w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500', 'value' => 1, 'checked' => $email_receipt]) ?>
                                    <span
                                        class="text-sm font-semibold text-slate-600"><?= lang(ucfirst($controller_name) . '.email_receipt') ?></span>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div> <!-- End Register Layout Grid -->

<!-- Floating Buttons -->
<button class="floating-clear-selection" id="floating-clear-selection">
    <span><?= lang('Sales.clear_cart') ?></span>
</button>

<button class="floating-add-cart" id="floating-add-cart">
    <span><?= lang('Sales.add_to_cart') ?></span>
    <span class="selection-badge" id="selection-count">0</span>
</button>

<script type="text/javascript">
    $(document).ready(function () {
        // Fix fixed position container context after page slide animation completes
        setTimeout(function () {
            $('main.page-container, .register-layout').removeClass('animate-page-entry');
        }, 600);

        const redirect = function () {
            window.location.href = "<?= site_url('sales'); ?>";
        };

        $("#remove_customer_button").click(function () {
            $.post("<?= site_url('sales/removeCustomer'); ?>", redirect);
        });

        $(".delete_item_button").click(function () {
            const item_id = $(this).data('item-id');
            $.post("<?= site_url('sales/deleteItem/'); ?>" + item_id, redirect);
        });

        $(".delete_payment_button").click(function () {
            const item_id = $(this).data('payment-id');
            $.post("<?= site_url('sales/deletePayment/'); ?>" + item_id, redirect);
        });

        $("input[name='item_number']").change(function () {
            var item_id = $(this).parents('tr').find("input[name='item_id']").val();
            var item_number = $(this).val();
            $.ajax({
                url: "<?= site_url('sales/changeItemNumber') ?>",
                method: 'post',
                data: {
                    'item_id': item_id,
                    'item_number': item_number,
                },
                dataType: 'json'
            });
        });

        $("input[name='name']").change(function () {
            var item_id = $(this).parents('tr').find("input[name='item_id']").val();
            var item_name = $(this).val();
            $.ajax({
                url: "<?= site_url('sales/changeItemName') ?>",
                method: 'post',
                data: {
                    'item_id': item_id,
                    'item_name': item_name,
                },
                dataType: 'json'
            });
        });

        $("input[name='item_description']").change(function () {
            var item_id = $(this).parents('tr').find("input[name='item_id']").val();
            var item_description = $(this).val();
            $.ajax({
                url: "<?= site_url('sales/changeItemDescription') ?>",
                method: 'post',
                data: {
                    'item_id': item_id,
                    'item_description': item_description,
                },
                dataType: 'json'
            });
        });

        $('#item').focus();

        // $('#item').blur(function () {
        //     $(this).val("<?= lang(ucfirst($controller_name) . '.start_typing_item_name') ?>");
        // });

        $('#item').autocomplete({
            source: "<?= esc("$controller_name/itemSearch") ?>",
            minChars: 0,
            autoFocus: false,
            delay: 500,
            select: function (a, ui) {
                $(this).val(ui.item.value);
                $('#add_item_form').submit();
                return false;
            }
        });

        $('#item').keypress(function (e) {
            if (e.which == 13) {
                $('#add_item_form').submit();
                return false;
            }
        });

        var clear_fields = function () {
            if ($(this).val().match("<?= lang(ucfirst($controller_name) . '.start_typing_item_name') . '|' . lang(ucfirst($controller_name) . '.start_typing_customer_name') ?>")) {
                $(this).val('');
            }
        };

        $('#item, #customer').click(clear_fields).dblclick(function (event) {
            $(this).autocomplete('search');
        });

        $('#customer').blur(function () {
            $(this).val("<?= lang(ucfirst($controller_name) . '.start_typing_customer_name') ?>");
        });

        $('#customer').autocomplete({
            source: "<?= site_url('customers/suggest') ?>",
            minChars: 0,
            delay: 10,
            select: function (a, ui) {
                $(this).val(ui.item.value);
                $('#select_customer_form').submit();
                return false;
            }
        });

        $('#customer').keypress(function (e) {
            if (e.which == 13) {
                $('#select_customer_form').submit();
                return false;
            }
        });

        $('.giftcard-input').autocomplete({
            source: "<?= site_url('giftcards/suggest') ?>",
            minChars: 0,
            delay: 10,
            select: function (a, ui) {
                $(this).val(ui.item.value);
                $('#add_payment_form').submit();
                return false;
            }
        });

        $('#comment').keyup(function () {
            $.post("<?= esc(site_url("$controller_name/setComment")) ?>", {
                comment: $('#comment').val()
            });
        });

        <?php if ($config['invoice_enable']) { ?>
            $('#sales_invoice_number').keyup(function () {
                $.post("<?= esc(site_url("$controller_name/setInvoiceNumber")) ?>", {
                    sales_invoice_number: $('#sales_invoice_number').val()
                });
            });

        <?php } ?>

        $('#sales_print_after_sale').change(function () {
            $.post("<?= esc(site_url("$controller_name/setPrintAfterSale")) ?>", {
                sales_print_after_sale: $(this).is(':checked')
            });
        });

        $('#price_work_orders').change(function () {
            $.post("<?= esc(site_url("$controller_name/setPriceWorkOrders")) ?>", {
                price_work_orders: $(this).is(':checked')
            });
        });

        $('#email_receipt').change(function () {
            $.post("<?= esc(site_url("$controller_name/setEmailReceipt")) ?>", {
                email_receipt: $(this).is(':checked')
            });
        });

        $('#finish_sale_button').click(function () {
            $('#buttons_form').attr('action', "<?= "$controller_name/complete" ?>");
            $('#buttons_form').submit();
        });

        $('#finish_invoice_quote_button').click(function () {
            $('#buttons_form').attr('action', "<?= "$controller_name/complete" ?>");
            $('#buttons_form').submit();
        });

        $('#suspend_sale_button').click(function () {
            $('#buttons_form').attr('action', "<?= site_url("$controller_name/suspend") ?>");
            $('#buttons_form').submit();
        });

        $('#cancel_sale_button').click(function () {
            if (confirm("<?= lang(ucfirst($controller_name) . '.confirm_cancel_sale') ?>")) {
                $('#buttons_form').attr('action', "<?= site_url("$controller_name/cancel") ?>");
                $('#buttons_form').submit();
            }
        });

        $('#add_payment_button').click(function () {
            $('#add_payment_form').submit();
        });

        $('#payment_types').change(check_payment_type).ready(check_payment_type);

        $('#cart_contents input').keypress(function (event) {
            if (event.which == 13) {
                $(this).parents('tr').prevAll('form:first').submit();
            }
        });

        $('#amount_tendered').keypress(function (event) {
            if (event.which == 13) {
                $('#add_payment_form').submit();
            }
        });

        $('#finish_sale_button').keypress(function (event) {
            if (event.which == 13) {
                $('#finish_sale_form').submit();
            }
        });

        dialog_support.init('a.modal-dlg, button.modal-dlg');

        table_support.handle_submit = function (resource, response, stay_open) {
            $.notify({
                message: response.message
            }, {
                type: response.success ? 'success' : 'danger'
            })

            if (response.success) {
                if (resource.match(/customers$/)) {
                    $('#customer').val(response.id);
                    $('#select_customer_form').submit();
                } else {
                    var $stock_location = $("select[name='stock_location']").val();
                    $('#item_location').val($stock_location);
                    $('#item').val(response.id);
                    if (stay_open) {
                        $('#add_item_form').ajaxSubmit();
                    } else {
                        $('#add_item_form').submit();
                    }
                }
            }
        }

        $('[name="price"],[name="quantity"],[name="discount"],[name="description"],[name="serialnumber"],[name="discounted_total"]').change(function () {
            $(this).parents('tr').prevAll('form:first').submit()
        });

        $('[name="discount_toggle"]').change(function () {
            var input = $('<input>').attr('type', 'hidden').attr('name', 'discount_type').val(($(this).prop('checked')) ? 1 : 0);
            $('#cart_' + $(this).attr('data-line')).append($(input));
            $('#cart_' + $(this).attr('data-line')).submit();
        });
    });

    function check_payment_type() {
        var cash_mode = <?= json_encode($cash_mode) ?>;

        if ($("#payment_types").val() == "<?= lang(ucfirst($controller_name) . '.giftcard') ?>") {
            $("#sale_total").html("<?= to_currency($total) ?>");
            $("#sale_amount_due").html("<?= to_currency($amount_due) ?>");
            $("#amount_tendered_label").html("<?= lang(ucfirst($controller_name) . '.giftcard_number') ?>");
            $("#amount_tendered:enabled").val('').focus();
            $(".giftcard-input").attr('disabled', false);
            $(".non-giftcard-input").attr('disabled', true);
            $(".giftcard-input:enabled").val('').focus();
        } else if (($("#payment_types").val() == "<?= lang(ucfirst($controller_name) . '.cash') ?>" && cash_mode == '1')) {
            $("#sale_total").html("<?= to_currency($non_cash_total) ?>");
            $("#sale_amount_due").html("<?= to_currency($cash_amount_due) ?>");
            $("#amount_tendered_label").html("<?= lang(ucfirst($controller_name) . '.amount_tendered') ?>");
            $("#amount_tendered:enabled").val("<?= to_currency_no_money($cash_amount_due) ?>");
            $(".giftcard-input").attr('disabled', true);
            $(".non-giftcard-input").attr('disabled', false);
        } else {
            $("#sale_total").html("<?= to_currency($non_cash_total) ?>");
            $("#sale_amount_due").html("<?= to_currency($amount_due) ?>");
            $("#amount_tendered_label").html("<?= lang(ucfirst($controller_name) . '.amount_tendered') ?>");
            $("#amount_tendered:enabled").val("<?= to_currency_no_money($amount_due) ?>");
            $(".giftcard-input").attr('disabled', true);
            $(".non-giftcard-input").attr('disabled', false);
        }
    }

    // ===== Product Grid Functionality =====
    let currentCategory = null;
    let selectedItems = []; // Track selected item IDs
    let allCategoriesData = []; // Store all categories and items

    // Load all categories and items at once on page load
    function loadAllData() {
        showLoading();

        $.ajax({
            url: '<?= site_url('sales/allCategoriesWithItems') ?>',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                hideLoading();
                allCategoriesData = data;

                if (data.length === 0) {
                    showEmpty();
                } else {
                    displayCategories();
                }
            },
            error: function () {
                hideLoading();
                showEmpty();
                console.error('Failed to load categories and items');
            }
        });
    }

    // Display categories in grid (from cached data)
    function displayCategories() {
        const $grid = $('#category-grid');
        $grid.empty().removeClass('hidden');
        $('#product-grid').addClass('hidden');
        $('#back-to-categories').addClass('hidden');
        $('#grid-title').text('<?= lang('Sales.product_categories') ?>');
        currentCategory = null;

        allCategoriesData.forEach(function (category) {
            const $wrapper = $('<div>').addClass('category-wrapper');

            const $card = $('<div>')
                .addClass('category-card')
                .attr('data-category', category.name);

            const $content = $(`
                <div class="category-content">
                    <div class="category-name">
                        <i data-lucide="folder" class="w-5 h-5"></i>
                        <div class="category-count"> (${category.count}) </div>
                        <span>${escapeHtml(category.name)}</span>
                    </div>
                </div>
            `);

            $card.append($content);

            const $editBtn = $(`
                <button class="category-edit-btn" data-category="${escapeHtml(category.name)}" title="<?= lang('Sales.rename_category') ?>">
                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                </button>
            `);

            // Category selection on card click
            $card.on('click', function () {
                selectCategory(category.name);
            });

            // Edit button click handler
            $editBtn.on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                openRenameCategoryDialog(category.name);
            });

            $wrapper.append($card, $editBtn);
            $grid.append($wrapper);
        });

        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Select a category and display its products (from cached data)
    function selectCategory(categoryName) {
        currentCategory = categoryName;

        // Find the category in cached data
        const category = allCategoriesData.find(cat => cat.name === categoryName);

        if (!category || category.items.length === 0) {
            showEmpty();
            return;
        }

        displayProducts(category.items, categoryName);
    }

    // Display products in grid (from cached data)
    function displayProducts(items, categoryName) {
        const $grid = $('#product-grid');
        $grid.empty().removeClass('hidden');
        $('#category-grid').addClass('hidden');
        $('#back-to-categories').removeClass('hidden');
        $('#grid-title').text(escapeHtml(categoryName));

        items.forEach(function (item) {
            const itemNumber = item.item_number || 'N/A';
            const isSelected = selectedItems.indexOf(item.item_id) > -1;

            const $card = $('<div>')
                .addClass('product-card')
                .attr('data-item-id', item.item_id);

            const $content = $(`
                <div class="product-content">
                    <div class="product-name">${escapeHtml(item.name)}</div>
                    <div class="product-details">
                        <span class="product-price">${item.price}</span>
                    </div>
                </div>
                <div class="product-actions">
                    <button class="product-edit-btn" data-item-id="${item.item_id}" title="<?= lang('Sales.edit_item') ?>">
                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                    </button>
                    <button class="product-delete-btn" data-item-id="${item.item_id}" title="<?= lang('Sales.delete_item') ?>">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            `);

            $card.append($content);

            // Item selection on content click (but not on action buttons)
            $card.find('.product-content').on('click', function () {
                toggleItemSelection(item.item_id, $card);
            });

            // Edit button click handler
            $card.find('.product-edit-btn').on('click', function (e) {
                e.stopPropagation();
                openEditItemDialog(item.item_id);
            });

            // Delete button click handler
            $card.find('.product-delete-btn').on('click', function (e) {
                e.stopPropagation();
                deleteItemFromGrid(item.item_id);
            });

            // Restore selection state if item was previously selected
            if (isSelected) {
                $card.addClass('selected');
            }

            $grid.append($card);
        });

        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Toggle item selection
    function toggleItemSelection(itemId, $card) {
        const index = selectedItems.indexOf(itemId);

        if (index > -1) {
            // Item is already selected, remove it
            selectedItems.splice(index, 1);
            $card.removeClass('selected');
        } else {
            // Item is not selected, add it
            selectedItems.push(itemId);
            $card.addClass('selected');
        }

        updateFloatingButton();
    }

    // Open rename category dialog
    function openRenameCategoryDialog(categoryName) {
        const $dialog = $('<div>');
        const $label = $('<label>').addClass('block text-sm font-medium text-slate-700 mb-2').text('<?= lang('Sales.category_name') ?>');
        const $input = $('<input>').attr({
            'type': 'text',
            'id': 'new-category-name',
            'value': categoryName
        }).addClass('flex h-9 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400');

        $dialog.append($('<div>').addClass('space-y-4').append(
            $('<div>').append($label, $input)
        ));

        BootstrapDialog.show({
            title: '<?= lang('Sales.rename_category') ?>',
            message: $dialog,
            closeByBackdrop: false,
            closeByKeyboard: false,
            buttons: [{
                label: '<?= lang('Sales.close_dialog') ?>',
                cssClass: 'btn btn-default',
                action: function (dialog) {
                    dialog.close();
                }
            }, {
                label: '<?= lang('Common.submit') ?>',
                cssClass: 'btn btn-primary',
                action: function (dialog) {
                    const newName = $('#new-category-name').val().trim();
                    if (newName) {
                        renameCategory(categoryName, newName, dialog);
                    }
                }
            }]
        });
    }

    // Rename category via AJAX
    function renameCategory(oldName, newName, dialog) {
        $.post('<?= site_url('sales/renameCategory') ?>', {
            old_name: oldName,
            new_name: newName
        }, function (response) {
            if (response.success) {
                // Update cached data
                const category = allCategoriesData.find(cat => cat.name === oldName);
                if (category) {
                    category.name = newName;
                    category.items.forEach(item => item.category = newName);
                }

                // Refresh display
                if (currentCategory === oldName) {
                    currentCategory = newName;
                    const category = allCategoriesData.find(cat => cat.name === newName);
                    if (category) {
                        displayProducts(category.items, newName);
                    }
                } else {
                    displayCategories();
                }

                dialog.close();
                show_feedback('success', response.message, '<?= lang('Common.success') ?>');
            } else {
                show_feedback('error', response.message, '<?= lang('Common.error') ?>');
            }
        }, 'json').fail(function () {
            show_feedback('error', '<?= lang('Sales.rename_category_error') ?>', '<?= lang('Common.error') ?>');
        });
    }

    // Open edit item dialog using modal-dlg approach
    function openEditItemDialog(itemId) {
        // Create a temporary link element to trigger the modal
        const $link = $('<a>').attr({
            'href': '<?= site_url('items/view') ?>/' + itemId,
            'data-btn-submit': '<?= lang('Common.submit') ?>',
            'class': 'modal-dlg'
        }).hide();

        $('body').append($link);

        // Initialize the modal dialog on this link
        dialog_support.init($link);

        // Trigger click to open the dialog
        $link.click();

        // Remove the link after a short delay
        setTimeout(function () {
            $link.remove();
        }, 100);
    }

    // Delete item from grid
    function deleteItemFromGrid(itemId) {
        if (confirm(<?= json_encode(lang('Sales.confirm_delete_item')) ?>)) {
            $.post(<?= json_encode(site_url('items/delete')) ?>, {
                'ids': [itemId]
            }, function (response) {
                if (response.success) {
                    loadAllData();
                }
                $.notify(response.message, {
                    type: response.success ? 'success' : 'danger'
                });
            }, 'json');
        }
    }

    // Customer selection dialog
    let currentCustomerPage = 0;
    const customersPerPage = 20;
    let allCustomers = [];
    let filteredCustomers = [];

    // Initialize product grid on page load
    loadAllData();

    $('#open-customer-dialog').on('click', function () {
        openCustomerSelectionDialog();
    });

    function openCustomerSelectionDialog() {
        const $dialog = $('<div>').addClass('customer-selection-dialog');

        const $searchInput = $('<input>').attr({
            'type': 'text',
            'id': 'customer-search',
            'placeholder': <?= json_encode(lang('Sales.search_customers')) ?>
        }).addClass('flex h-9 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400');

        const $customerList = $('<div>').attr('id', 'customer-list').addClass('space-y-2').css({
            'max-height': '400px',
            'overflow-y': 'auto'
        }).html('<div class="text-center py-4"><div class="spinner-border" role="status"></div></div>');

        const $pagination = $('<div>').attr('id', 'customer-pagination').addClass('flex justify-between items-center mt-4 pt-4 border-t');
        const $prevBtn = $('<button>').attr('id', 'prev-customers').addClass('btn btn-default btn-sm flex flex-row gap-2 justify-center items-center').prop('disabled', true)
            .html('<i data-lucide="chevron-left" class="w-4 h-4"></i> Previous');
        const $pageInfo = $('<span>').attr('id', 'customer-page-info');
        const $nextBtn = $('<button>').attr('id', 'next-customers').addClass('btn btn-default btn-sm flex flex-row gap-2 justify-center items-center')
            .html('Next <i data-lucide="chevron-right" class="w-4 h-4"></i>');

        $pagination.append($prevBtn, $pageInfo, $nextBtn);
        $dialog.append(
            $('<div>').addClass('mb-4').append($searchInput),
            $customerList,
            $pagination
        );

        const dialog = BootstrapDialog.show({
            title: <?= json_encode(lang('Sales.select_customer_dialog')) ?>,
            message: $dialog,
            size: BootstrapDialog.SIZE_WIDE,
            closeByBackdrop: false,
            closeByKeyboard: false,
            closable: false,
            buttons: [{
                label: '<?= lang('Sales.close_dialog') ?>',
                cssClass: 'btn btn-default',
                action: function (dialog) {
                    dialog.close();
                }
            }],
            onshown: function () {
                loadCustomers(0, '');

                // Search functionality
                $('#customer-search').on('input', function () {
                    const search = $(this).val();
                    currentCustomerPage = 0;
                    loadCustomers(0, search);
                });

                // Pagination
                $('#prev-customers').on('click', function () {
                    if (currentCustomerPage > 0) {
                        currentCustomerPage--;
                        loadCustomers(currentCustomerPage, $('#customer-search').val());
                    }
                });

                $('#next-customers').on('click', function () {
                    currentCustomerPage++;
                    loadCustomers(currentCustomerPage, $('#customer-search').val());
                });
            }
        });

        window.customerDialog = dialog;
    }

    function loadCustomers(page, search) {
        const offset = page * customersPerPage;
        const $list = $('#customer-list');

        // Show loading state
        $list.html(`
            <div class="text-center py-8">
                <div class="spinner-border text-emerald-500" role="status"></div>
                <div class="mt-2 text-slate-500"><?= lang('Common.loading') ?>...</div>
            </div>
        `);

        $.get('<?= site_url('sales/customers') ?>', {
            search: search,
            limit: customersPerPage,
            offset: offset
        }, function (response) {
            $list.empty();

            if (response.customers.length === 0) {
                $list.html(`
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 mb-3">
                            <i data-lucide="search" class="w-6 h-6 text-slate-400"></i>
                        </div>
                        <div class="text-slate-500"><?= lang('Sales.no_customers_found') ?></div>
                    </div>
                `);
            } else {
                const $table = $(`
                    <div class="overflow-x-auto">
                        <table class="w-full text-start border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="py-2 px-3 text-sm font-semibold text-slate-600 text-start"><?= lang('Common.name') ?></th>
                                    <th class="py-2 px-3 text-sm font-semibold text-slate-600 text-start"><?= lang('Common.phone_number') ?></th>
                                    <th class="py-2 px-3 text-sm font-semibold text-slate-600 text-start"><?= lang('Common.email') ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100"></tbody>
                        </table>
                    </div>
                `);

                const $tbody = $table.find('tbody');

                response.customers.forEach(function (customer) {
                    const $row = $(`
                        <tr class="hover:bg-slate-50 cursor-pointer transition-colors" data-customer-id="${customer.person_id}">
                            <td class="py-2 px-3 text-slate-900 font-medium text-start">${escapeHtml(customer.display_name)}</td>
                            <td class="py-2 px-3 text-slate-500 text-sm text-start">${customer.phone_number ? escapeHtml(customer.phone_number) : '-'}</td>
                            <td class="py-2 px-3 text-slate-500 text-sm text-start">${customer.email ? escapeHtml(customer.email) : '-'}</td>
                        </tr>
                    `);

                    $row.on('click', function () {
                        selectCustomerFromDialog(customer.person_id);
                    });

                    $tbody.append($row);
                });

                $list.append($table);
            }

            // Update pagination
            const totalPages = Math.ceil(response.total / customersPerPage);
            $('#customer-page-info').text(`Page ${page + 1} of ${totalPages || 1}`);

            // Stylize pagination buttons
            const prevDisabled = page === 0;
            const nextDisabled = page >= totalPages - 1 || totalPages === 0;

            $('#prev-customers').prop('disabled', prevDisabled)
                .toggleClass('opacity-50 cursor-not-allowed', prevDisabled);

            $('#next-customers').prop('disabled', nextDisabled)
                .toggleClass('opacity-50 cursor-not-allowed', nextDisabled);

            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }, 'json').fail(function () {
            $list.html(`
                <div class="text-center py-8 text-rose-500">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-rose-100 mb-3">
                        <i data-lucide="alert-circle" class="w-6 h-6 text-rose-500"></i>
                    </div>
                    <div><?= lang('Common.error') ?></div>
                </div>
            `);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    }

    function selectCustomerFromDialog(customerId) {
        if (!customerId) return;

        $.post('<?= site_url('sales/selectCustomer') ?>', {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            customer: customerId
        })
            .done(function () {
                window.location.reload();
            })
            .fail(function () {
                alert('<?= lang('Sales.customer_selection_failed') ?>'); // Fallback error
            });
    }

    // Update floating button visibility and count
    function updateFloatingButton() {
        const $addButton = $('#floating-add-cart');
        const $clearButton = $('#floating-clear-selection');
        const $count = $('#selection-count');

        if (selectedItems.length > 0) {
            $addButton.addClass('show');
            $clearButton.addClass('show');
            $count.text(selectedItems.length);

            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        } else {
            $addButton.removeClass('show');
            $clearButton.removeClass('show');
        }
    }

    // Clear all selections
    function clearAllSelections() {
        selectedItems = [];
        $('.product-card').removeClass('selected');
        updateFloatingButton();
    }

    // Clear selection button click handler
    $('#floating-clear-selection').on('click', function () {
        clearAllSelections();
    });

    // Add selected items to cart
    function addSelectedItemsToCart() {
        if (selectedItems.length === 0) return;

        // Add each selected item to the cart using AJAX to avoid page reload
        let itemsAdded = 0;
        const totalItems = selectedItems.length;

        selectedItems.forEach(function (itemId) {
            $.ajax({
                url: '<?= site_url('sales/add') ?>',
                method: 'POST',
                data: { item: itemId },
                success: function () {
                    itemsAdded++;
                    if (itemsAdded === totalItems) {
                        // All items added, reload the page to update cart
                        location.reload();
                    }
                },
                error: function () {
                    console.error('Failed to add item:', itemId);
                    itemsAdded++;
                    if (itemsAdded === totalItems) {
                        location.reload();
                    }
                }
            });
        });
    }

    // Floating button click handler
    $('#floating-add-cart').on('click', function () {
        addSelectedItemsToCart();
    });

    // Back to categories button
    $('#back-to-categories').on('click', function () {
        displayCategories();
    });

    // UI State Management
    function showLoading() {
        $('#grid-loading').removeClass('hidden');
        $('#grid-empty').addClass('hidden');
        $('#category-grid').addClass('hidden');
        $('#product-grid').addClass('hidden');
    }

    function hideLoading() {
        $('#grid-loading').addClass('hidden');
    }

    function showEmpty() {
        $('#grid-empty').removeClass('hidden');
        $('#category-grid').addClass('hidden');
        $('#product-grid').addClass('hidden');

        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Utility function to escape HTML
    function escapeHtml(text) {
        // Handle null, undefined, or non-string values
        if (text === null || text === undefined) {
            return '';
        }
        // Convert to string if not already
        text = String(text);

        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function (m) { return map[m]; });
    }

    // Initialize product grid on page load
    $(document).ready(function () {
        loadAllData();
    });
    // ===== End Product Grid Functionality =====

    // Add Keyboard Shortcuts/Hotkeys to Sale Register
    document.body.onkeyup = function (e) {
        switch (event.altKey && event.keyCode) {
            case 49: // Alt + 1 Items Seach
                $("#item").focus();
                $("#item").select();
                break;
            case 50: // Alt + 2 Customers Search
                $("#customer").focus();
                $("#customer").select();
                break;
            case 51: // Alt + 3 Suspend Current Sale
                $("#suspend_sale_button").click();
                break;
            case 52: // Alt + 4 Check Suspended
                $("#show_suspended_sales_button").click();
                break;
            case 53: // Alt + 5 Edit Amount Tendered Value
                $("#amount_tendered").focus();
                $("#amount_tendered").select();
                break;
            case 54: // Alt + 6 Add Payment
                $("#add_payment_button").click();
                break;
            case 55: // Alt + 7 Add Payment and Complete Sales/Invoice
                $("#add_payment_button").click();
                window.location.href = "<?= 'sales/complete' ?>";
                break;
            case 56: // Alt + 8 Finish Quote/Invoice without payment
                $("#finish_invoice_quote_button").click();
                break;
            case 57: // Alt + 9 Open Shortcuts Help Modal
                $("#show_keyboard_help").click();
                break;
        }

        switch (event.keyCode) {
            case 27: // ESC Cancel Current Sale
                $("#cancel_sale_button").click();
                break;
        }
    }
</script>



<?= view('partial/footer') ?>