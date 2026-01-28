<?php

namespace App\Controllers;

use App\Models\Employee;

/**
 * @property Employee employee
 */
class Office extends Secure_Controller
{
    protected Employee $employee;

    public function __construct()
    {
        parent::__construct('office', null, 'office');
    }

    /**
     * @return void
     */
    public function getIndex(): void
    {
        $item_model = model(\App\Models\Item::class);
        $customer_model = model(\App\Models\Customer::class);
        $sale_model = model(\App\Models\Sale::class);

        $stats = [
            'total_items' => $item_model->get_total_rows(),
            'total_customers' => $customer_model->get_total_rows(),
            'total_sales' => $sale_model->get_total_rows(),
            // Today's sales count (simplified)
            'sales_today' => $sale_model->where('DATE(sale_time)', date('Y-m-d'))->countAllResults()
        ];

        // Get current month financial summary
        $summary_sales = model(\App\Models\Reports\Summary_sales::class);
        $summary_expenses = model(\App\Models\Reports\Summary_expenses_categories::class);
        
        $start_date = date('Y-m-01'); // First day of current month
        $end_date = date('Y-m-d');     // Today
        
        $inputs = [
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'sale_type'  => 'all',
            'location_id'=> 'all'
        ];
        
        $sales_summary = $summary_sales->getSummaryData($inputs);
        $expenses_summary = $summary_expenses->getSummaryData($inputs);
        
        $financial_summary = [
            'total_sales' => $sales_summary['total'] ?? 0,
            'total_cost'  => $sales_summary['cost'] ?? 0,
            'gross_profit'=> $sales_summary['profit'] ?? 0,
            'total_expenses' => $expenses_summary['expenses_total_amount'] ?? 0,
            'net_profit'  => ($sales_summary['profit'] ?? 0) - ($expenses_summary['expenses_total_amount'] ?? 0)
        ];

        echo view('home/office', ['stats' => $stats, 'financial_summary' => $financial_summary]);
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $this->employee = model(Employee::class);

        $this->employee->logout();
    }
}
