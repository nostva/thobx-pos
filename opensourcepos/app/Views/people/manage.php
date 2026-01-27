<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $config
 */
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    $(document).ready(function() {
        <?= view('partial/bootstrap_tables_locale') ?>

        table_support.init({
            resource: '<?= esc($controller_name) ?>',
            headers: <?= $table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'people.person_id',
            enableActions: function() {
                var email_disabled = $("td input:checkbox:checked").parents("tr").find("td a[href^='mailto:']").length == 0;
                $("#email").prop('disabled', email_disabled);
            }
        });

        $("#email").click(function(event) {
            var recipients = $.map($("tr.selected a[href^='mailto:']"), function(element) {
                return $(element).attr('href').replace(/^mailto:/, '');
            });
            location.href = "mailto:" + recipients.join(",");
        });
    });
</script>

<div id="title_bar" class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-slate-800"><?= lang('Module.' . $controller_name) ?></h2>
    <div class="flex gap-2">
        <?php if ($controller_name === 'customers') { ?>
            <button class="btn btn-info btn-sm flex items-center gap-2 modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= "$controller_name/csvImport" ?>" title="<?= lang(ucfirst($controller_name) . '.import_items_csv') ?>">
                <i data-lucide="file-up" class="w-4 h-4"></i>
                <span><?= lang('Common.import_csv') ?></span>
            </button>
        <?php } ?>
        <button class="btn btn-info btn-sm flex items-center gap-2 modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= "$controller_name/view" ?>" title="<?= lang(ucfirst($controller_name) . '.new') ?>">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span><?= lang(ucfirst($controller_name) . '.new') ?></span>
        </button>
    </div>
</div>

<div id="toolbar" class="flex gap-3 mb-6 items-center">
    <div class="flex gap-2" role="toolbar">
        <button id="delete" class="btn btn-default btn-sm flex items-center gap-2 text-red-600 hover:bg-red-50 hover:border-red-200">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
            <span><?= lang('Common.delete') ?></span>
        </button>
        <button id="email" class="btn btn-default btn-sm flex items-center gap-2">
            <i data-lucide="mail" class="w-4 h-4"></i>
            <span><?= lang('Common.email') ?></span>
        </button>
    </div>
</div>

<div id="table_holder">
    <table id="table"></table>
</div>

<?= view('partial/footer') ?>
