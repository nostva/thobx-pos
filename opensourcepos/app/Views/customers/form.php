<?php
/**
 * @var string $controller_name
 * @var object $person_info
 * @var array $packages
 * @var int $selected_package
 * @var bool $use_destination_based_tax
 * @var string $sales_tax_code_label
 * @var string $employee
 * @var array $config
 */
?>

<ul id="error_message_box" class="error_message_box text-sm text-red-500 mb-4"></ul>

<?= form_open("$controller_name/save/$person_info->person_id", ['id' => 'customer_form', 'class' => 'space-y-6']) ?>

<ul class="nav nav-tabs nav-justified" data-tabs="tabs">
    <li class="active" role="presentation">
        <a data-toggle="tab" href="#customer_basic_info">
            <?= lang('Customers.basic_information') ?>
        </a>
    </li>
    <?php if (!empty($stats)) { ?>
        <li role="presentation">
            <a data-toggle="tab" href="#customer_stats_info"><?= lang('Customers.stats_info') ?></a>
        </li>
    <?php } ?>
    <?php if (!empty($mailchimp_info) && !empty($mailchimp_activity)) { ?>
        <li role="presentation">
            <a data-toggle="tab" href="#customer_mailchimp_info"><?= lang('Customers.mailchimp_info') ?></a>
        </li>
    <?php } ?>
</ul>

