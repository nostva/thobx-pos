<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultController('Login');

$routes->get('/', 'Login::index');
$routes->get('login', 'Login::index');
$routes->post('login', 'Login::index');

$routes->add('no_access/index/(:segment)', 'No_access::index/$1');
$routes->add('no_access/index/(:segment)/(:segment)', 'No_access::index/$1/$2');

// Financial Summary (unified pattern)
$routes->add('reports/financial_summary/(:any)/(:any)/(:any)', 'Reports::financial_summary/$1/$2/$3');
$routes->add('reports/financial_summary/(:any)/(:any)', 'Reports::financial_summary/$1/$2');
$routes->add('reports/financial_summary', 'Reports::financial_summary');

// Summary Reports (unified pattern)
$routes->add('reports/summary_sales/(:any)/(:any)/(:any)/(:any)', 'Reports::summary_sales/$1/$2/$3/$4');
$routes->add('reports/summary_sales/(:any)/(:any)/(:any)', 'Reports::summary_sales/$1/$2/$3');
$routes->add('reports/summary_sales', 'Reports::summary_sales');

$routes->add('reports/summary_categories/(:any)/(:any)/(:any)/(:any)', 'Reports::summary_categories/$1/$2/$3/$4');
$routes->add('reports/summary_categories/(:any)/(:any)/(:any)', 'Reports::summary_categories/$1/$2/$3');
$routes->add('reports/summary_categories', 'Reports::summary_categories');

$routes->add('reports/summary_customers/(:any)/(:any)/(:any)/(:any)', 'Reports::summary_customers/$1/$2/$3/$4');
$routes->add('reports/summary_customers/(:any)/(:any)/(:any)', 'Reports::summary_customers/$1/$2/$3');
$routes->add('reports/summary_customers', 'Reports::summary_customers');

$routes->add('reports/summary_suppliers/(:any)/(:any)/(:any)/(:any)', 'Reports::summary_suppliers/$1/$2/$3/$4');
$routes->add('reports/summary_suppliers/(:any)/(:any)/(:any)', 'Reports::summary_suppliers/$1/$2/$3');
$routes->add('reports/summary_suppliers', 'Reports::summary_suppliers');

$routes->add('reports/summary_items/(:any)/(:any)/(:any)/(:any)', 'Reports::summary_items/$1/$2/$3/$4');
$routes->add('reports/summary_items/(:any)/(:any)/(:any)', 'Reports::summary_items/$1/$2/$3');
$routes->add('reports/summary_items', 'Reports::summary_items');

$routes->add('reports/summary_employees/(:any)/(:any)/(:any)/(:any)', 'Reports::summary_employees/$1/$2/$3/$4');
$routes->add('reports/summary_employees/(:any)/(:any)/(:any)', 'Reports::summary_employees/$1/$2/$3');
$routes->add('reports/summary_employees', 'Reports::summary_employees');

$routes->add('reports/summary_taxes/(:any)/(:any)/(:any)/(:any)', 'Reports::summary_taxes/$1/$2/$3/$4');
$routes->add('reports/summary_taxes/(:any)/(:any)/(:any)', 'Reports::summary_taxes/$1/$2/$3');
$routes->add('reports/summary_taxes', 'Reports::summary_taxes');

$routes->add('reports/summary_sales_taxes/(:any)/(:any)/(:any)/(:any)', 'Reports::summary_sales_taxes/$1/$2/$3/$4');
$routes->add('reports/summary_sales_taxes/(:any)/(:any)/(:any)', 'Reports::summary_sales_taxes/$1/$2/$3');
$routes->add('reports/summary_sales_taxes', 'Reports::summary_sales_taxes');

$routes->add('reports/summary_discounts/(:any)/(:any)/(:any)/(:any)', 'Reports::summary_discounts/$1/$2/$3/$4');
$routes->add('reports/summary_discounts/(:any)/(:any)/(:any)', 'Reports::summary_discounts/$1/$2/$3');
$routes->add('reports/summary_discounts', 'Reports::summary_discounts');

$routes->add('reports/summary_payments/(:any)/(:any)/(:any)/(:any)', 'Reports::summary_payments/$1/$2/$3/$4');
$routes->add('reports/summary_payments/(:any)/(:any)/(:any)', 'Reports::summary_payments/$1/$2/$3');
$routes->add('reports/summary_payments', 'Reports::summary_payments');

$routes->add('reports/summary_expenses_categories/(:any)/(:any)/(:any)', 'Reports::summary_expenses_categories/$1/$2/$3');
$routes->add('reports/summary_expenses_categories', 'Reports::summary_expenses_categories');

// Fallback for any missed ones (should likely not be hit if we cover all)
$routes->add('reports/summary_(:any)/(:any)/(:any)/(:any)', 'Reports::summary_$1/$2/$3/$4');
$routes->add('reports/summary_(:any)', 'Reports::date_input');

$routes->add('reports/graphical_(:any)/(:any)/(:any)', 'Reports::Graphical_$1/$2/$3/$4');
$routes->add('reports/graphical_summary_expenses_categories', 'Reports::date_input_only');
$routes->add('reports/graphical_summary_discounts', 'Reports::summary_discounts_input');
$routes->add('reports/graphical_(:any)', 'Reports::date_input');

$routes->add('reports/inventory_(:any)/(:any)', 'Reports::Inventory_$1/$2');
$routes->add('reports/inventory_low', 'Reports::inventory_low'); // Already correct
$routes->add('reports/inventory_summary', 'Reports::inventory_summary'); // Changed from input
$routes->add('reports/inventory_summary/(:any)/(:any)/(:any)', 'Reports::inventory_summary/$1/$2/$3');

$routes->add('reports/detailed_(:any)/(:any)/(:any)/(:any)', 'Reports::Detailed_$1/$2/$3/$4');
$routes->add('reports/detailed_sales', 'Reports::detailed_sales'); // Changed from date_input_sales
$routes->add('reports/detailed_sales/(:any)/(:any)/(:any)/(:any)', 'Reports::detailed_sales/$1/$2/$3/$4'); // Added param route
$routes->add('reports/detailed_receivings', 'Reports::detailed_receivings'); // Changed from date_input_recv
$routes->add('reports/detailed_receivings/(:any)/(:any)/(:any)/(:any)', 'Reports::detailed_receivings/$1/$2/$3/$4'); // Added param route

$routes->add('reports/specific_(:any)/(:any)/(:any)/(:any)', 'Reports::Specific_$1/$2/$3/$4');
$routes->add('reports/specific_customers', 'Reports::specific_customers'); // Changed from input
$routes->add('reports/specific_customers/(:any)/(:any)/(:any)/(:any)/(:any)', 'Reports::specific_customers/$1/$2/$3/$4/$5');
$routes->add('reports/specific_employees', 'Reports::specific_employees'); // Changed from input
$routes->add('reports/specific_employees/(:any)/(:any)/(:any)/(:any)', 'Reports::specific_employees/$1/$2/$3/$4');
$routes->add('reports/specific_discounts', 'Reports::specific_discounts'); // Changed from input
$routes->add('reports/specific_discounts/(:any)/(:any)/(:any)/(:any)/(:any)', 'Reports::specific_discounts/$1/$2/$3/$4/$5');
$routes->add('reports/specific_suppliers', 'Reports::specific_suppliers'); // Changed from input
$routes->add('reports/specific_suppliers/(:any)/(:any)/(:any)/(:any)', 'Reports::specific_suppliers/$1/$2/$3/$4');

