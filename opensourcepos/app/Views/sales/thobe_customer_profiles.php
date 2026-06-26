<?php
/**
 * @var int $customer_id
 * @var string $customer_name
 * @var array $profiles
 * @var array $measurements
 * @var array $config
 */
?>
<style>
    #profiles_list_table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 12px;
    }

    #profiles_list_table td {
        vertical-align: middle !important;
    }

    .apply-profile-btn {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
    }

    .apply-profile-btn:hover {
        background-color: #059669 !important;
        border-color: #059669 !important;
        color: #fff !important;
    }
</style>

<div class="table-responsive">
    <table class="table table-striped table-hover" id="profiles_list_table">
        <thead>
            <tr>
                <th><?= lang('Thobe.profile_name') ?></th>
                <th><?= lang('Thobe.measurement_fields') ?></th>
                <th class="text-right"><?= lang('Common.actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($profiles)): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted p-4"><?= lang('Thobe.no_profiles') ?></td>
                </tr>
            <?php else: ?>
                <?php foreach ($profiles as $profile): ?>
                    <tr>
                        <td class="font-bold" style="font-weight: bold;"><?= $profile['name'] ?></td>
                        <td>
                            <small class="text-foreground">
                                <?php
                                $summary = [];
                                foreach ($measurements as $m) {
                                    if (isset($profile['values'][$m['measurement_id']])) {
                                        $val = $profile['values'][$m['measurement_id']];
                                        if ($m['value_type'] == 'boolean') {
                                            if ($val == '1') {
                                                $summary[] = esc($m['label']);
                                            }
                                        } else {
                                            $summary[] = esc($m['label']) . ": " . esc($val);
                                        }
                                    }
                                }
                                echo !empty($summary) ? implode(', ', $summary) : lang('Thobe.none');
                                ?>
                            </small>
                        </td>
                        <td class="text-right">
                            <div class="flex flex-row gap-2">
                                <button type="button" class="btn btn-success apply-profile-btn"
                                    data-values="<?= esc(json_encode($profile['values'])) ?>"
                                    data-name="<?= esc($profile['name']) ?>" data-profile-id="<?= $profile['profile_id'] ?>"
                                    style="padding: 3px 8px;">
                                    <i data-lucide="square-mouse-pointer" class="w-3.5 h-3.5"></i>
                                </button>
                                <button type="button" class="btn btn-danger delete-profile-btn"
                                    data-profile-id="<?= $profile['profile_id'] ?>" style="padding: 3px 8px;">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Apply profile measurements to parent register
        $('.apply-profile-btn').click(function () {
            var profileId = $(this).data('profile-id') || '';
            var name = $(this).data('name') || '';
            var values = $(this).data('values') || {};


            let data = $('#thobe_detail_form').serializeArray();
            data.push({
                name: 'thobe_data[selected_profile_id]',
                value: profileId
            });
            $.post('<?= site_url("sales/setThobeData") ?>', data);

            $('#thobe_detail_form input[name="thobe_data[measurements_profile_name]"]')
                .val(name);

            // Loop over parent inputs
            $('#thobe_detail_form input[name^="thobe_data[measurements]"]').each(function () {
                var nameAttr = $(this).attr('name');
                var match = nameAttr.match(/\[measurements\]\[(\d+)\]/);
                if (match) {
                    var id = match[1];
                    var val = values[id] !== undefined ? values[id] : '';

                    if ($(this).attr('type') === 'checkbox') {
                        $(this).prop('checked', val == '1' || val === true || val === 'true');
                    } else {
                        $(this).val(val);
                    }
                }
            });

            // Trigger change event to save the new measurements to session state
            $('#thobe_detail_form input, #thobe_detail_form select').first().trigger('change');

            // Close modal using standard Bootstrap Dialog
            BootstrapDialog.closeAll();
        });

        // Delete customer profile
        $('.delete-profile-btn').click(function () {
            var btn = $(this);
            var profileId = btn.data('profile-id');

            if (confirm("<?= lang('Thobe.confirm_delete_profile') ?>")) {
                $.post("<?= site_url('sales/deleteThobeCustomerProfile') ?>/" + profileId, function (response) {
                    if (response.success) {
                        $.notify({ message: response.message }, { type: 'success' });
                        // Refresh the modal content
                        var modalBody = btn.closest('.modal-body');
                        if (modalBody.length) {
                            $.get("<?= site_url('sales/thobeCustomerProfiles/' . $customer_id) ?>", function (html) {
                                modalBody.html(html);
                            });
                        }
                    } else {
                        $.notify({ message: response.message }, { type: 'danger' });
                    }
                }, 'json');
            }
        });
    });
</script>