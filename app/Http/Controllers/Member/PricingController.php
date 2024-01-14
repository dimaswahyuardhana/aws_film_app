<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class PricingController extends Controller
{
    public function index(){
        $standartPackage = Package::where('name' ,'standart')->first();
        $goldPackage = Package::where('name' ,'gold')->first();

        return view('member.pricing' ,[
            'standart' => $standartPackage,
            'gold' => $goldPackage
        ]);
    }
}
