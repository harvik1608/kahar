<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Sale_entry;
use Illuminate\Support\Str;
use Auth;

class ReportController extends Controller
{
    public function daily_report()
    {
        return view('admin.report.daily_report');
    }

    public function load_date_wise_chart(Request $request)
    {
        $categories = [];

        $date = $request->date;
        $entries = Sale_entry::with('customer:id,name')->select("customer_id","amount")->where("date",$date)->get();
        // if(!$entries->isEmpty()) {
        //     foreach($entries as $entry) {
        //         $categories[] = $entry->customer?->name;
        //     }
        // }
        return response()->json([
            'categories' => $entries->pluck('customer.name'),
            'values' => $entries->pluck('amount'),
        ]);
    }
}
