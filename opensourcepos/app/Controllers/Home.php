<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;

class Home extends Secure_Controller
{
    public function __construct()
    {
        parent::__construct('home', null, 'home');
    }

    /**
     * @return void
     */
    public function getIndex(): void
    {
        $item_model = model(\App\Models\Item::class);
        $customer_model = model(\App\Models\Customer::class);
        $sale_model = model(\App\Models\Sale::class);

        $filters = [
            "sale_type" => "complete",
            "location_id" => "all",
            "selected_customer" => false,
            "only_invoices" => false,
            "only_cash" => false,
            "only_due" => false,
            "only_check" => false,
            "only_creditcard" => false,
            "only_store_account_payment" => false,
            "only_giftcard" => false,
            "only_suspended" => false,
        ];

        $stats = [
            'total_items' => $item_model->get_total_rows(),
            'total_customers' => $customer_model->get_total_rows(),
            'total_sales' => $sale_model->get_found_rows("", [
                'start_date' => '2000-01-01',
                'end_date' => date('Y-m-d'),
                ...$filters,
            ]),
            'sales_today' => $sale_model->get_found_rows("", [
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d'),
                ...$filters,
            ]),
        ];

        // Get current month financial summary
        $summary_sales = model(\App\Models\Reports\Summary_sales::class);
        $summary_expenses = model(\App\Models\Reports\Summary_expenses_categories::class);

        $start_date = date('Y-m-01'); // First day of current month
        $end_date = date('Y-m-d');     // Today

        $inputs = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'sale_type' => 'complete',
            'location_id' => 'all'
        ];

        $sales_summary = $summary_sales->getSummaryData($inputs);
        $expenses_summary = $summary_expenses->getSummaryData($inputs);

        $financial_summary = [
            'total_sales' => $sales_summary['total'] ?? 0,
            'total_cost' => $sales_summary['cost'] ?? 0,
            'gross_profit' => $sales_summary['profit'] ?? 0,
            'total_expenses' => $expenses_summary['expenses_total_amount'] ?? 0,
            'net_profit' => ($sales_summary['profit'] ?? 0) - ($expenses_summary['expenses_total_amount'] ?? 0)
        ];

        echo view('home/home', ['stats' => $stats, 'financial_summary' => $financial_summary]);
    }

    /**
     * Logs the currently logged in employee out of the system.  Used in app/Views/partial/header.php
     *
     * @return RedirectResponse
     * @noinspection PhpUnused
     */
    public function getLogout(): RedirectResponse
    {
        $this->employee->logout();
        return redirect()->to('login');
    }

    /**
     * Load "change employee password" form
     *
     * @noinspection PhpUnused
     */
    public function getChangePassword(int $employee_id = -1): void    // TODO: Replace -1 with a constant
    {
        $person_info = $this->employee->get_info($employee_id);
        foreach (get_object_vars($person_info) as $property => $value) {
            $person_info->$property = $value;
        }
        $data['person_info'] = $person_info;

        echo view('home/form_change_password', $data);
    }

    /**
     * Change employee password
     */
    public function postSave(int $employee_id = -1): void    // TODO: Replace -1 with a constant
    {
        if (!empty($this->request->getPost('current_password')) && $employee_id != -1) {
            if ($this->employee->check_password($this->request->getPost('username', FILTER_SANITIZE_FULL_SPECIAL_CHARS), $this->request->getPost('current_password'))) {
                $employee_data = [
                    'username' => $this->request->getPost('username', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'hash_version' => 2
                ];

                if ($this->employee->change_password($employee_data, $employee_id)) {
                    echo json_encode([
                        'success' => true,
                        'message' => lang('Employees.successful_change_password'),
                        'id' => $employee_id
                    ]);
                } else { // Failure    // TODO: Replace -1 with constant
                    echo json_encode([
                        'success' => false,
                        'message' => lang('Employees.unsuccessful_change_password'),
                        'id' => -1
                    ]);
                }
            } else {    // TODO: Replace -1 with constant
                echo json_encode([
                    'success' => false,
                    'message' => lang('Employees.current_password_invalid'),
                    'id' => -1
                ]);
            }
        } else {    // TODO: Replace -1 with constant
            echo json_encode([
                'success' => false,
                'message' => lang('Employees.current_password_invalid'),
                'id' => -1
            ]);
        }
    }
}
