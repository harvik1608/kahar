<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Fish;
use App\Models\Sale_entry;
use App\Models\Sale_entry_item;
use App\Models\Payment_method;
use App\Models\Purchase_entry;
use App\Models\Purchase_entry_item;
use Auth;

class SaleController extends Controller
{
    public function index()
    {
        $entries = Sale_entry::with('customer:id,name,phone,avatar')->where("created_by",Auth::user()->id)->orderBy("id","desc")->get();
        return view('admin.sale.list',compact("entries"));
    }

    public function create()
    {
        $sale = null;
        $customers = User::select("id","name")->where("created_by",Auth::user()->id)->where("role",4)->orderBy("name","asc")->get();
        $payment_methods = Payment_method::select("id","name")->orderBy("name","asc")->get();
        return view('admin.sale.add_edit',compact('sale','customers','payment_methods'));  
    }

    public function store(Request $request)
    {
        try {
            $post = $request->all();

            $avatar = "";
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // generate random file name
                $avatar = Str::random(20) . '.' . $image->getClientOriginalExtension();
                $path = $image->move(public_path('uploads/sale'), $avatar);
            }

            $row = new Sale_entry;
            $row->customer_id = $post['customer_id'];
            $row->date = $post['date'];
            $row->time = $post['time'];
            $row->payment_type = $post['payment_type'];
            $row->payment_method = $post['payment_method'];
            $row->note = $post['note'] == "" ? "" : $post['note'];
            $row->avatar = $avatar;
            $row->created_by = Auth::user()->id;
            $row->created_at = date("Y-m-d H:i:s");
            $row->save();
            $sale_entry_id = $row->id;

            $amount = 0;
            $insert_batch_data = array();
            for($i = 0; $i < count($post["fish_id"]); $i ++) {
                $amount = $amount + ($post["quantity"][$i]*$post["amount"][$i]);
                $insert_batch_data[] = array(
                    'sale_entry_id' => $sale_entry_id,
                    'vendor_id' => $post["vendor_id"][$i],
                    'fish_id' => $post["fish_id"][$i],
                    'quantity' => $post["quantity"][$i],
                    'amount' => $post["amount"][$i],
                    'note' => "",
                    'created_at' => date("Y-m-d H:i:s")
                );
            }
            Sale_entry_item::insert($insert_batch_data);
            Sale_entry::where("id",$sale_entry_id)->update(["amount" => $amount]);

            return response()->json(['success' => true,'message' => "Sale entry has been added."], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'message' => $e->getMessage()], 200);
        }
    }

    public function edit($id)
    {
        $sale = Sale_entry::find($id);
        if(!$sale) {
            return redirect()->route("admin.dashboard");
        }
        $customers = User::select("id","name")->where("created_by",Auth::user()->id)->where("role",4)->orderBy("name","asc")->get();
        $payment_methods = Payment_method::select("id","name")->orderBy("name","asc")->get();
        return view('admin.sale.add_edit',compact('sale','customers','payment_methods'));
    }

    public function update(Request $request,$id)
    {
        try {
            $post = $request->all();

            $avatar = $post["old_avatar"];
            if($request->hasFile('image')) {
                $image = $request->file('image');

                // generate random file name
                $avatar = Str::random(20) . '.' . $image->getClientOriginalExtension();
                $path = $image->move(public_path('uploads/sale'), $avatar);

                // delete old image
                if($post["old_avatar"] != "" && file_exists(public_path('uploads/sale/'.$post["old_avatar"]))) {
                    unlink((public_path('uploads/sale/'.$post["old_avatar"])));
                }
            }

            $row = Sale_entry::find($id);
            $row->customer_id = $post['customer_id'];
            $row->date = $post['date'];
            $row->time = $post['time'];
            $row->payment_type = $post['payment_type'];
            $row->payment_method = $post['payment_method'];
            $row->note = $post['note'] == "" ? "" : $post['note'];
            $row->avatar = $avatar;
            $row->updated_by = Auth::user()->id;
            $row->updated_at = date("Y-m-d H:i:s");
            $row->save();
            $sale_entry_id = $id;
            Sale_entry_item::where("sale_entry_id",$sale_entry_id)->delete();

            $amount = 0;
            $insert_batch_data = array();
            for($i = 0; $i < count($post["fish_id"]); $i ++) {
                $amount = $amount + ($post["quantity"][$i]*$post["amount"][$i]);
                $insert_batch_data[] = array(
                    'sale_entry_id' => $sale_entry_id,
                    'vendor_id' => $post["vendor_id"][$i],
                    'fish_id' => $post["fish_id"][$i],
                    'quantity' => $post["quantity"][$i],
                    'amount' => $post["amount"][$i],
                    'note' => "",
                    'created_at' => date("Y-m-d H:i:s")
                );
            }
            Sale_entry_item::insert($insert_batch_data);
            Sale_entry::where("id",$sale_entry_id)->update(["amount" => $amount]);

            return response()->json(['success' => true,'message' => "Sale entry has been edited."], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'message' => $e->getMessage()], 200);
        }
    }

    public function destroy($id)
    {
        Purchase_entry::destroy($id);
        Purchase_entry_item::where("purchase_entry_id",$id)->delete();

        return response()->json(['success' => true,'message' => "Purchase entry has been removed."], 200);
    }

    public function add_more_sale(Request $request)
    {
        $sale_id = $request->sale_id;
        $vendors = User::select("id","name")->where("created_by",Auth::user()->id)->where("role",3)->orderBy("name","asc")->get();
        if($sale_id == 0) {
            $no = time();
            $entries = null;
        } else {
            $no = time();
            $entries = Sale_entry_item::where("sale_entry_id",$sale_id)->get();
        }
        $html = view('admin.sale.add_more',compact('no','vendors','entries'))->render();
        return response()->json(['success' => true,'html' => $html], 200);
    }

    public function fetch_vendor_fish(Request $request)
    {
        $result = [];
        $purchases = Purchase_entry::select("id")->where("vendor_id",$request->vendor_id)->get();
        if(!$purchases->isEmpty()) {
            $ids = array_column($purchases->toArray(), "id");
            $fish_ids = Purchase_entry_item::select("fish_id")->whereIn("purchase_entry_id",$ids)->get();
            if(!$fish_ids->isEmpty()) {
                $fishIds = array_column($fish_ids->toArray(), "fish_id");
                $fishes = Fish::select("id","name")->whereIn("id",$fishIds)->get();
                if(!$fishes->isEmpty()) {
                    foreach($fishes as $key => $val) {
                        $quantity = Purchase_entry_item::select("id","name")
                        ->where("vendor_id",$request->vendor_id)
                        ->where("fish_id",$val->id)
                        ->sum('quantity');
                        $fishes[$key]["available_qty"] = $quantity;
                    }
                }
                $result = $fishes->toArray();
            }
        }
        $html = view('admin.purchase.fetch_vendor_fish',compact('result'))->render();
        return response()->json(['success' => true,'html' => $html], 200);
    }
}
