<?= view('partial/header') ?>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<div class="max-w-3xl mx-auto mt-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
            <div class="p-2 bg-emerald-100/50 rounded-lg text-emerald-600">
                <i data-lucide="message-square-plus" class="w-5 h-5"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800"><?= lang('Messages.sms_send') ?></h3>
        </div>

        <div class="p-6">
            <?= form_open("messages/send/", ['id' => 'send_sms_form', 'enctype' => 'multipart/form-data', 'method' => 'post', 'class' => 'space-y-6']) ?>
                
                <div class="space-y-2">
                    <label for="phone" class="block text-sm font-bold text-slate-700">
                        <?= lang('Messages.phone') ?>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </div>
                        <input class="flex h-10 w-full rounded-lg border border-slate-300 bg-white ps-10 pe-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" type="text" name="phone" placeholder="<?= lang('Messages.phone_placeholder') ?>">
                    </div>
                    <p class="text-xs text-slate-500 flex items-center gap-1.5">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i>
                        <?= lang('Messages.multiple_phones') ?>
                    </p>
                </div>

                <div class="space-y-2">
                    <label for="message" class="block text-sm font-bold text-slate-700">
                        <?= lang('Messages.message') ?>
                    </label>
                    <textarea class="flex w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" rows="4" id="message" name="message" placeholder="<?= lang('Messages.message_placeholder') ?>"></textarea>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" id="submit_form" class="btn btn-primary inline-flex items-center gap-2 px-6 py-2.5 rounded-lg font-bold shadow-lg shadow-emerald-200 hover:shadow-emerald-300 transition-all">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <?= lang('Common.submit') ?>
                    </button>
                </div>

            <?= form_close() ?>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>

<script type="text/javascript">
    // Validation and submit handling
    $(document).ready(function() {
        $('#send_sms_form').validate({
            submitHandler: function(form) {
                $(form).ajaxSubmit({
                    success: function(response) {
                        $.notify({
                            message: response.message
                        }, {
                            type: response.success ? 'success' : 'danger'
                        })
                    },
                    dataType: 'json'
                });
            }
        });
    });
</script>
