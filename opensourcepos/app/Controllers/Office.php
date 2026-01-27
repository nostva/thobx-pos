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

        echo view('home/office', ['stats' => $stats]);
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
