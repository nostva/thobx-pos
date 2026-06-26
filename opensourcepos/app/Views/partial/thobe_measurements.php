<?php
/**
 * @var array $thobe_measurements
 */
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="flex justify-between items-center">
            <strong><?= lang('Thobe.measurement_fields') ?></strong>
            <button type="button" class="btn btn-success btn-xs modal-dlg" data-href="<?= site_url('config/viewThobeMeasurement/-1') ?>" data-btn-submit="<?= lang('Common.submit') ?>" title="<?= lang('Thobe.add_field') ?>">
                <span class="glyphicon glyphicon-plus"></span> <?= lang('Thobe.add_field') ?>
            </button>
        </div>
    </div>
    <div class="panel-body" style="padding: 0;">
        <table class="table table-condensed table-striped" style="margin-bottom:0;">
            <thead>
                <tr>
                    <th><?= lang('Common.name') ?></th>
                    <th><?= lang('Thobe.type') ?></th>
                    <th><?= lang('Thobe.sort_order') ?></th>
                    <th class="text-right"><?= lang('Common.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($thobe_measurements)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted"><?= lang('Thobe.no_measurements') ?></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($thobe_measurements as $m): ?>
                        <tr>
                            <td><?= esc($m['label']) ?></td>
                            <td><span class="label label-default"><?= esc($m['value_type']) ?></span></td>
                            <td><?= esc($m['sort_order']) ?></td>
                            <td class="text-right">
                                <button type="button" class="btn btn-default btn-xs modal-dlg" data-href="<?= site_url('config/viewThobeMeasurement/' . $m['measurement_id']) ?>" data-btn-submit="<?= lang('Common.submit') ?>" title="Edit Measurement Field">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                                <button type="button" class="btn btn-danger btn-xs delete-measurement-btn" data-id="<?= $m['measurement_id'] ?>">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    dialog_support.init('button.modal-dlg');

    // --- Delete buttons ---
    $(document).off('click', '.delete-measurement-btn').on('click', '.delete-measurement-btn', function() {
        if (confirm('<?= lang('Thobe.confirm_delete_measurement') ?>')) {
            var id = $(this).data('id');
            $.post('<?= site_url('config/deleteThobeMeasurement') ?>', { id: id }, function(response) {
                if (response.success) {
                    $.notify({ message: 'Deleted.' }, { type: 'success' });
                    $('#thobe_measurements_container').load('<?= site_url('config/thobeMeasurements') ?>', function() {
                        if (typeof window.lucide !== 'undefined') window.lucide.createIcons();
                    });
                } else {
                    $.notify({ message: 'Delete failed.' }, { type: 'danger' });
                }
            }, 'json');
        }
    });
});
</script>
