<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class TransactionController extends Controller
{
    public function indexCash()
    {
        session()->flash('page', (object)[
            'page' => 'Transaction',
            'child' => 'database Cash',
        ]);

        return view('components.cash.index');
    }

    public function indexBank()
    {
        session()->flash('page', (object)[
            'page' => 'Transaction',
            'child' => 'database Bank',
        ]);

        return view('components.bank.index');
    }
}
