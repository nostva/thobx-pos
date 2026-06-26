<?php
/**
 * @var array $thobe_option_groups
 */
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="flex justify-between items-center">
            <strong><?= lang('Thobe.option_groups_values') ?></strong>
            <button type="button" class="btn btn-success btn-xs modal-dlg" data-href="<?= site_url('config/viewThobeOptionGroup/-1') ?>" data-btn-submit="<?= lang('Common.submit') ?>" title="<?= lang('Thobe.add_group') ?>">
                <span class="glyphicon glyphicon-plus"></span> <?= lang('Thobe.add_group') ?>
            </button>
        </div>
    </div>
    <div class="panel-body" style="padding: 0;">
        <table class="table table-condensed table-striped" style="margin-bottom:0;">
            <thead>
                <tr>
                    <th><?= lang('Thobe.group_name') ?></th>
                    <th><?= lang('Thobe.option_values') ?></th>
                    <th><?= lang('Thobe.sort_order') ?></th>
                    <th class="text-right"><?= lang('Common.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($thobe_option_groups)): ?>
                    <tr><td colspan="4" class="text-center text-muted"><?= lang('Thobe.no_option_groups') ?></td></tr>
                <?php else: ?>
                    <?php foreach ($thobe_option_groups as $group): ?>
                        <tr>
                            <td><strong><?= esc($group['name']) ?></strong></td>
                            <td>
                                <?php 
                                    $valNames = array_column($group['values'], 'name');
                                    echo esc(implode(', ', $valNames));
                                ?>
                            </td>
                            <td><?= esc($group['sort_order']) ?></td>
                            <td class="text-right">
                                <button type="button" class="btn btn-default btn-xs modal-dlg" data-href="<?= site_url('config/viewThobeOptionGroup/' . $group['option_group_id']) ?>" data-btn-submit="<?= lang('Common.submit') ?>" title="Edit Option Group">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                                <button type="button" class="btn btn-danger btn-xs delete-group-btn" data-id="<?= $group['option_group_id'] ?>">
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
    $(document).off('click', '.delete-group-btn').on('click', '.delete-group-btn', function() {
        if (confirm('<?= lang('Thobe.confirm_delete_group') ?>')) {
            var id = $(this).data('id');
            $.post('<?= site_url('config/deleteThobeOptionGroup') ?>', { id: id }, function(response) {
                if (response.success) {
                    $.notify({ message: 'Group deleted.' }, { type: 'success' });
                    $('#thobe_option_groups_container').load('<?= site_url('config/thobeOptionGroups') ?>', function() {
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
