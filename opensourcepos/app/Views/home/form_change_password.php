<?php
/**
 * @var object $person_info
 */
?>

<ul id="error_message_box" class="error_message_box text-sm text-red-500 mb-4 block"></ul>

<?= form_open("home/save/$person_info->person_id", ['id' => 'employee_form', 'class' => 'space-y-4']) ?>
    <fieldset class="flex flex-col gap-4">

        <div class="form-group w-full">
            <?= form_label(lang('Employees.username'), 'username', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block required text-start']) ?>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <?= form_input([
                    'name'     => 'username',
                    'id'       => 'username',
                    'class'    => 'flex h-10 w-full rounded-md border border-slate-300 bg-slate-100 ps-10 pe-3 py-2 text-sm text-slate-600 focus:outline-none cursor-not-allowed text-start',
                    'value'    => $person_info->username,
                    'readonly' => 'readonly'
                ]) ?>
            </div>
        </div>

        <?php $password_label_attributes = $person_info->person_id == "" ? ['class' => 'required label text-sm font-medium text-slate-700 mb-1 block text-start'] : ['class' => 'label text-sm font-medium text-slate-700 mb-1 block text-start']; ?>

        <div class="form-group w-full">
            <?= form_label(lang('Employees.current_password'), 'current_password', $password_label_attributes) ?>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <?= form_password([
                    'name'  => 'current_password',
                    'id'    => 'current_password',
                    'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white ps-10 pe-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-start',
                    'placeholder' => lang('Employees.current_password')
                ]) ?>
            </div>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Employees.password'), 'password', $password_label_attributes) ?>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <?= form_password([
                    'name'  => 'password',
                    'id'    => 'password',
                    'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white ps-10 pe-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-start',
                    'placeholder' => lang('Employees.password')
                ]) ?>
            </div>
        </div>

        <div class="form-group w-full">
            <?= form_label(lang('Employees.repeat_password'), 'repeat_password', $password_label_attributes) ?>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <?= form_password([
                    'name'  => 'repeat_password',
                    'id'    => 'repeat_password',
                    'class' => 'flex h-10 w-full rounded-md border border-slate-300 bg-white ps-10 pe-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-start',
                    'placeholder' => lang('Employees.repeat_password')
                ]) ?>
            </div>
        </div>

    </fieldset>
<?= form_close() ?>

<script type="text/javascript">
    // Validation and submit handling
    $(document).ready(function() {
        $.validator.setDefaults({
            ignore: []
        });

        $.validator.addMethod("notEqualTo", function(value, element, param) {
            return this.optional(element) || value != $(param).val();
        }, '<?= lang('Employees.password_not_must_match') ?>');

        $('#employee_form').validate($.extend({
            submitHandler: function(form) {
                $(form).ajaxSubmit({
                    success: function(response) {
                        dialog_support.hide();
                        $.notify(response.message, {
                            type: response.success ? 'success' : 'danger'
                        });
                    },
                    dataType: 'json'
                });
            },

            rules: {
                current_password: {
                    required: true,
                    minlength: 8
                },
                password: {
                    required: true,
                    minlength: 8,
                    notEqualTo: "#current_password"
                },
                repeat_password: {
                    equalTo: "#password"
                }
            },

            messages: {
                password: {
                    required: "<?= lang('Employees.password_required') ?>",
                    minlength: "<?= lang('Employees.password_minlength') ?>"
                },
                repeat_password: {
                    equalTo: "<?= lang('Employees.password_must_match') ?>"
                }
            }
        }, form_support.error));
    });
</script>
