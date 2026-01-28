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
                        <label class="text-sm font-semibold text-slate-700"><?= lang(ucfirst($controller_name) . '.mode') ?>:</label>
                        <?= form_dropdown('mode', $modes, $mode, ['onchange' => "$('#mode_form').submit();", 'class' => 'flex h-8 w-fit rounded-md border border-slate-300 bg-white px-2 py-0.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400']) ?>
                    </div>
                    
                    <?php if ($config['dinner_table_enable']) { ?>
                        <div class="flex items-center gap-1.5">
                            <label class="text-sm font-semibold text-slate-700"><?= lang(ucfirst($controller_name) . '.table') ?>:</label>
                            <?= form_dropdown('dinner_table', $empty_tables, $selected_table, ['onchange' => "$('#mode_form').submit();", 'class' => 'flex h-8 w-fit rounded-md border border-slate-300 bg-white px-2 py-0.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400']) ?>
                        </div>
                    <?php } ?>

                    <?php if (count($stock_locations) > 1) { ?>
                        <div class="flex items-center gap-1.5">
                            <label class="text-sm font-semibold text-slate-700"><?= lang(ucfirst($controller_name) . '.stock_location') ?>:</label>
                            <?= form_dropdown('stock_location', $stock_locations, $stock_location, ['onchange' => "$('#mode_form').submit();", 'class' => 'flex h-8 w-fit rounded-md border border-slate-300 bg-white px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400']) ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="flex items-center gap-2">
                    <button class="btn btn-default btn-sm modal-dlg inline-flex items-center gap-2" id="show_suspended_sales_button" data-href="<?= esc("$controller_name/suspended") ?>" title="<?= lang(ucfirst($controller_name) . '.suspended_sales') ?>">
                        <i data-lucide="list" class="w-4 h-4"></i>
                        <?= lang(ucfirst($controller_name) . '.suspended_sales') ?>
                    </button>

                    <?php
                    $employee = model(Employee::class);
                    if ($employee->has_grant('reports_sales', session('person_id'))) {
                    ?>
                        <a href="<?= site_url("$controller_name/manage") ?>" class="btn btn-primary btn-sm inline-flex items-center gap-2" id="sales_takings_button">
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
                <button id="new_item_button" class="btn btn-info action-btn w-fit modal-dlg inline-flex items-center gap-2 h-10 px-4" data-btn-new="<?= lang('Common.new') ?>" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= "items/view" ?>" title="<?= lang(ucfirst($controller_name) . ".new_item") ?>">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span class="hidden sm:inline"><?= lang(ucfirst($controller_name) . ".new_item") ?></span>
                </button>
            <?= form_close() ?>
        </div>

        <!-- Cart Table inside a card -->
        <div class="register-card overflow-hidden">


    <!-- Sale Items List -->

        <table class="table" id="register">
            <thead class="bg-slate-50">
                <tr>
                    <th class="w-[5%]"><?= lang('Common.delete') ?></th>
                    <th class="w-[12%]"><?= lang(ucfirst($controller_name) . '.item_number') ?></th>
                    <th class="w-[33%]"><?= lang(ucfirst($controller_name) . '.item_name') ?></th>
                    <th class="w-[10%]"><?= lang(ucfirst($controller_name) . '.price') ?></th>
                    <th class="w-[10%] text-center"><?= lang(ucfirst($controller_name) . '.quantity') ?></th>
                    <th class="w-[15%]"><?= lang(ucfirst($controller_name) . '.discount') ?></th>
                    <th class="w-[10%] text-end"><?= lang(ucfirst($controller_name) . '.total') ?></th>
                    <th class="w-[5%] text-center"><?= lang(ucfirst($controller_name) . '.update') ?></th>
                </tr>
            </thead>

        <tbody id="cart_contents">
            <?php if (count($cart) == 0) { ?>
                <tr>
                    <td colspan="8">
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
                        <tr class="group">
                            <td class="text-center">
                                <?php
                                echo anchor("$controller_name/deleteItem/$line", '<i data-lucide="trash-2" class="w-4 h-4 text-rose-500 hover:text-rose-700 transition-colors"></i>');
                                echo form_hidden('location', (string)$item['item_location']);
                                echo form_input(['type' => 'hidden', 'name' => 'item_id', 'value' => $item['item_id']]);
                                ?>
                            </td>
                            <?php if ($item['item_type'] == ITEM_TEMP) { ?>
                                <td><?= form_input(['name' => 'item_number', 'id' => 'item_number', 'class' => 'form-control input-sm', 'value' => $item['item_number'], 'tabindex' => ++$tabindex]) ?></td>
                                <td style="align: center;">
                                    <?= form_input(['name' => 'name', 'id' => 'name', 'class' => 'form-control input-sm', 'value' => $item['name'], 'tabindex' => ++$tabindex]) ?>
                                </td>
                            <?php } else { ?>
                                <td><?= esc($item['item_number']) ?></td>
                                <td style="align: center;">
                                    <?= esc($item['name']) . ' ' . implode(' ', [$item['attribute_values'], $item['attribute_dtvalues']]) ?>
                                    <br>
                                    <?php if ($item['stock_type'] == '0'): echo '[' . to_quantity_decimals($item['in_stock']) . ' in ' . $item['stock_name'] . ']';
                                    endif; ?>
                                </td>
                            <?php } ?>

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
                                <div class="input-group">
                                    <?= form_input(['name' => 'discount', 'class' => 'form-control input-sm', 'value' => $item['discount_type'] ? to_currency_no_money($item['discount']) : to_decimals($item['discount']), 'tabindex' => ++$tabindex, 'onClick' => 'this.select();']) ?>
                                    <span class="input-group-btn">
                                        <?= form_checkbox(['id' => 'discount_toggle', 'name' => 'discount_toggle', 'value' => 1, 'data-toggle' => "toggle", 'data-size' => 'small', 'data-onstyle' => 'success', 'data-on' => '<b>' . $config['currency_symbol'] . '</b>', 'data-off' => '<b>%</b>', 'data-line' => $line, 'checked' => $item['discount_type'] == 1]) ?>
                                    </span>
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
                                <a href="javascript:document.getElementById('<?= "cart_$line" ?>').submit();" class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-colors" title="<?= lang(ucfirst($controller_name) . '.update') ?>">
                                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <?php if ($item['item_type'] == ITEM_TEMP) { ?>
                                <td><?= form_input(['type' => 'hidden', 'name' => 'item_id', 'value' => $item['item_id']]) ?></td>
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
    </div> <!-- End Left Column -->

<!-- Overall Sale -->

    <div class="flex flex-col gap-3">
        <!-- Customer Section -->
        <div class="register-card">
            <?= form_open("$controller_name/selectCustomer", ['id' => 'select_customer_form', 'class' => 'space-y-2']) ?>
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-1.5 text-slate-400">
                        <i data-lucide="users" class="w-3.5 h-3.5"></i>
                        <span class="text-sm font-bold uppercase tracking-wider"><?= lang(ucfirst($controller_name) . '.customer') ?></span>
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
                        <div class="flex items-center gap-2 p-2 rounded-lg bg-slate-50 border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-slate-900 flex items-center justify-center text-white font-bold text-sm">
                                <?= substr(esc($customer), 0, 1) ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-bold text-slate-900 truncate">
                                    <?= anchor("customers/view/$customer_id", $customer, ['class' => 'modal-dlg hover:text-emerald-600 transition-colors', 'data-btn-submit' => lang('Common.submit'), 'title' => lang('Customers.update')]) ?>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                                <span class="text-slate-500 block"><?= lang(ucfirst($controller_name) . '.customer_discount') ?></span>
                                <span class="font-bold text-slate-900"><?= ($customer_discount_type == FIXED) ? to_currency($customer_discount) : $customer_discount . '%' ?></span>
                            </div>
                            <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                                <span class="text-slate-500 block"><?= lang(ucfirst($controller_name) . '.customer_total') ?></span>
                                <span class="font-bold text-slate-900"><?= to_currency($customer_total) ?></span>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="space-y-2">
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                                <i data-lucide="user" class="w-3.5 h-3.5"></i>
                            </div>
                            <?= form_input([
                                'name' => 'customer', 
                                'id' => 'customer', 
                                'class' => 'flex h-9 w-full rounded-lg border border-slate-300 bg-white ps-10 pe-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400',
                                'placeholder' => lang(ucfirst($controller_name) . '.start_typing_customer_name')
                            ]) ?>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn btn-emerald-soft flex-1 modal-dlg inline-flex items-center justify-center gap-1.5 h-8 text-sm" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= "customers/view" ?>" title="<?= lang(ucfirst($controller_name) . ".new_customer") ?>">
                                <i data-lucide="user-plus" class="w-3 h-3"></i>
                                <?= lang(ucfirst($controller_name) . ".new_customer") ?>
                            </button>
                            <button class="btn btn-default flex-1 modal-dlg inline-flex items-center justify-center gap-1.5 h-8 text-sm" id="show_keyboard_help" data-href="<?= esc("$controller_name/salesKeyboardHelp") ?>" title="<?= lang(ucfirst($controller_name) . '.key_title') ?>">
                                <i data-lucide="keyboard" class="w-3 h-3"></i>
                                <?= lang(ucfirst($controller_name) . '.key_help') ?>
                            </button>
                        </div>
                    </div>
                <?php } ?>
            <?= form_close() ?>
        </div>

        <!-- Summary Section -->
        <div class="register-card">
            <div class="register-summary">
                <div class="summary-row">
                    <span class="text-slate-500"><?= lang(ucfirst($controller_name) . '.quantity_of_items', [$item_count]) ?></span>
                    <span class="font-semibold text-slate-900"><?= $total_units ?></span>
                </div>
                <div class="summary-row">
                    <span class="text-slate-500"><?= lang(ucfirst($controller_name) . '.sub_total') ?></span>
                    <span class="font-semibold text-slate-900"><?= to_currency($subtotal) ?></span>
                </div>
                <?php foreach ($taxes as $tax_group_index => $tax) { ?>
                    <div class="summary-row border-none !py-0.5">
                        <span class="text-slate-500 text-sm"><?= (float)$tax['tax_rate'] . '% ' . $tax['tax_group'] ?></span>
                        <span class="font-semibold text-slate-900 text-sm"><?= to_currency_tax($tax['sale_tax_amount']) ?></span>
                    </div>
                <?php } ?>
                <div class="summary-row total !py-0.5 border-t border-slate-100 mt-1">
                    <span class="text-sm font-bold"><?= lang(ucfirst($controller_name) . '.total') ?></span>
                    <span id="sale_total" class="text-emerald-600 font-bold text-sm"><?= to_currency($total) ?></span>
                </div>

                <?php if (count($cart) > 0) { ?>
                    <div class="summary-row !py-0.5">
                        <span class="text-slate-500 text-sm"><?= lang(ucfirst($controller_name) . '.payments_total') ?></span>
                        <span class="font-semibold text-slate-900 text-sm"><?= to_currency($payments_total) ?></span>
                    </div>
                    <div class="summary-row total due !py-0.5 border-t border-slate-100 mt-1">
                        <span class="text-rose-500 text-sm font-bold"><?= lang(ucfirst($controller_name) . '.amount_due') ?></span>
                        <span id="sale_amount_due" class="text-rose-600 font-bold text-sm"><?= to_currency($amount_due) ?></span>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- Payment Section -->
        <?php if (count($cart) > 0) { ?>
            <div class="register-card">
                <div id="payment_details" class="space-y-2">
                    <?php if ($payments_cover_total) { ?>
                        <div class="p-2 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center gap-2 text-emerald-700">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            <span class="text-sm font-semibold"><?= lang(ucfirst($controller_name) . '.payment_received') ?></span>
                        </div>
                    <?php } else { ?>
                        <?= form_open("$controller_name/addPayment", ['id' => 'add_payment_form', 'class' => 'space-y-3']) ?>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-sm font-bold text-slate-500 uppercase"><?= lang(ucfirst($controller_name) . '.payment_type') ?></label>
                                    <?= form_dropdown('payment_type', $payment_options,  $selected_payment_type, ['id' => 'payment_types', 'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400']) ?>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-bold text-slate-500 uppercase"><?= lang(ucfirst($controller_name) . '.amount') ?></label>
                                    <?= form_input(['name' => 'amount_tendered', 'id' => 'amount_tendered', 'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-1 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-slate-400 non-giftcard-input', 'value' => to_currency_no_money($amount_due), 'onClick' => 'this.select();']) ?>
                                    <?= form_input(['name' => 'amount_tendered', 'id' => 'amount_tendered', 'class' => 'hidden giftcard-input', 'disabled' => true, 'value' => to_currency_no_money($amount_due)]) ?>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary action-btn w-full inline-flex items-center justify-center gap-2" id="add_payment_button">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                <?= lang(ucfirst($controller_name) . '.add_payment') ?>
                            </button>
                        <?= form_close() ?>
                    <?php } ?>

                    <?php if (count($payments) > 0) { ?>
                        <div class="space-y-1.5 mt-2">
                            <?php foreach ($payments as $payment_id => $payment) { ?>
                                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-100 group">
                                    <div class="flex items-center gap-2">
                                        <?= anchor("$controller_name/deletePayment/". base64_encode($payment_id), '<i data-lucide="x-circle" class="w-3.5 h-3.5 text-slate-400 hover:text-rose-500 transition-colors"></i>') ?>
                                        <span class="text-sm font-semibold text-slate-700"><?= $payment['payment_type'] ?></span>
                                    </div>
                                    <span class="text-sm font-bold text-slate-900"><?= to_currency($payment['payment_amount']) ?></span>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>

                <!-- Final Actions -->
                <div class="mt-4 pt-4 border-t border-slate-100">
    <?php
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

                        if (!$due_payment || ($due_payment && isset($customer))) {
                    ?>
                        <button class="btn btn-success action-btn mb-2 shadow-lg shadow-emerald-200 w-full inline-flex items-center justify-center gap-2" id="finish_sale_button" tabindex="<?= ++$tabindex ?>">
                            <i data-lucide="check-check" class="w-5 h-5"></i>
                            <?= lang(ucfirst($controller_name) . '.complete_sale') ?>
                        </button>
                    <?php
                        }
                    } elseif (!$pos_mode && isset($customer)) { ?>
                        <button class="btn btn-success action-btn mb-2 shadow-lg shadow-emerald-200 w-full inline-flex items-center justify-center gap-2" id="finish_invoice_quote_button">
                            <i data-lucide="file-check" class="w-5 h-5"></i>
                            <?= esc($mode_label) ?>
                        </button>
                    <?php } ?>

                    <?= form_open("$controller_name/cancel", ['id' => 'buttons_form']) ?>
                    <div class="grid grid-cols-2 gap-3" id="buttons_sale">
                        <div class="btn btn-default action-btn w-full inline-flex items-center justify-center gap-2" id="suspend_sale_button">
                            <i data-lucide="pause-circle" class="w-4 h-4"></i>
                            <?= lang(ucfirst($controller_name) . '.suspend_sale') ?>
                        </div>
                        <div class="btn bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-100 hover:border-rose-200 transition-all action-btn w-full inline-flex items-center justify-center gap-2" id="cancel_sale_button">
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
                            <label class="flex items-center gap-1.5 cursor-pointer p-1.5 rounded-lg border border-slate-100 bg-slate-50/50 hover:bg-slate-100 transition-colors">
                                <?= form_checkbox(['name' => 'sales_print_after_sale', 'id' => 'sales_print_after_sale', 'class' => 'w-3.5 h-3.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500', 'value' => 1, 'checked' => $print_after_sale]) ?>
                                <span class="text-sm font-semibold text-slate-600"><?= lang(ucfirst($controller_name) . '.print_after_sale') ?></span>
                            </label>

                            <?php if (!empty($customer_email)) { ?>
                                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg border border-slate-100 bg-slate-50/50 hover:bg-slate-100 transition-colors">
                                    <?= form_checkbox(['name' => 'email_receipt', 'id' => 'email_receipt', 'class' => 'w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500', 'value' => 1, 'checked' => $email_receipt]) ?>
                                    <span class="text-sm font-semibold text-slate-600"><?= lang(ucfirst($controller_name) . '.email_receipt') ?></span>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div> <!-- End Register Layout Grid -->

<script type="text/javascript">
    $(document).ready(function() {
        const redirect = function() {
            window.location.href = "<?= site_url('sales'); ?>";
        };

        $("#remove_customer_button").click(function() {
            $.post("<?= site_url('sales/removeCustomer'); ?>", redirect);
        });

        $(".delete_item_button").click(function() {
            const item_id = $(this).data('item-id');
            $.post("<?= site_url('sales/deleteItem/'); ?>" + item_id, redirect);
        });

        $(".delete_payment_button").click(function() {
            const item_id = $(this).data('payment-id');
            $.post("<?= site_url('sales/deletePayment/'); ?>" + item_id, redirect);
        });

        $("input[name='item_number']").change(function() {
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

        $("input[name='name']").change(function() {
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

        $("input[name='item_description']").change(function() {
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

        $('#item').blur(function() {
            $(this).val("<?= lang(ucfirst($controller_name) . '.start_typing_item_name') ?>");
        });

        $('#item').autocomplete({
            source: "<?= esc("$controller_name/itemSearch") ?>",
            minChars: 0,
            autoFocus: false,
            delay: 500,
            select: function(a, ui) {
                $(this).val(ui.item.value);
                $('#add_item_form').submit();
                return false;
            }
        });

        $('#item').keypress(function(e) {
            if (e.which == 13) {
                $('#add_item_form').submit();
                return false;
            }
        });

        var clear_fields = function() {
            if ($(this).val().match("<?= lang(ucfirst($controller_name) . '.start_typing_item_name') . '|' . lang(ucfirst($controller_name) . '.start_typing_customer_name') ?>")) {
                $(this).val('');
            }
        };

        $('#item, #customer').click(clear_fields).dblclick(function(event) {
            $(this).autocomplete('search');
        });

        $('#customer').blur(function() {
            $(this).val("<?= lang(ucfirst($controller_name) . '.start_typing_customer_name') ?>");
        });

        $('#customer').autocomplete({
            source: "<?= site_url('customers/suggest') ?>",
            minChars: 0,
            delay: 10,
            select: function(a, ui) {
                $(this).val(ui.item.value);
                $('#select_customer_form').submit();
                return false;
            }
        });

        $('#customer').keypress(function(e) {
            if (e.which == 13) {
                $('#select_customer_form').submit();
                return false;
            }
        });

        $('.giftcard-input').autocomplete({
            source: "<?= site_url('giftcards/suggest') ?>",
            minChars: 0,
            delay: 10,
            select: function(a, ui) {
                $(this).val(ui.item.value);
                $('#add_payment_form').submit();
                return false;
            }
        });

        $('#comment').keyup(function() {
            $.post("<?= esc(site_url("$controller_name/setComment")) ?>", {
                comment: $('#comment').val()
            });
        });

        <?php if ($config['invoice_enable']) { ?>
            $('#sales_invoice_number').keyup(function() {
                $.post("<?= esc(site_url("$controller_name/setInvoiceNumber")) ?>", {
                    sales_invoice_number: $('#sales_invoice_number').val()
                });
            });

        <?php } ?>

        $('#sales_print_after_sale').change(function() {
            $.post("<?= esc(site_url("$controller_name/setPrintAfterSale")) ?>", {
                sales_print_after_sale: $(this).is(':checked')
            });
        });

        $('#price_work_orders').change(function() {
            $.post("<?= esc(site_url("$controller_name/setPriceWorkOrders")) ?>", {
                price_work_orders: $(this).is(':checked')
            });
        });

        $('#email_receipt').change(function() {
            $.post("<?= esc(site_url("$controller_name/setEmailReceipt")) ?>", {
                email_receipt: $(this).is(':checked')
            });
        });

        $('#finish_sale_button').click(function() {
            $('#buttons_form').attr('action', "<?= "$controller_name/complete" ?>");
            $('#buttons_form').submit();
        });

        $('#finish_invoice_quote_button').click(function() {
            $('#buttons_form').attr('action', "<?= "$controller_name/complete" ?>");
            $('#buttons_form').submit();
        });

        $('#suspend_sale_button').click(function() {
            $('#buttons_form').attr('action', "<?= site_url("$controller_name/suspend") ?>");
            $('#buttons_form').submit();
        });

        $('#cancel_sale_button').click(function() {
            if (confirm("<?= lang(ucfirst($controller_name) . '.confirm_cancel_sale') ?>")) {
                $('#buttons_form').attr('action', "<?= site_url("$controller_name/cancel") ?>");
                $('#buttons_form').submit();
            }
        });

        $('#add_payment_button').click(function() {
            $('#add_payment_form').submit();
        });

        $('#payment_types').change(check_payment_type).ready(check_payment_type);

        $('#cart_contents input').keypress(function(event) {
            if (event.which == 13) {
                $(this).parents('tr').prevAll('form:first').submit();
            }
        });

        $('#amount_tendered').keypress(function(event) {
            if (event.which == 13) {
                $('#add_payment_form').submit();
            }
        });

        $('#finish_sale_button').keypress(function(event) {
            if (event.which == 13) {
                $('#finish_sale_form').submit();
            }
        });

        dialog_support.init('a.modal-dlg, button.modal-dlg');

        table_support.handle_submit = function(resource, response, stay_open) {
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

        $('[name="price"],[name="quantity"],[name="discount"],[name="description"],[name="serialnumber"],[name="discounted_total"]').change(function() {
            $(this).parents('tr').prevAll('form:first').submit()
        });

        $('[name="discount_toggle"]').change(function() {
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

    // Add Keyboard Shortcuts/Hotkeys to Sale Register
    document.body.onkeyup = function(e) {
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
