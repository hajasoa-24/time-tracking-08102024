<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_library {

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('conges_model');
        $this->CI->load->model('user_model');
    }

    public function add_pro_rata_leave_monthly()
    {
        // Get all employees from the database
        $this->CI->load->model('user_model');
        $employees = $this->CI->user_model->getAllUser();

        // Loop through all employees and add pro-rata leave
        foreach ($employees as $employee) {
            $this->add_pro_rata_leave($employee->user_id);
        }
    }

    public function add_pro_rata_leave($employee_id)
    {
        // Get the employee's date of joining
        $this->CI->load->model('user_model');
        $employee = $this->CI->user_model->getUserInfos($employee_id);
        $date_of_joining = new DateTime($employee->usr_dateembauche);

        // Get the current date and time
        $now = new DateTime();

        // Calculate the number of months between the employee's date of joining and now
        $months_worked = (($now->format('Y') - $date_of_joining->format('Y')) * 12) + ($now->format('n') - $date_of_joining->format('n'));
        
        // Calculate the pro-rata leave days
        $pro_rata_leave_days = round(($months_worked / 12) * 2.5, 1);

        // Check if the employee has already been credited with the pro-rata leave days for the current month
        $current_month = $now->format('m');
        $current_year = $now->format('Y');
        $existing_leave_entry = $this->CI->conges_model->get_by_employee_id_and_month_year($employee_id, $current_month, $current_year);
        if ($existing_leave_entry) {
            return; // Pro-rata leave has already been added for this employee this month
        }

        // Add the pro-rata leave days to the employee's leave balance
        $this->CI->load->model('leave_balance_model');
        $leave_balance = $this->CI->leave_balance_model->get_by_employee_id($employee_id);
        $leave_balance->balance += $pro_rata_leave_days;
        $this->CI->leave_balance_model->update($leave_balance);

        // Add a record of the pro-rata leave days added to the leave history
        $this->CI->load->model('leave_history_model');
        $leave_history = new stdClass();
        $leave_history->employee_id = $employee_id;
        $leave_history->leave_type_id = 1; // Assuming pro-rata leave has a fixed leave type ID
        $leave_history->leave_days = $pro_rata_leave_days;
        $leave_history->leave_date = $now->format('Y-m-d H:i:s');
        $this->CI->leave_history_model->create($leave_history);
    }

}
