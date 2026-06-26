<?php
/**
 * @var int $option_group_id
 * @var string $controller_name
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open_multipart('config/saveThobeOptionValue', ['id' => 'thobe_option_value_form', 'class' => 'form-horizontal']) ?>
    <fieldset id="value_basic_info">
        <input type="hidden" name="option_group_id" value="<?= esc($option_group_id) ?>">

        <div class="form-group form-group-sm">
            <?= form_label('Value Name', 'value_name', ['class' => 'required control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_input([
                    'name'  => 'value_name',
                    'id'    => 'value_name',
                    'class' => 'form-control input-sm required'
                ]) ?>
            </div>
        </div>

        <div class="form-group form-group-sm">
            <?= form_label('Thumbnail', 'value_image', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <input type="file" name="value_image" class="form-control input-sm" accept="image/*">
            </div>
        </div>

        <div class="form-group form-group-sm">
            <?= form_label('Sort Order', 'value_sort_order', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-4">
                <?= form_input([
                    'type'  => 'number',
                    'name'  => 'value_sort_order',
                    'id'    => 'value_sort_order',
                    'class' => 'form-control input-sm',
                    'value' => '0'
                ]) ?>
            </div>
        </div>
    </fieldset>
<?= form_close() ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#thobe_option_value_form').validate($.extend({
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
            value_name: 'required'
        },
        messages: {
            value_name: 'Value name is required'
        }
    }, form_support.error));
});
</script>
