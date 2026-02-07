<?php
/**
 * Tabular report content only (no header/footer) - for AJAX responses
 * This view receives all the data variables and passes them to the component
 */

// Ensure config is available
if (!isset($config)) {
    $config = config('App');
}

// Render the report table component
echo view('reports/components/report_table', get_defined_vars());
