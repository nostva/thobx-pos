<?php
/**
 * @var object $group
 * @var string $controller_name
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open_multipart('config/saveThobeGroup', ['id' => 'thobe_option_group_form', 'class' => 'form-horizontal']) ?>
    <fieldset id="group_basic_info">
        <input type="hidden" name="option_group_id" value="<?= esc($group->option_group_id) ?>">

        <div class="form-group form-group-sm">
            <?= form_label(lang('Thobe.group_name'), 'name', ['class' => 'required control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_input([
                    'name'  => 'name',
                    'id'    => 'name',
                    'class' => 'form-control input-sm required',
                    'value' => $group->name
                ]) ?>
            </div>
        </div>

        <div class="form-group form-group-sm">
            <?= form_label(lang('Thobe.sort_order'), 'sort_order', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-4">
                <?= form_input([
                    'type'  => 'number',
                    'name'  => 'sort_order',
                    'id'    => 'sort_order',
                    'class' => 'form-control input-sm',
                    'value' => $group->sort_order
                ]) ?>
            </div>
        </div>
        <hr style="margin:10px 0;">
        <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-xs-3"></div>
            <div class="col-xs-8">
                <strong><?= lang('Thobe.option_values') ?></strong>
            </div>
        </div>
        <div id="option_values_container">
            <?php if (isset($values) && !empty($values)): ?>
                <?php foreach ($values as $val): ?>
                    <div class="form-group form-group-sm option-value-row">
                        <div class="col-xs-3"></div>
                        <div class="col-xs-3">
                            <input type="text" name="values[<?= $val['option_value_id'] ?>][name]" class="form-control input-sm" value="<?= esc($val['name']) ?>" placeholder="<?= lang('Thobe.value_name') ?>" required>
                        </div>
                        <div class="col-xs-2">
                            <input type="number" name="values[<?= $val['option_value_id'] ?>][sort_order]" class="form-control input-sm" value="<?= $val['sort_order'] ?>" placeholder="<?= lang('Thobe.sort_order') ?>">
                        </div>
                        <div class="col-xs-3">
                            <?php if (!empty($val['image'])): ?>
                                <img src="<?= base_url('uploads/thobe_options/' . $val['image']) ?>" style="height:24px; margin-bottom: 3px; display:block; border-radius:3px;">
                            <?php endif; ?>
                            <input type="file" name="image_<?= $val['option_value_id'] ?>" class="form-control input-sm" accept="image/*">
                        </div>
                        <div class="col-xs-1">
                            <button type="button" class="btn btn-danger btn-sm remove-existing-value" data-id="<?= $val['option_value_id'] ?>"><span class="glyphicon glyphicon-trash"></span></button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="form-group form-group-sm">
            <div class="col-xs-3"></div>
            <div class="col-xs-8">
                <button type="button" class="btn btn-default btn-sm" id="add_option_value_btn"><span class="glyphicon glyphicon-plus"></span> <?= lang('Thobe.add_value') ?></button>
            </div>
        </div>
        <div id="deleted_values_container"></div>
    </fieldset>
<?= form_close() ?>

<script type="text/javascript">
$(document).ready(function() {
    let newValueIndex = 0;
    $('#add_option_value_btn').click(function() {
        let html = `
            <div class="form-group form-group-sm option-value-row">
                <div class="col-xs-3"></div>
                <div class="col-xs-3">
                    <input type="text" name="new_values[${newValueIndex}][name]" class="form-control input-sm" placeholder="<?= lang('Thobe.value_name') ?>" required>
                </div>
                <div class="col-xs-2">
                    <input type="number" name="new_values[${newValueIndex}][sort_order]" class="form-control input-sm" value="0" placeholder="<?= lang('Thobe.sort_order') ?>">
                </div>
                <div class="col-xs-3">
                    <input type="file" name="new_image_${newValueIndex}" class="form-control input-sm" accept="image/*">
                </div>
                <div class="col-xs-1">
                    <button type="button" class="btn btn-danger btn-sm remove-new-value"><span class="glyphicon glyphicon-trash"></span></button>
                </div>
            </div>
        `;
        $('#option_values_container').append(html);
        newValueIndex++;
    });

    $(document).on('click', '.remove-new-value', function() {
        $(this).closest('.option-value-row').remove();
    });

    $(document).on('click', '.remove-existing-value', function() {
        let id = $(this).data('id');
        $('#deleted_values_container').append(`<input type="hidden" name="deleted_values[]" value="${id}">`);
        $(this).closest('.option-value-row').remove();
    });

    $('#thobe_option_group_form').validate($.extend({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                success: function(response) {
                    dialog_support.hide();
                    $('#thobe_option_groups_container').load('<?= site_url('config/thobeOptionGroups') ?>', function() {
                        if (typeof window.lucide !== 'undefined') window.lucide.createIcons();
                    });
                    $.notify(response.message || 'Saved successfully', { type: response.success ? 'success' : 'danger' });
                },
                dataType: 'json'
            });
        },
        errorLabelContainer: '#error_message_box',
        rules: {
            name: 'required'
        },
        messages: {
            name: '<?= lang('Thobe.group_name_required') ?>'
        }
    }, form_support.error));
});
</script>
