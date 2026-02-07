<?php /** @var array $table_headers */ ?>
<div class="relative bg-white rounded-xl h-[550px] flex flex-col overflow-hidden font-sans border border-slate-100"
    id="categories-manager-container">
    <div id="categories-list-view"
        class="flex flex-col h-full absolute inset-0 transition-all duration-300 ease-out transform translate-x-0">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-white">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">
                    <?= lang('Expenses_categories.manage') ?>
                </h3>
                <div class="flex items-center gap-3">
                    <p class="text-xs text-slate-500 font-medium">
                        <?= lang('Expenses_categories.manage_sub') ?>
                    </p>
                    <label class="flex items-center gap-1.5 cursor-pointer group">
                        <input type="checkbox" id="chk-show-deleted"
                            class="w-3.5 h-3.5 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500/20">
                        <span
                            class="text-[10px] uppercase tracking-wider font-bold text-slate-400 group-hover:text-slate-600 transition-colors">
                            <?= lang('Expenses_categories.show_deleted') ?>
                        </span>
                    </label>
                </div>
            </div>
            <button id="btn-add-category"
                class="bg-emerald-500 hover:bg-emerald-600 active:scale-95 transition-all text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <?= lang('Expenses_categories.add_item') ?>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-2 bg-slate-50/30">
            <div id="category-loader" class="flex flex-col justify-center items-center py-20 hidden">
                <div class="w-12 h-12 rounded-full border-4 border-slate-100 border-t-emerald-500 animate-spin mb-3">
                </div>
                <span class="text-sm text-slate-400 font-medium">
                    <?= lang('Expenses_categories.fetching_categories') ?>
                </span>
            </div>
            <ul id="categories-list" class="space-y-2 p-2"></ul>
        </div>
    </div>
    <div id="categories-form-view"
        class="flex flex-col h-full absolute inset-0 transition-all duration-300 ease-out transform translate-x-full bg-white z-10 border-l border-slate-50">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-white">
            <div>
                <h3 id="form-title" class="font-bold text-slate-800 text-lg">
                    <?= lang('Expenses_categories.new') ?>
                </h3>
                <p class="text-xs text-slate-500 font-medium">
                    <?= lang('Expenses_categories.new_sub') ?>
                </p>
            </div>
            <button id="btn-cancel-form"
                class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="flex-1 p-8 overflow-y-auto">
            <form id="category-form" class="space-y-6 max-w-md mx-auto">
                <input type="hidden" id="expense_category_id" name="expense_category_id" value="-1">
                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <i data-lucide="tag" class="w-3.5 h-3.5 text-slate-400"></i>
                        <?= lang('Expenses_categories.name') ?>
                    </label>
                    <input type="text" id="category_name" name="category_name"
                        placeholder="<?= lang('Expenses_categories.name_placeholder') ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none text-slate-700 font-medium"
                        required>
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <i data-lucide="file-text" class="w-3.5 h-3.5 text-slate-400"></i>
                        <?= lang('Expenses_categories.description') ?>
                    </label>
                    <textarea id="category_description" name="category_description" rows="4"
                        placeholder="<?= lang('Expenses_categories.description_placeholder') ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none text-slate-700 font-medium resize-none"></textarea>
                </div>
                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" id="btn-cancel-form-2"
                        class="px-5 py-2.5 rounded-xl text-slate-500 hover:text-slate-800 font-semibold transition-colors">
                        <?= lang('Expenses_categories.discard') ?>
                    </button>
                    <button type="submit" id="btn-save-category"
                        class="bg-emerald-500 hover:bg-emerald-600 active:scale-95 transition-all text-white px-8 py-2.5 rounded-xl font-bold shadow-lg shadow-emerald-500/20 flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <?= lang('Expenses_categories.save') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        const $listView = $('#categories-list-view');
        const $formView = $('#categories-form-view');
        const $list = $('#categories-list');
        const $container = $('#categories-manager-container');

        // JS Translations
        const trans = {
            archived: "<?= lang('Expenses_categories.archived') ?>",
            noDescription: "<?= lang('Common.none') ?>",
            noCategories: "<?= lang('Expenses_categories.no_categories_found') ?>",
            loading: "<?= lang('Expenses_categories.fetching_categories') ?>",
            edit: "<?= lang('Common.edit') ?>",
            delete: "<?= lang('Common.delete') ?>",
            restore: "<?= lang('Expenses_categories.restore') ?>",
            confirmDelete: "<?= lang('Expenses_categories.confirm_delete_msg') ?>",
            restoredMsg: "<?= lang('Expenses_categories.restore_successful') ?>",
            failedMsg: "<?= lang('Expenses_categories.failed_to_load') ?>",
            saving: "<?= lang('Common.wait') ?>...",
            newTitle: "<?= lang('Expenses_categories.new') ?>",
            editTitle: "<?= lang('Expenses_categories.update') ?>"
        };

        loadCategories();
        if (window.lucide) window.lucide.createIcons({ root: $container[0] });

        $('#btn-add-category').off('click').on('click', function () { showForm(); });
        $('#btn-cancel-form, #btn-cancel-form-2').off('click').on('click', function () { showList(); });
        $('#category-form').off('submit').on('submit', function (e) { e.preventDefault(); saveCategory(); });
        $('#chk-show-deleted').on('change', function () { loadCategories(); });

        function loadCategories() {
            $('#category-loader').removeClass('hidden');
            $list.empty();
            const showDeleted = $('#chk-show-deleted').is(':checked');

            $.get('expenses_categories/search', { limit: 100, include_deleted: showDeleted }, function (data) {
                try {
                    const response = typeof data === 'string' ? JSON.parse(data) : data;
                    $('#category-loader').addClass('hidden');
                    if (response.rows && response.rows.length > 0) {
                        response.rows.forEach(function (cat) {
                            const isDeleted = parseInt(cat.deleted) === 1;
                            const li = $(`
                            <li class="p-4 bg-white border ${isDeleted ? 'border-amber-100 bg-amber-50/10 opacity-60' : 'border-slate-100'} rounded-xl transition-all duration-200 cursor-default ${!isDeleted ? 'hover:border-emerald-200 hover:shadow-md hover:shadow-slate-200/50' : ''}">
                                <div class="flex items-center gap-4 min-w-0 pr-4">
                                    <div class="w-10 h-10 rounded-lg ${isDeleted ? 'bg-amber-50 text-amber-500' : 'bg-emerald-50 text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white'} flex items-center justify-center shrink-0 transition-colors">
                                        <i data-lucide="${isDeleted ? 'history' : 'layout-grid'}" class="w-5 h-5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <div class="font-bold ${isDeleted ? 'text-slate-400 font-medium' : 'text-slate-800'} transition-colors truncate cat-name">${cat.category_name}</div>
                                            ${isDeleted ? `<span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-600 text-[9px] font-black uppercase tracking-tighter">${trans.archived}</span>` : ''}
                                        </div>
                                        <div class="text-xs text-slate-400 font-medium line-clamp-1 cat-desc">${cat.category_description || trans.noDescription}</div>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-1 mt-2">
                                    ${!isDeleted ? `
                                        <button class="btn-edit w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition-all active:scale-90" data-id="${cat.expense_category_id}" title="${trans.edit}">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <button class="btn-delete w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all active:scale-90" data-id="${cat.expense_category_id}" title="${trans.delete}">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    ` : `
                                        <button class="btn-restore bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all active:scale-95 flex items-center gap-1.5" data-id="${cat.expense_category_id}">
                                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> ${trans.restore}
                                        </button>
                                    `}
                                </div>
                            </li>
                        `);
                            $list.append(li);
                        });
                        if (window.lucide) window.lucide.createIcons({ root: $list[0] });
                        $list.find('.btn-edit').click(function () { editCategory($(this).data('id')); });
                        $list.find('.btn-delete').click(function () { deleteCategory($(this).data('id')); });
                        $list.find('.btn-restore').click(function () { restoreCategory($(this).data('id')); });
                    } else {
                        $list.html(`<div class="p-12 text-center flex flex-col items-center justify-center"><div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200"><i data-lucide="package-open" class="w-8 h-8"></i></div><p class="text-slate-400 font-semibold">${trans.noCategories}</p></div>`);
                        if (window.lucide) window.lucide.createIcons({ root: $list[0] });
                    }
                } catch (e) {
                    console.error("Parse error:", e);
                    $('#category-loader').addClass('hidden');
                }
            }).fail(function () {
                $('#category-loader').addClass('hidden');
                $.notify(trans.failedMsg, { type: 'danger' });
            });
        }

        function editCategory(id) {
            $.get('expenses_categories/view/' + id, function (html) {
                const $dom = $('<div>').html(html);
                $('#category_name').val($dom.find('#category_name').val());
                $('#category_description').val($dom.find('#category_description').val());
                $('#expense_category_id').val(id);
                $('#form-title').text(trans.editTitle);
                showForm();
            });
        }

        function saveCategory() {
            const id = $('#expense_category_id').val();
            const $btn = $('#btn-save-category');
            const originalText = $btn.html();

            $btn.prop('disabled', true).html(`<div class="w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></div> ${trans.saving}`);

            $.post('expenses_categories/save/' + id, {
                category_name: $('#category_name').val(),
                category_description: $('#category_description').val(),
                csrf_test_name: get_csrf_hash()
            }, function (response) {
                $btn.prop('disabled', false).html(originalText);
                try {
                    let res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (res.success) {
                        $.notify(res.message, { type: 'success' });
                        showList();
                        loadCategories();
                        if (window.table_support) window.table_support.refresh();
                    } else {
                        $.notify(res.message || 'Error', { type: 'danger' });
                    }
                } catch (e) {
                    console.error("Save response error:", e);
                    $.notify("Error", { type: 'danger' });
                }
            }).fail(function (xhr) {
                $btn.prop('disabled', false).html(originalText);
                $.notify(trans.failedMsg, { type: 'danger' });
            });
        }

        function deleteCategory(id) {
            if (!confirm(trans.confirmDelete)) return;
            $.post('expenses_categories/delete', { 'ids[]': id, csrf_test_name: get_csrf_hash() }, function (response) {
                try {
                    let res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (res.success) {
                        $.notify(res.message, { type: 'success' });
                        loadCategories();
                        if (window.table_support) window.table_support.refresh();
                    } else {
                        $.notify(res.message, { type: 'danger' });
                    }
                } catch (e) { $.notify("Error", { type: 'danger' }); }
            });
        }

        function restoreCategory(id) {
            const $li = $(`.btn-restore[data-id="${id}"]`).closest('li');
            $.post('expenses_categories/save/' + id, {
                category_name: $li.find('.cat-name').text(),
                category_description: $li.find('.cat-desc').text() === trans.noDescription ? '' : $li.find('.cat-desc').text(),
                deleted: 0,
                csrf_test_name: get_csrf_hash()
            }, function (response) {
                try {
                    let res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (res.success) {
                        $.notify(trans.restoredMsg, { type: 'success' });
                        loadCategories();
                        if (window.table_support) window.table_support.refresh();
                    } else {
                        $.notify(res.message, { type: 'danger' });
                    }
                } catch (e) { $.notify("Error", { type: 'danger' }); }
            });
        }

        function showForm() {
            $listView.removeClass('translate-x-0 opacity-100 scale-100').addClass('-translate-x-full opacity-0 scale-95');
            $formView.removeClass('translate-x-full opacity-0 scale-105').addClass('translate-x-0 opacity-100 scale-100');
            if ($('#expense_category_id').val() == '-1') {
                $('#form-title').text(trans.newTitle);
                $('#category-form')[0].reset();
            }
        }

        function showList() {
            $formView.removeClass('translate-x-0 opacity-100 scale-100').addClass('translate-x-full opacity-0 scale-105');
            $listView.removeClass('-translate-x-full opacity-0 scale-95').addClass('translate-x-0 opacity-100 scale-100');
            setTimeout(() => $('#expense_category_id').val('-1'), 300);
        }

        function get_csrf_hash() { return $('input[name="csrf_test_name"]').val() || getCookie('csrf_cookie_name'); }
        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }
    });
</script>