<div class="tab-content">
    <div class="tab-pane fade in active" id="customer_basic_info">
        <fieldset class="flex flex-col gap-4">
            <div class="form-group w-full">
                <?= form_checkbox([
                    'name' => 'consent',
                    'id' => 'consent',
                    'value' => 1,
                    'checked' => $person_info->consent == '' ? !$config['enforce_privacy'] : (bool) $person_info->consent,
                    'class' => 'rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
                ]) ?>
                <?= form_label(lang('Customers.consent'), 'consent', ['class' => 'text-sm text-slate-700 cursor-pointer mb-0 required']) ?>
            </div>

            <div class="md:col-span-2">
                <?= view('people/form_basic_info') ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <div class="form-group w-full">
                    <?= form_label(lang('Customers.discount_type'), 'discount_type', ['class' => 'label text-sm font-medium text-slate-700 mb-2 block']) ?>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <?= form_radio([
                                'name' => 'discount_type',
                                'type' => 'radio',
                                'id' => 'discount_type',
                                'value' => 0,
                                'checked' => $person_info->discount_type == PERCENT,
                                'class' => 'text-indigo-600 focus:ring-indigo-500 border-gray-300'
                            ]) ?>
                            <span class="text-sm text-slate-700"><?= lang('Customers.discount_percent') ?></span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <?= form_radio([
                                'name' => 'discount_type',
                                'type' => 'radio',
                                'id' => 'discount_type',
                                'value' => 1,
                                'checked' => $person_info->discount_type == FIXED,
                                'class' => 'text-indigo-600 focus:ring-indigo-500 border-gray-300'
                            ]) ?>
                            <span class="text-sm text-slate-700"><?= lang('Customers.discount_fixed') ?></span>
                        </label>
                    </div>
                </div>

                <div class="form-group w-full">
                    <?= form_label(lang('Customers.discount'), 'discount', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <?= form_input([
                        'name' => 'discount',
                        'id' => 'discount',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                        'onClick' => 'this.select();',
                        'value' => $person_info->discount_type === FIXED ? to_currency_no_money($person_info->discount) : to_decimals($person_info->discount)
                    ]) ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                <div class="form-group w-full">
                    <?= form_label(lang('Customers.company_name'), 'customer_company_name', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <?= form_input([
                        'name' => 'company_name',
                        'id' => 'customer_company_name',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                        'value' => $person_info->company_name
                    ]) ?>
                </div>

                <div class="form-group w-full">
                    <?= form_label(lang('Customers.account_number'), 'account_number', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <?= form_input([
                        'name' => 'account_number',
                        'id' => 'account_number',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                        'value' => $person_info->account_number
                    ]) ?>
                </div>

                <div class="form-group w-full">
                    <?= form_label(lang('Customers.tax_id'), 'tax_id', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <?= form_input([
                        'name' => 'tax_id',
                        'id' => 'tax_id',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                        'value' => $person_info->tax_id
                    ]) ?>
                </div>
            </div>

            <?php if ($config['customer_reward_enable']): ?>
                <div class="form-group w-full">
                    <?= form_label(lang('Customers.rewards_package'), 'rewards', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <?= form_dropdown(
                        'package_id',
                        $packages,
                        $selected_package,
                        'class="flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2"'
                    ) ?>
                </div>

                <div class="form-group w-full">
                    <?= form_label(lang('Customers.available_points'), 'available_points', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <?= form_input([
                        'name' => 'available_points',
                        'id' => 'available_points',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-slate-100 px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none cursor-not-allowed',
                        'value' => $person_info->points,
                        'disabled' => ''
                    ]) ?>
                </div>
            <?php endif; ?>

            <div class="form-group w-full">
                <?= form_checkbox([
                    'name' => 'taxable',
                    'id' => 'taxable',
                    'value' => 1,
                    'checked' => $person_info->taxable == 1,
                    'class' => 'rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
                ]) ?>
                <?= form_label(lang('Customers.taxable'), 'taxable', ['class' => 'text-sm text-slate-700 cursor-pointer mb-0']) ?>
            </div>

            <?php if ($use_destination_based_tax) { ?>
                <div class="form-group w-full">
                    <?= form_label(lang('Customers.tax_code'), 'sales_tax_code_name', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <div class="relative">
                        <?= form_input([
                            'name' => 'sales_tax_code_name',
                            'id' => 'sales_tax_code_name',
                            'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
                            'value' => $sales_tax_code_label
                        ]) ?>
                        <?= form_hidden('sales_tax_code_id', $person_info->sales_tax_code_id) ?>
                    </div>
                </div>
            <?php } ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <div class="form-group w-full">
                    <?= form_label(lang('Customers.date'), 'date', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <?= form_input([
                            'name' => 'date',
                            'id' => 'datetime',
                            'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-slate-100 ps-10 pe-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none cursor-not-allowed',
                            'value' => to_datetime(strtotime($person_info->date)),
                            'readonly' => 'true'
                        ]) ?>
                    </div>
                </div>

                <div class="form-group w-full">
                    <?= form_label(lang('Customers.employee'), 'employee', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block']) ?>
                    <?= form_input([
                        'name' => 'employee',
                        'id' => 'employee',
                        'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-slate-100 px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none cursor-not-allowed',
                        'value' => $employee,
                        'readonly' => 'true'
                    ]) ?>
                </div>

                <?= form_hidden('employee_id', $person_info->employee_id) ?>
            </div>
        </fieldset>
    </div>

    <?php if (!empty($stats)) { ?>
        <br>
        <div class="tab-pane" id="customer_stats_info">
            <fieldset>
                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.total'), 'total', ['class' => 'control-label col-xs-5']) ?>
                    <div class="col-xs-4">
                        <div class="input-group input-group-sm">
                            <?php if (!is_right_side_currency_symbol()): ?>
                                <span class="input-group-addon input-sm"><b><?= esc($config['currency_symbol']) ?></b></span>
                            <?php endif; ?>
                            <?= form_input([
                                'name' => 'total',
                                'id' => 'total',
                                'class' => 'form-control input-sm',
                                'value' => to_currency_no_money($stats->total),
                                'disabled' => ''
                            ]) ?>
                            <?php if (is_right_side_currency_symbol()): ?>
                                <span class="input-group-addon input-sm"><b><?= esc($config['currency_symbol']) ?></b></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.max'), 'max', ['class' => 'control-label col-xs-5']) ?>
                    <div class="col-xs-4">
                        <div class="input-group input-group-sm">
                            <?php if (!is_right_side_currency_symbol()): ?>
                                <span class="input-group-addon input-sm"><b><?= esc($config['currency_symbol']) ?></b></span>
                            <?php endif; ?>
                            <?= form_input([
                                'name' => 'max',
                                'id' => 'max',
                                'class' => 'form-control input-sm',
                                'value' => to_currency_no_money($stats->max),
                                'disabled' => ''
                            ]) ?>
                            <?php if (is_right_side_currency_symbol()): ?>
                                <span class="input-group-addon input-sm"><b><?= esc($config['currency_symbol']) ?></b></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.min'), 'min', ['class' => 'control-label col-xs-5']) ?>
                    <div class="col-xs-4">
                        <div class="input-group input-group-sm">
                            <?php if (!is_right_side_currency_symbol()): ?>
                                <span class="input-group-addon input-sm"><b><?= esc($config['currency_symbol']) ?></b></span>
                            <?php endif; ?>
                            <?= form_input([
                                'name' => 'min',
                                'id' => 'min',
                                'class' => 'form-control input-sm',
                                'value' => to_currency_no_money($stats->min),
                                'disabled' => ''
                            ]) ?>
                            <?php if (is_right_side_currency_symbol()): ?>
                                <span class="input-group-addon input-sm"><b><?= esc($config['currency_symbol']) ?></b></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.average'), 'average', ['class' => 'control-label col-xs-5']) ?>
                    <div class="col-xs-4">
                        <div class="input-group input-group-sm">
                            <?php if (!is_right_side_currency_symbol()): ?>
                                <span class="input-group-addon input-sm"><b><?= esc($config['currency_symbol']) ?></b></span>
                            <?php endif; ?>
                            <?= form_input([
                                'name' => 'average',
                                'id' => 'average',
                                'class' => 'form-control input-sm',
                                'value' => to_currency_no_money($stats->average),
                                'disabled' => ''
                            ]) ?>
                            <?php if (is_right_side_currency_symbol()): ?>
                                <span class="input-group-addon input-sm"><b><?= esc($config['currency_symbol']) ?></b></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.quantity'), 'quantity', ['class' => 'control-label col-xs-5']) ?>
                    <div class="col-xs-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon input-sm"><b><?= '>' ?></b></span>
                            <?= form_input([
                                'name' => 'quantity',
                                'id' => 'quantity',
                                'class' => 'form-control input-sm',
                                'value' => to_quantity_decimals($stats->quantity),
                                'disabled' => ''
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.avg_discount'), 'avg_discount', ['class' => 'control-label col-xs-5']) ?>
                    <div class="col-xs-4">
                        <div class="input-group input-group-sm">
                            <?= form_input([
                                'name' => 'avg_discount',
                                'id' => 'avg_discount',
                                'class' => 'form-control input-sm',
                                'value' => to_decimals($stats->avg_discount),
                                'disabled' => ''
                            ]) ?>
                            <span class="input-group-addon input-sm"><b>%</b></span>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    <?php } ?>

    <?php if (!empty($mailchimp_info) && !empty($mailchimp_activity)) { ?>
        <div class="tab-pane" id="customer_mailchimp_info">
            <fieldset>
                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.mailchimp_status'), 'mailchimp_status', ['class' => 'control-label col-xs-3']) ?>
                    <div class="col-xs-4">
                        <?= form_dropdown(
                            'mailchimp_status',
                            [
                                'subscribed' => 'subscribed',
                                'unsubscribed' => 'unsubscribed',
                                'cleaned' => 'cleaned',
                                'pending' => 'pending'
                            ],
                            $mailchimp_info['status'],
                            ['id' => 'mailchimp_status', 'class' => 'form-control input-sm']
                        ) ?>
                    </div>
                </div>

                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.mailchimp_vip'), 'mailchimp_vip', ['class' => 'control-label col-xs-3']) ?>
                    <div class="col-xs-1">
                        <?= form_checkbox('mailchimp_vip', 1, $mailchimp_info['vip'] == 1) ?>
                    </div>
                </div>

                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.mailchimp_member_rating'), 'mailchimp_member_rating', ['class' => 'control-label col-xs-3']) ?>
                    <div class="col-xs-4">
                        <?= form_input([
                            'name' => 'mailchimp_member_rating',
                            'class' => 'form-control input-sm',
                            'value' => $mailchimp_info['member_rating'],
                            'disabled' => ''
                        ]) ?>
                    </div>
                </div>

                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.mailchimp_activity_total'), 'mailchimp_activity_total', ['class' => 'control-label col-xs-3']) ?>
                    <div class="col-xs-4">
                        <?= form_input([
                            'name' => 'mailchimp_activity_total',
                            'class' => 'form-control input-sm',
                            'value' => $mailchimp_activity['total'],
                            'disabled' => ''
                        ]) ?>
                    </div>
                </div>

                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.mailchimp_activity_lastopen'), 'mailchimp_activity_lastopen', ['class' => 'control-label col-xs-3']) ?>
                    <div class="col-xs-4">
                        <?= form_input([
                            'name' => 'mailchimp_activity_lastopen',
                            'class' => 'form-control input-sm',
                            'value' => $mailchimp_activity['lastopen'],
                            'disabled' => ''
                        ]) ?>
                    </div>
                </div>

                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.mailchimp_activity_open'), 'mailchimp_activity_open', ['class' => 'control-label col-xs-3']) ?>
                    <div class="col-xs-4">
                        <?= form_input([
                            'name' => 'mailchimp_activity_open',
                            'class' => 'form-control input-sm',
                            'value' => $mailchimp_activity['open'],
                            'disabled' => ''
                        ]) ?>
                    </div>
                </div>

                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.mailchimp_activity_click'), 'mailchimp_activity_click', ['class' => 'control-label col-xs-3']) ?>
                    <div class="col-xs-4">
                        <?= form_input([
                            'name' => 'mailchimp_activity_click',
                            'class' => 'form-control input-sm',
                            'value' => $mailchimp_activity['click'],
                            'disabled' => ''
                        ]) ?>
                    </div>
                </div>

                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.mailchimp_activity_unopen'), 'mailchimp_activity_unopen', ['class' => 'control-label col-xs-3']) ?>
                    <div class="col-xs-4">
                        <?= form_input([
                            'name' => 'mailchimp_activity_unopen',
                            'class' => 'form-control input-sm',
                            'value' => $mailchimp_activity['unopen'],
                            'disabled' => ''
                        ]) ?>
                    </div>
                </div>

                <div class="form-group form-group-sm">
                    <?= form_label(lang('Customers.mailchimp_email_client'), 'mailchimp_email_client', ['class' => 'control-label col-xs-3']) ?>
                    <div class="col-xs-4">
                        <?= form_input([
                            'name' => 'mailchimp_email_client',
                            'class' => 'form-control input-sm',
                            'value' => $mailchimp_info['email_client'],
                            'disabled' => ''
                        ]) ?>
                    </div>
                </div>
            </fieldset>
        </div>
    <?php } ?>
</div>

<?= form_close() ?>

<script type="text/javascript">
    // Validation and submit handling
    $(document).ready(function () {
        $("input[name='sales_tax_code_name']").change(function () {
            if (!$("input[name='sales_tax_code_name']").val()) {
                $("input[name='sales_tax_code_id']").val('');
            }
        });

        var fill_value = function (event, ui) {
            event.preventDefault();
            $("input[name='sales_tax_code_id']").val(ui.item.value);
            $("input[name='sales_tax_code_name']").val(ui.item.label);
        };

        $('#sales_tax_code_name').autocomplete({
            source: "<?= esc('taxes/suggestTaxCodes') ?>",
            minChars: 0,
            delay: 15,
            cacheLength: 1,
            appendTo: '.modal-content',
            select: fill_value,
            focus: fill_value
        });

        $('#customer_form').validate($.extend({
            submitHandler: function (form) {
                $(form).ajaxSubmit({
                    success: function (response) {
                        dialog_support.hide();
                        table_support.handle_submit("<?= $controller_name ?>", response);
                    },
                    dataType: 'json'
                });
            },

            errorLabelContainer: '#error_message_box',

            rules: {
                first_name: 'required',
                last_name: 'required',
                consent: 'required',
                email: {
                    remote: {
                        url: "<?= "$controller_name/checkEmail" ?>",
                        type: 'POST',
                        data: {
                            'person_id': "<?= $person_info->person_id ?>"
                            // Email is posted by default
                        }
                    }
                },
                account_number: {
                    remote: {
                        url: "<?= "$controller_name/checkAccountNumber" ?>",
                        type: 'POST',
                        data: {
                            'person_id': "<?= $person_info->person_id ?>"
                            // Account_number is posted by default
                        }
                    }
                }
            },

            messages: {
                first_name: "<?= lang('Common.first_name_required') ?>",
                last_name: "<?= lang('Common.last_name_required') ?>",
                consent: "<?= lang('Customers.consent_required') ?>",
                email: "<?= lang('Customers.email_duplicate') ?>",
                account_number: "<?= lang('Customers.account_number_duplicate') ?>"
            }
        }, form_support.error));
    });
</script>