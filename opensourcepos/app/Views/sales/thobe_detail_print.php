<div class="thobe-print-container"
    style="<?= ($page_break ?? true) ? 'page-break-before: always;' : '' ?> font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; padding-top: 15px; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important;">
    <hr class="thobe-print-divider" style="border-top: 1px dashed #666666; margin-bottom: 15px;">

    <div class="thobe-print-header" style="text-align: center; margin-bottom: 15px;">
        <h3
            style="font-weight: bold; margin: 0 0 5px 0; font-size: 15px; color: #000000; text-transform: uppercase; letter-spacing: 0.05em;">
            <?= lang('Thobe.thobe_order_details') ?>
        </h3>
        <div style="font-size: 11px; color: #334155; line-height: 1.5;">
            <strong><?= lang('Sales.id') ?>:</strong> <?= esc($sale_id) ?><br class="print-only-narrow">
            <span class="print-hide-narrow"> &nbsp;|&nbsp; </span>
            <strong><?= lang('Customers.customer') ?>:</strong> <?= esc($customer) ?><br class="print-only-narrow">
            <span class="print-hide-narrow"> &nbsp;|&nbsp; </span>
            <strong><?= lang('Sales.date') ?>:</strong> <?= esc($transaction_time) ?>
        </div>
    </div>

    <div class="thobe-print-grid" style="display: flex; flex-direction: column; gap: 15px;">


        <!-- Fabric & Style Columns container (using a responsive CSS grid styled via print stylesheet, fallback inline) -->
        <div class="thobe-print-style-fabric">
            <!-- Fabric Details Section -->
            <?php if (!empty($thobe_detail['fabric_item_number']) || !empty($thobe_detail['fabric_quantity'])): ?>
                <div class="thobe-print-section" style="page-break-inside: avoid;">
                    <h4
                        style="font-size: 11px; font-weight: bold; border-bottom: 2px solid #1e293b; margin: 0 0 8px 0; padding-bottom: 3px; text-transform: uppercase; color: #0f172a; letter-spacing: 0.02em;">
                        <?= lang('Thobe.fabric') ?>
                    </h4>
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                        <?php if (!empty($thobe_detail['fabric_item_number'])): ?>
                            <tr style="border-bottom: 1px dashed #cbd5e1;">
                                <td style="padding: 5px 2px; font-weight: bold; text-align: start; color: #334155;">
                                    <?= lang('Thobe.fabric_item_number') ?>
                                </td>
                                <td style="padding: 5px 2px; text-align: end; font-weight: 700; color: #000000;">
                                    <?= esc($thobe_detail['fabric_item_number']) ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if (!empty($thobe_detail['fabric_quantity'])): ?>
                            <tr style="border-bottom: 1px dashed #cbd5e1;">
                                <td style="padding: 5px 2px; font-weight: bold; text-align: start; color: #334155;">
                                    <?= lang('Thobe.fabric_quantity') ?>
                                </td>
                                <td style="padding: 5px 2px; text-align: end; font-weight: 700; color: #000000;">
                                    <?= esc((float) $thobe_detail['fabric_quantity']) ?>
                                    <?= lang('Thobe.m') ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            <?php endif; ?>

            <!-- Measurements Section -->
            <div class="thobe-print-section" style="margin-bottom: 15px; page-break-inside: avoid;">
                <h4
                    style="font-size: 11px; font-weight: bold; border-bottom: 2px solid #1e293b; margin: 0 0 8px 0; padding-bottom: 3px; text-transform: uppercase; color: #0f172a; letter-spacing: 0.02em;">
                    <?= lang('Thobe.measurement_fields') ?>
                </h4>
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <?php foreach ($thobe_measurements as $m): ?>
                        <?php
                        $val = $thobe_detail['measurements'][$m['measurement_id']] ?? '';
                        if ($val === '')
                            continue; // Skip empty measurements for clean print
                        ?>
                        <tr style="border-bottom: 1px dashed #cbd5e1;">
                            <td style="padding: 5px 2px; font-weight: bold; text-align: start; color: #334155;">
                                <?= esc($m['label']) ?>
                            </td>
                            <td style="padding: 5px 2px; text-align: end; font-weight: 700; color: #000000;">
                                <?php
                                if ($m['value_type'] == 'boolean') {
                                    echo $val ? lang('Common.yes') : lang('Common.no');
                                } else {
                                    echo esc($val);
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- Style Options Section -->
            <div class="thobe-print-section" style="page-break-inside: avoid;">
                <h4
                    style="font-size: 11px; font-weight: bold; border-bottom: 2px solid #1e293b; margin: 0 0 8px 0; padding-bottom: 3px; text-transform: uppercase; color: #0f172a; letter-spacing: 0.02em;">
                    <?= lang('Thobe.style_options') ?>
                </h4>
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <?php foreach ($thobe_options as $group): ?>
                        <?php
                        $selected_val_id = $thobe_detail['options'][$group['option_group_id']] ?? null;
                        if (!$selected_val_id)
                            continue;
                        $selected_val_name = '';
                        $selected_val_image = '';
                        foreach ($group['values'] as $v) {
                            if ($v['option_value_id'] == $selected_val_id) {
                                $selected_val_name = $v['name'];
                                $selected_val_image = $v['image'];
                            }
                        }
                        if (empty($selected_val_name))
                            continue;
                        ?>
                        <tr style="border-bottom: 1px dashed #cbd5e1;">
                            <td
                                style="padding: 5px 2px; font-weight: bold; text-align: start; color: #334155; vertical-align: middle;">
                                <?= esc($group['name']) ?>
                            </td>
                            <td
                                style="padding: 5px 2px; text-align: end; font-weight: 700; color: #000000; vertical-align: middle;">
                                <div
                                    style="display: inline-flex; align-items: center; justify-content: flex-end; gap: 6px;">
                                    <span style="vertical-align: middle;"><?= esc($selected_val_name) ?></span>
                                    <?php if (!empty($selected_val_image)): ?>
                                        <img src="<?= base_url('uploads/thobe_options/' . $selected_val_image) ?>"
                                            class="thobe-style-image"
                                            style="height: 40px; width: 40px; object-fit: cover; border-radius: 4px; display: inline-block; vertical-align: middle;">
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- Notes Section -->
            <?php if (!empty($thobe_detail['notes'])): ?>
                <div class="thobe-print-section" style="page-break-inside: avoid;">
                    <h4
                        style="font-size: 11px; font-weight: bold; border-bottom: 2px solid #1e293b; margin: 0 0 8px 0; padding-bottom: 3px; text-transform: uppercase; color: #0f172a; letter-spacing: 0.02em;">
                        <?= lang('Common.notes') ?>
                    </h4>
                    <div
                        style="border: 1px solid #cbd5e1; padding: 6px; font-size: 11px; border-radius: 4px; background-color: #f8fafc; color: #334155; min-height: 40px; text-align: start; line-height: 1.4;">
                        <?= nl2br(esc($thobe_detail['notes'])) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>