<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(){
        $transactions = Transaction::with([ //with untuk mengambil semua data yang terdapat di dalam tabel
            'package',
            'user'
        ])->get();
        //dd($transactions);
        return view('admin.transaction', ['transactions' => $transactions]);
    }
}
