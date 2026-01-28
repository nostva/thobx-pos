<?php
/**
 * @var array $definition_names
 * @var array $definition_values
 * @var int $item_id
 * @var array $config
 */
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 pt-4 border-t border-slate-100">
    <div class="form-group w-full">
        <?= form_label(lang('Attributes.definition_name'), 'definition_name_label', ['class' => 'label text-sm font-medium text-slate-700 mb-1 block text-start']) ?>
        <?= form_dropdown([
            'name'     => 'definition_name',
            'options'  => $definition_names,
            'selected' => -1,
            'class'    => 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 text-start',
            'id'       => 'definition_name'
        ]) ?>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
<?php foreach ($definition_values as $definition_id => $definition_value) { ?>

    <div class="form-group w-full">
        <?= form_label($definition_value['definition_name'], $definition_value['definition_name'], ['class' => 'label text-sm font-medium text-slate-700 mb-1 block text-start']) ?>
        <div class="relative flex">
            <div class="flex-grow">
                <?php
                echo form_hidden("attribute_ids[$definition_id]", strval($definition_value['attribute_id']));
                $attribute_value = $definition_value['attribute_value'];

                $common_class = 'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 text-start';

                switch ($definition_value['definition_type']) {
                    case DATE:
                        $value = (empty($attribute_value) || empty($attribute_value->attribute_date)) ? NOW : strtotime($attribute_value->attribute_date);
                        echo form_input([
                            'name'               => "attribute_links[$definition_id]",
                            'value'              => to_date($value),
                            'class'              => $common_class . ' datetime',
                            'data-definition-id' => $definition_id,
                            'readonly'           => 'true'
                        ]);
                        break;
                    case DROPDOWN:
                        $selected_value = $definition_value['selected_value'];
                        echo form_dropdown([
                            'name'               => "attribute_links[$definition_id]",
                            'options'            => $definition_value['values'],
                            'selected'           => $selected_value,
                            'class'              => $common_class,
                            'data-definition-id' => $definition_id
                        ]);
                        break;
                    case TEXT:
                        $value = (empty($attribute_value) || empty($attribute_value->attribute_value)) ? $definition_value['selected_value'] : $attribute_value->attribute_value;
                        echo form_input([
                            'name'               => "attribute_links[$definition_id]",
                            'value'              => $value,
                            'class'              => $common_class . ' valid_chars',
                            'data-definition-id' => $definition_id
                        ]);
                        break;
                    case DECIMAL:
                        $value = (empty($attribute_value) || empty($attribute_value->attribute_decimal)) ? $definition_value['selected_value'] : $attribute_value->attribute_decimal;
                        echo form_input([
                            'name'               => "attribute_links[$definition_id]",
                            'value'              => to_decimals((float)$value),
                            'class'              => $common_class . ' valid_chars',
                            'data-definition-id' => $definition_id
                        ]);
                        break;
                    case CHECKBOX:
                        $value = (empty($attribute_value) || empty($attribute_value->attribute_value)) ? $definition_value['selected_value'] : $attribute_value->attribute_value;

                        echo '<div class="flex items-center gap-2 h-10">';
                        echo form_input([
                            'type'               => 'hidden',
                            'name'               => "attribute_links[$definition_id]",
                            'id'                 => "attribute_links[$definition_id]",
                            'value'              => 0,
                            'data-definition-id' => $definition_id
                        ]);
                        echo form_checkbox([
                            'name'               => "attribute_links[$definition_id]",
                            'id'                 => "attribute_links[$definition_id]",
                            'value'              => 1,
                            'checked'            => $value == 1,
                            'class'              => 'w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500',
                            'data-definition-id' => $definition_id
                        ]);
                        echo '</div>';
                        break;
                }
                ?>
            </div>
            <button type="button" class="ms-2 px-2 py-1 text-red-500 hover:text-red-700 transition-colors remove_attribute_btn" title="<?= lang('Common.remove') ?>">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

<?php } ?>
</div>

<script type="text/javascript">
    (function() {
        <?= view('partial/datepicker_locale', ['config' => '{ minView: 2, format: "' . dateformat_bootstrap($config['dateformat'] . '"}')]) ?>

        var enable_delete = function() {
            $('.remove_attribute_btn').click(function() {
                $(this).parents('.form-group').remove();
            });
        };

        enable_delete();

        $("input[name*='attribute_links']").change(function() {
            var definition_id = $(this).data('definition-id');
            $("input[name='attribute_ids[" + definition_id + "]']").val('');
        }).autocomplete({
            source: function(request, response) {
                $.get('<?= 'attributes/suggestAttribute/' ?>' + this.element.data('definition-id') + '?term=' + request.term, function(data) {
                    return response(data);
                }, 'json');
            },
            appendTo: '.modal-content',
            select: function(event, ui) {
                event.preventDefault();
                $(this).val(ui.item.label);
            },
            delay: 10
        });

        var definition_values = function() {
            var result = {};
            $("[name*='attribute_links'").each(function() {
                var definition_id = $(this).data('definition-id');
                result[definition_id] = $(this).val();
            });
            return result;
        };

        var refresh = function() {
            var definition_id = $("#definition_name option:selected").val();
            var attribute_values = definition_values();
            attribute_values[definition_id] = '';
            $('#attributes').load('<?= "items/attributes/$item_id" ?>', {
                'definition_ids': JSON.stringify(attribute_values)
            }, enable_delete);
        };

        $('#definition_name').change(function() {
            refresh();
        });
    })();
</script>
