<?php
/**
 * @var bool   $thobe_detail_enable
 * @var bool   $thobe_detail_print
 * @var array  $thobe_measurements
 * @var array  $thobe_option_groups
 * @var string $controller_name
 */
?>

<div class="max-w-4xl animate-in fade-in slide-up">

    <!-- ================================================================== -->
    <!-- General Thobe Settings Form                                          -->
    <!-- Follows the exact same pattern as general_config.php / info_config.php -->
    <!-- ================================================================== -->
    <?= form_open('config/saveThobe', ['id' => 'thobe_config_form', 'class' => 'form-horizontal']) ?>
    <div id="config_wrapper">
        <fieldset id="thobe_config_info">
            <ul id="thobe_error_message_box" class="error_message_box"></ul>

            <h3 class="text-xl font-bold text-slate-800 mb-2"><?= lang('Thobe.thobe_detail_config') ?></h3>
            <p class="text-slate-500 mb-6"><?= lang('Thobe.thobe_detail_config_desc') ?></p>

            <div class="form-group form-group-sm">
                <?= form_label(lang('Thobe.enable_thobe_detail_module'), 'thobe_detail_enable', ['class' => 'control-label col-xs-4']) ?>
                <div class="col-xs-1">
                    <?= form_checkbox([
                        'name'    => 'thobe_detail_enable',
                        'id'      => 'thobe_detail_enable',
                        'value'   => '1',
                        'checked' => (bool) $thobe_detail_enable
                    ]) ?>
                </div>
                <div class="col-xs-5">
                    <p class="help-block"><?= lang('Thobe.enable_thobe_detail_module_desc') ?></p>
                </div>
            </div>

            <div class="form-group form-group-sm">
                <?= form_label(lang('Thobe.print_thobe_detail'), 'thobe_detail_print', ['class' => 'control-label col-xs-4']) ?>
                <div class="col-xs-1">
                    <?= form_checkbox([
                        'name'    => 'thobe_detail_print',
                        'id'      => 'thobe_detail_print',
                        'value'   => '1',
                        'checked' => (bool) $thobe_detail_print
                    ]) ?>
                </div>
                <div class="col-xs-5">
                    <p class="help-block"><?= lang('Thobe.print_thobe_detail_desc') ?></p>
                </div>
            </div>

            <?= form_submit([
                'name'  => 'submit_thobe',
                'id'    => 'submit_thobe',
                'value' => lang('Common.submit'),
                'class' => 'btn btn-primary btn-sm pull-right'
            ]) ?>
        </fieldset>
    </div>
    <?= form_close() ?>

    <hr class="my-8 border-slate-200">

    <!-- ================================================================== -->
    <!-- Measurement Fields & Option Groups                                   -->
    <!-- ================================================================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Measurements -->
        <div>
            <div id="thobe_measurements_container">
                <?= view('partial/thobe_measurements', ['thobe_measurements' => $thobe_measurements]) ?>
            </div>
        </div>

        <!-- Option Groups -->
        <div>
            <div id="thobe_option_groups_container">
                <?= view('partial/thobe_option_groups', ['thobe_option_groups' => $thobe_option_groups]) ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Follow the exact pattern of general_config.php:
    // form_support.handler already provides submitHandler (ajaxSubmit + json + notify)
    $('#thobe_config_form').validate($.extend(form_support.handler, {
        errorLabelContainer: '#thobe_error_message_box',
        rules: {},
        messages: {}
    }));
});
</script>
