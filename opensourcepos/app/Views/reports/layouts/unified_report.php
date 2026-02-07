<?php
/**
 * Unified Report Layout - Clean component-based approach
 * @var string $report_title
 * @var array $filter_data - Data for filter component
 * @var array $table_data - Data for table component
 * @var array $config
 */
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<div class="max-w-6xl mx-auto mt-6 animate-in fade-in slide-up">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            <?= $report_title ?>
        </h2>
    </div>

    <!-- Filters Component -->
    <?= view('reports/components/report_filters', $filter_data) ?>

    <!-- Results Container -->
    <div id="results_container">
        <?= view($results_view ?? 'reports/components/report_table', $table_data) ?>
    </div>
</div>

<?= view('partial/footer') ?>

<script type="text/javascript">
    $(document).ready(function () {
        // Handle form submission
        $("#report_filter_form").on('submit', function (e) {
            e.preventDefault();

            // Build URL with parameters
            var url = "<?= site_url($filter_data['report_url']) ?>/" +
                encodeURIComponent(start_date) + "/" +
                encodeURIComponent(end_date);

            // Specific Input (Customer/Employee/Supplier)
            if ($("#specific_input_data").length) {
                url += "/" + ($("#specific_input_data").val() || 'all');
            }

            // Sale Type OR Receiving Type
            if ($("#sale_type").length) {
                url += "/" + ($("#sale_type").val() || 'complete');
            } else if ($("#receiving_type").length) {
                url += "/" + ($("#receiving_type").val() || 'all');
            }

            // Payment Type
            if ($("#payment_type").length) {
                url += "/" + ($("#payment_type").val() || 'all');
            }

            <?php if (isset($filter_data['stock_locations']) && count($filter_data['stock_locations']) > 2): ?>
                url += "/" + ($("#location_id").val() || 'all');
            <?php endif; ?>

            // Add discount type if it exists
            if ($("#discount_type").length) {
                url += "/" + ($("#discount_type").val() || '0');
            }

            // Item Count
            if ($("#item_count").length) {
                url += "/" + ($("#item_count").val() || 'all');
            }

            // Navigate to the new URL (full page reload with new parameters)
            window.location.href = url;
        });
    });
</script>