<?php
/**
 * @var string $controller_name
 * @var array $modes
 * @var string $mode
 * @var bool $show_stock_locations
 * @var array $stock_locations
 * @var int $stock_source
 * @var string $stock_destination
 * @var array $cart
 * @var bool $items_module_allowed
 * @var float $total
 * @var string $comment
 * @var bool $print_after_sale
 * @var string $reference
 * @var array $payment_options
 * @var array $config
 */
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
    echo '<div class="alert alert-dismissible alert-success">' .esc($success) . '</div>';
}
?>

<div class="register-layout animate-page-entry">
    <div class="flex flex-col gap-3">
        <!-- Top register controls -->
        <div class="register-card">
            <?= form_open("$controller_name/changeMode", ['id' => 'mode_form', 'class' => 'flex flex-wrap items-center justify-between gap-3']) ?>
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1.5">
                        <label class="text-sm font-semibold text-slate-700"><?= lang(ucfirst($controller_name) . '.mode') ?>:</label>
                        <?= form_dropdown('mode', $modes, $mode, ['onchange' => "$('#mode_form').submit();", 'class' => 'flex h-8 w-fit rounded-md border border-slate-300 bg-white px-2 py-0.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400']) ?>
                    </div>

                    <?php if ($show_stock_locations) { ?>
                        <div class="flex items-center gap-1.5">
                            <label class="text-sm font-semibold text-slate-700"><?= lang(ucfirst($controller_name) . '.stock_source') ?>:</label>
                            <?= form_dropdown('stock_source', $stock_locations, $stock_source, ['onchange' => "$('#mode_form').submit();", 'class' => 'flex h-8 w-fit rounded-md border border-slate-300 bg-white px-2 py-0.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400']) ?>
                        </div>

                        <?php if ($mode == 'requisition') { ?>
                            <div class="flex items-center gap-1.5">
                                <label class="text-sm font-semibold text-slate-700"><?= lang(ucfirst($controller_name) . '.stock_destination') ?>:</label>
                                <?= form_dropdown('stock_destination', $stock_locations, $stock_destination, ['onchange' => "$('#mode_form').submit();", 'class' => 'flex h-8 w-fit rounded-md border border-slate-300 bg-white px-2 py-0.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400']) ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?= form_close() ?>
        </div>

        <div class="register-card">
            <?= form_open("$controller_name/add", ['id' => 'add_item_form', 'class' => 'flex items-center justify-between gap-3']) ?>
                <div class="flex items-center gap-2 flex-grow max-w-2xl">
                    <label for="item" class="text-sm font-semibold text-slate-700 whitespace-nowrap">
                        <?php if ($mode == 'receive' or $mode == 'requisition') { ?>
                            <?= lang(ucfirst($controller_name) . '.find_or_scan_item') ?>:
                        <?php } else { ?>
                            <?= lang(ucfirst($controller_name) . '.find_or_scan_item_or_receipt') ?>:
                        <?php } ?>
                    </label>
                    <?= form_input(['name' => 'item', 'id' => 'item', 'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400', 'placeholder' => lang('Sales.start_typing_item_name'), 'tabindex' => '1']) ?>
                </div>

                <button id="new_item_button" class="btn btn-primary btn-sm modal-dlg inline-flex items-center gap-2 shrink-0" data-btn-submit="<?= lang('Common.submit') ?>" data-btn-new="<?= lang('Common.new') ?>" data-href="<?= "items/view" ?>" title="<?= lang('Sales.new_item') ?>">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                    <?= lang('Sales.new_item') ?>
                </button>
            <?= form_close() ?>
        </div>

        <!-- Receiving Items List -->
        <div class="register-card p-0 overflow-hidden border-0 shadow-sm rounded-lg">
            <table class="w-full text-sm text-left border-collapse" id="register">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 w-[5%]"><?= lang('Common.delete') ?></th>
                        <th class="px-4 py-3 w-[15%]"><?= lang('Sales.item_number') ?></th>
                        <th class="px-4 py-3 w-[23%]"><?= lang(ucfirst($controller_name) . '.item_name') ?></th>
                        <th class="px-4 py-3 w-[10%]"><?= lang(ucfirst($controller_name) . '.cost') ?></th>
                        <th class="px-4 py-3 w-[8%]"><?= lang(ucfirst($controller_name) . '.quantity') ?></th>
                        <th class="px-4 py-3 w-[10%]"><?= lang(ucfirst($controller_name) . '.ship_pack') ?></th>
                        <th class="px-4 py-3 w-[14%]"><?= lang(ucfirst($controller_name) . '.discount') ?></th>
                        <th class="px-4 py-3 w-[10%]"><?= lang(ucfirst($controller_name) . '.total') ?></th>
                        <th class="px-4 py-3 w-[5%] text-center"><?= lang(ucfirst($controller_name) . '.update') ?></th>
                    </tr>
                </thead>

                <tbody id="cart_contents" class="divide-y divide-slate-100">
                    <?php if (count($cart) == 0) { ?>
                        <tr>
                            <td colspan="9" class="p-8 text-center bg-white">
                                <div class="flex flex-col items-center gap-2 text-slate-400">
                                    <i data-lucide="shopping-cart" class="w-12 h-12 opacity-20"></i>
                                    <span class="text-sm font-medium"><?= lang('Sales.no_items_in_cart') ?></span>
                                </div>
                            </td>
                        </tr>
                    <?php } else {
                        foreach (array_reverse($cart, true) as $line => $item) {
                        ?>
                            <?= form_open("$controller_name/editItem/$line", ['id' => "cart_$line"]) ?>
                                <tr class="hover:bg-slate-50/50 transition-colors align-middle">
                                    <td class="px-4 py-3">
                                        <a href="<?= site_url("$controller_name/deleteItem/$line") ?>" class="text-red-400 hover:text-red-600 transition-colors inline-block p-1">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 font-mono text-xs"><?= esc($item['item_number']) ?></td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-slate-900"><?= esc($item['name'] . ' ' . implode(' ', [$item['attribute_values'], $item['attribute_dtvalues']])) ?></span>
                                            <span class="text-xs text-slate-500">[<?= to_quantity_decimals($item['in_stock']) ?> in <?= $item['stock_name'] ?>]</span>
                                            <?= form_hidden('location', (string)$item['item_location']) ?>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <?php if ($items_module_allowed && $mode != 'requisition') { ?>
                                            <?= form_input([
                                                'name'    => 'price',
                                                'class'   => 'flex h-8 w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-emerald-400',
                                                'value'   => to_currency_no_money($item['price']),
                                                'onClick' => 'this.select();'
                                            ]) ?>
                                        <?php } else { ?>
                                            <span class="text-slate-700"><?= $item['price'] ?></span>
                                            <?= form_hidden('price', to_currency_no_money($item['price'])) ?>
                                        <?php } ?>
                                    </td>

                                    <td class="px-4 py-3">
                                        <?= form_input(['name' => 'quantity', 'class' => 'flex h-8 w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-emerald-400', 'value' => to_quantity_decimals($item['quantity']), 'onClick' => 'this.select();']) ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?= form_dropdown(
                                            'receiving_quantity',
                                            $item['receiving_quantity_choices'],
                                            $item['receiving_quantity'],
                                            ['class' => 'flex h-8 w-full rounded border border-slate-200 bg-white px-1 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-emerald-400']
                                        ) ?>
                                    </td>

                                    <td class="px-4 py-3">
                                        <?php if ($items_module_allowed && $mode != 'requisition') { ?>
                                            <div class="flex items-center gap-1">
                                                <?= form_input(['name' => 'discount', 'class' => 'flex h-8 w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-emerald-400', 'value' => $item['discount_type'] ? to_currency_no_money($item['discount']) : to_decimals($item['discount']), 'onClick' => 'this.select();']) ?>
                                                <div class="shrink-0 scale-75 origin-right">
                                                    <?= form_checkbox([
                                                        'id'           => 'discount_toggle',
                                                        'name'         => 'discount_toggle',
                                                        'value'        => 1,
                                                        'data-toggle'  => "toggle",
                                                        'data-size'    => 'small',
                                                        'data-onstyle' => 'success',
                                                        'data-on'      => '<b>' . $config['currency_symbol'] . '</b>',
                                                        'data-off'     => '<b>%</b>',
                                                        'data-line'    => $line,
                                                        'checked'      => $item['discount_type'] == 1
                                                    ]) ?>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <span class="text-slate-700"><?= $item['discount'] ?></span>
                                            <?= form_hidden('discount', (string)$item['discount']) ?>
                                        <?php } ?>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-slate-900">
                                        <?= to_currency(($item['discount_type'] == PERCENT) ? $item['price'] * $item['quantity'] * $item['receiving_quantity'] - $item['price'] * $item['quantity'] * $item['receiving_quantity'] * $item['discount'] / 100 : $item['price'] * $item['quantity'] * $item['receiving_quantity'] - $item['discount']) ?>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="submit" class="text-emerald-500 hover:text-emerald-700 transition-colors p-1" title="<?= lang(ucfirst($controller_name) . '.update') ?>">
                                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php if ($item['allow_alt_description'] == 1 || $item['description'] != '') { ?>
                                    <tr class="bg-slate-50/30">
                                        <td class="px-4 py-1 text-[10px] text-slate-400 font-bold uppercase"><?= lang('Sales.description_abbrv') ?></td>
                                        <td colspan="8" class="px-4 py-1">
                                             <?php
                                                if ($item['allow_alt_description'] == 1) {
                                                    echo form_input([
                                                        'name'  => 'description',
                                                        'class' => 'flex h-7 w-full border-b border-transparent bg-transparent px-0 py-1 text-xs focus:outline-none focus:border-emerald-400',
                                                        'value' => $item['description']
                                                    ]);
                                                } else {
                                                    echo '<span class="text-xs text-slate-500 italic">' . ($item['description'] ?: lang('Sales.no_description')) . '</span>';
                                                    echo form_hidden('description', $item['description']);
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?= form_close() ?>
                        <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sidebar: Overall Receiving -->
    <div class="flex flex-col gap-3">
        <div class="register-card p-5">
            <?php if (isset($supplier)) { ?>
                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-slate-500 font-medium"><?= lang(ucfirst($controller_name) . '.supplier') ?></span>
                                <span class="text-sm font-bold text-slate-900"><?= esc($supplier) ?></span>
                            </div>
                        </div>
                        <a href="<?= site_url("$controller_name/removeSupplier") ?>" class="text-slate-400 hover:text-red-500 transition-colors" title="<?= lang('Common.remove') ?>">
                            <i data-lucide="x-circle" class="w-5 h-5"></i>
                        </a>
                    </div>

                    <div class="space-y-2 text-xs text-slate-600">
                        <?php if (!empty($supplier_email)) { ?>
                            <div class="flex items-center gap-2">
                                <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span><?= esc($supplier_email) ?></span>
                            </div>
                        <?php } ?>
                        <?php if (!empty($supplier_address)) { ?>
                            <div class="flex items-start gap-2">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 mt-0.5"></i>
                                <span><?= esc($supplier_address) ?></span>
                            </div>
                        <?php } ?>
                        <?php if (!empty($supplier_location)) { ?>
                            <div class="flex items-center gap-2">
                                <i data-lucide="globe" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span><?= esc($supplier_location) ?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } else { ?>
                <?= form_open("$controller_name/selectSupplier", ['id' => 'select_supplier_form', 'class' => 'flex flex-col gap-3']) ?>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider"><?= lang(ucfirst($controller_name) . '.select_supplier') ?></label>
                    <div class="relative">
                        <?= form_input([
                            'name'  => 'supplier',
                            'id'    => 'supplier',
                            'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400',
                            'placeholder' => lang(ucfirst($controller_name) . '.start_typing_supplier_name')
                        ]) ?>
                    </div>
                    <button id="new_supplier_button" class="btn btn-default btn-sm modal-dlg inline-flex items-center justify-center gap-2" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= "suppliers/view" ?>" title="<?= lang(ucfirst($controller_name) . '.new_supplier') ?>">
                        <i data-lucide="user-plus" class="w-4 h-4 text-emerald-500"></i>
                        <?= lang(ucfirst($controller_name) . '.new_supplier') ?>
                    </button>
                <?= form_close() ?>
            <?php } ?>
        </div>

        <div class="register-card p-5 bg-slate-50/50">
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-slate-600"><?= lang('Sales.total') ?></span>
                    <span class="text-2xl font-black text-slate-900"><?= to_currency($total) ?></span>
                </div>

                <?php if (count($cart) > 0) { ?>
                    <?= form_open($mode == 'requisition' ? "$controller_name/requisitionComplete" : "$controller_name/complete", ['id' => 'finish_receiving_form', 'class' => 'flex flex-col gap-4']) ?>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider"><?= lang('Common.comments') ?></label>
                            <?= form_textarea([
                                'name'  => 'comment',
                                'id'    => 'comment',
                                'class' => 'flex min-h-[80px] w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400',
                                'value' => $comment,
                                'rows'  => '2'
                            ]) ?>
                        </div>

                        <?php if ($mode != 'requisition') { ?>
                            <div class="grid grid-cols-1 gap-3 p-3 bg-white rounded-lg border border-slate-200">
                                <div class="flex items-center justify-between px-1">
                                    <label class="text-xs font-medium text-slate-600"><?= lang(ucfirst($controller_name) . '.print_after_sale') ?></label>
                                    <?= form_checkbox([
                                        'name'    => 'recv_print_after_sale',
                                        'id'      => 'recv_print_after_sale',
                                        'class'   => 'w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500',
                                        'value'   => 1,
                                        'checked' => $print_after_sale == 1
                                    ]) ?>
                                </div>
                                
                                <?php if ($mode == "receive") { ?>
                                    <div class="flex flex-col gap-1">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase"><?= lang(ucfirst($controller_name) . '.reference') ?></label>
                                        <?= form_input([
                                            'name'  => 'recv_reference',
                                            'id'    => 'recv_reference',
                                            'class' => 'flex h-8 w-full rounded border border-slate-200 bg-slate-50 px-2 py-1 text-xs focus:bg-white focus:outline-none focus:ring-1 focus:ring-emerald-400',
                                            'value' => $reference
                                        ]) ?>
                                    </div>
                                <?php } ?>

                                <div class="flex flex-col gap-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase"><?= lang('Sales.payment') ?></label>
                                    <?= form_dropdown(
                                        'payment_type',
                                        $payment_options,
                                        [],
                                        [
                                            'id' => 'payment_types',
                                            'class' => 'flex h-8 w-full rounded border border-slate-200 bg-slate-50 px-2 py-1 text-xs focus:bg-white focus:outline-none focus:ring-1 focus:ring-emerald-400'
                                        ]
                                    ) ?>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase"><?= lang('Sales.amount_tendered') ?></label>
                                    <?= form_input([
                                        'name'  => 'amount_tendered',
                                        'class' => 'flex h-8 w-full rounded border border-slate-200 bg-slate-50 px-2 py-1 text-xs focus:bg-white focus:outline-none focus:ring-1 focus:ring-emerald-400',
                                        'placeholder' => '0.00'
                                    ]) ?>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="flex gap-2">
                            <button type="button" id="cancel_receiving_button" class="flex-1 h-10 inline-flex items-center justify-center gap-2 rounded-md bg-white border border-slate-200 text-red-600 text-sm font-semibold hover:bg-red-50 hover:border-red-200 transition-colors">
                                <i data-lucide="x" class="w-4 h-4"></i>
                                <?= lang('Common.cancel') ?>
                            </button>
                            <button type="button" id="finish_receiving_button" class="flex-[2] h-10 inline-flex items-center justify-center gap-2 rounded-md bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 shadow-sm shadow-emerald-200 transition-all">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                <?= lang(ucfirst($controller_name) . '.complete_receiving') ?>
                            </button>
                        </div>
                    <?= form_close() ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $("#item").autocomplete({
            source: '<?= esc("$controller_name/stockItemSearch") ?>',
            minChars: 0,
            delay: 10,
            autoFocus: false,
            select: function(a, ui) {
                $(this).val(ui.item.value);
                $("#add_item_form").submit();
                return false;
            }
        });

        $('#item').focus();

        $('#item').keypress(function(e) {
            if (e.which == 13) {
                $('#add_item_form').submit();
                return false;
            }
        });

        $('#item').blur(function() {
            $(this).attr('value', "<?= lang('Sales.start_typing_item_name') ?>");
        });

        $('#comment').keyup(function() {
            $.post('<?= esc("$controller_name/setComment") ?>', {
                comment: $('#comment').val()
            });
        });

        $('#recv_reference').keyup(function() {
            $.post('<?= esc("$controller_name/setReference") ?>', {
                recv_reference: $('#recv_reference').val()
            });
        });

        $("#recv_print_after_sale").change(function() {
            $.post('<?= esc("$controller_name/setPrintAfterSale") ?>', {
                recv_print_after_sale: $(this).is(":checked")
            });
        });

        $('#item,#supplier').click(function() {
            $(this).attr('value', '');
        });

        $("#supplier").autocomplete({
            source: '<?= "suppliers/suggest" ?>',
            minChars: 0,
            delay: 10,
            select: function(a, ui) {
                $(this).val(ui.item.value);
                $("#select_supplier_form").submit();
            }
        });

        dialog_support.init("a.modal-dlg, button.modal-dlg");

        $('#supplier').blur(function() {
            $(this).attr('value', "<?= lang(ucfirst($controller_name) . '.start_typing_supplier_name') ?>");
        });

        $("#finish_receiving_button").click(function() {
            $('#finish_receiving_form').submit();
        });

        $("#cancel_receiving_button").click(function() {
            if (confirm('<?= lang(ucfirst($controller_name) . '.confirm_cancel_receiving') ?>')) {
                $('#finish_receiving_form').attr('action', '<?= esc("$controller_name/cancelReceiving") ?>');
                $('#finish_receiving_form').submit();
            }
        });

        $("#cart_contents input").keypress(function(event) {
            if (event.which == 13) {
                $(this).parents("tr").prevAll("form:first").submit();
            }
        });

        table_support.handle_submit = function(resource, response, stay_open) {
            if (response.success) {
                if (resource.match(/suppliers$/)) {
                    $("#supplier").val(response.id);
                    $("#select_supplier_form").submit();
                } else {
                    $("#item").val(response.id);
                    if (stay_open) {
                        $("#add_item_form").ajaxSubmit();
                    } else {
                        $("#add_item_form").submit();
                    }
                }
            }
        }

        $('[name="price"],[name="quantity"],[name="receiving_quantity"],[name="discount"],[name="description"],[name="serialnumber"]').change(function() {
            $(this).parents("tr").prevAll("form:first").submit()
        });

        $('[name="discount_toggle"]').change(function() {
            var input = $("<input>").attr("type", "hidden").attr("name", "discount_type").val(($(this).prop('checked')) ? 1 : 0);
            $('#cart_' + $(this).attr('data-line')).append($(input));
            $('#cart_' + $(this).attr('data-line')).submit();
        });

    });
</script>

<?= view('partial/footer') ?>
