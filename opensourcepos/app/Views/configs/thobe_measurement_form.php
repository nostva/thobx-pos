<?php
/**
 * @var object $measurement
 * @var string $controller_name
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open('config/saveThobeMeasurements', ['id' => 'thobe_measurement_form', 'class' => 'form-horizontal']) ?>
    <fieldset id="measurement_basic_info">
        <input type="hidden" name="measurement_id" value="<?= esc($measurement->measurement_id) ?>">

        <div class="form-group form-group-sm">
            <?= form_label(lang('Thobe.label'), 'label', ['class' => 'required control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_input([
                    'name'  => 'label',
                    'id'    => 'label',
                    'class' => 'form-control input-sm required',
                    'value' => $measurement->label
                ]) ?>
            </div>
        </div>

        <div class="form-group form-group-sm">
            <?= form_label(lang('Thobe.value_type'), 'value_type', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_dropdown('value_type', [
                    'string' => lang('Thobe.value_type_string'),
                    'number' => lang('Thobe.value_type_number')
                ], $measurement->value_type, ['class' => 'form-control input-sm', 'id' => 'value_type']) ?>
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
                    'value' => $measurement->sort_order
                ]) ?>
            </div>
        </div>
    </fieldset>
<?= form_close() ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#thobe_measurement_form').validate($.extend({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                success: function(response) {
                    dialog_support.hide();
                    // Reload the measurements section
                    $('#thobe_measurements_container').load('<?= site_url('config/thobeMeasurements') ?>', function() {
                        if (typeof window.lucide !== 'undefined') window.lucide.createIcons();
                    });
                    $.notify(response.message || 'Saved successfully', { type: response.success ? 'success' : 'danger' });
                },
                dataType: 'json'
            });
        },
        errorLabelContainer: '#error_message_box',
        rules: {
            label: 'required'
        },
        messages: {
            label: '<?= lang('Thobe.label_required') ?>'
        }
    }, form_support.error));
});
</script>
