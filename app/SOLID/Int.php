<?php

interface Employee
{
    public function work(): void;

    public function eat(): void;
}

class HumanEmployee implements Employee
{
    public function work(): void
    {
        // ....working
    }

    public function eat(): void
    {
        // ...... eating in lunch break
    }
}

class RobotEmployee implements Employee
{
    public function work(): void
    {
        //.... working much more
    }

    public function eat(): void
    {
        throw new Exception('Robots doesnt eat');
    }
}


class
{
    public function sendEmailsToCustomers(Employee $employee)
    {
        $employee->work();
    }
}
