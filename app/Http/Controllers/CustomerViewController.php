<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerViewController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function showDetail($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.showDetail', compact('customer'));
    }
}