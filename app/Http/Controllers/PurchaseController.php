<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Fish;
use App\Models\Purchase_entry;
use App\Models\Purchase_entry_item;
use Auth;

class PurchaseController extends Controller
{
    public function index()
    {
        $entries = Purchase_entry::with('vendor:id,name,phone,avatar')->where("created_by",Auth::user()->id)->orderBy("id","desc")->get();
        return view('admin.purchase.list',compact("entries"));
    }

    public function create()
    {
        $purchase = null;
        $vendors = User::select("id","name","email","phone")->where("created_by",Auth::user()->id)->where("role",3)->orderBy("name","asc")->get();
        $fishes = Fish::select("id","name")->orderBy("name","asc")->get();
        return view('admin.purchase.add_edit',compact('purchase','vendors','fishes'));  
    }

    public function add_more()
    {
        $fishes = Fish::select("id","name")->orderBy("name","asc")->get();
        $html = view('admin.purchase.add_more',compact('fishes'))->render();
        return response()->json(['success' => true,'html' => $html], 200);
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
                $path = $image->move(public_path('uploads/purchase'), $avatar);
            }

            $row = new Purchase_entry;
            $row->vendor_id = $post['vendor_id'];
            $row->date = $post['date'];
            $row->time = $post['time'];
            $row->note = $post['note'] == "" ? "" : $post['note'];
            $row->avatar = $avatar;
            $row->created_by = Auth::user()->id;
            $row->created_at = date("Y-m-d H:i:s");
            $row->save();
            $purchase_entry_id = $row->id;

            $total_kg = 0;
            $insert_batch_data = array();
            for($i = 0; $i < count($post["fish_id"]); $i ++) {
                $total_kg = $total_kg + $post["quantity"][$i];
                $insert_batch_data[] = array(
                    'purchase_entry_id' => $purchase_entry_id,
                    'fish_id' => $post["fish_id"][$i],
                    'quantity' => $post["quantity"][$i],
                    'amount' => $post["amount"][$i],
                    'note' => $post["fish_note"][$i] == "" ? "" : $post["fish_note"][$i],
                    'created_at' => date("Y-m-d H:i:s")
                );
            }
            Purchase_entry_item::insert($insert_batch_data);
            Purchase_entry::where("id",$purchase_entry_id)->update(["fish" => $total_kg]);

            return response()->json(['success' => true,'message' => "Purchase entry has been added."], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'message' => $e->getMessage()], 200);
        }
    }

    public function edit($id)
    {
        $purchase = Purchase_entry::find($id);
        if(!$purchase) {
            return redirect()->route("admin.dashboard");
        }
        $vendors = User::select("id","name","email","phone")->where("created_by",Auth::user()->id)->where("role",3)->orderBy("name","asc")->get();
        $fishes = Fish::select("id","name")->orderBy("name","asc")->get();
        $items = Purchase_entry_item::select("id","fish_id","quantity","amount","note")->where("purchase_entry_id",$id)->get();
        $purchase["items"] = $items->toArray();
        return view('admin.purchase.add_edit',compact('purchase','vendors','fishes'));  
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
                $path = $image->move(public_path('uploads/purchase'), $avatar);

                // delete old image
                if($post["old_avatar"] != "" && file_exists(public_path('uploads/purchase/'.$post["old_avatar"]))) {
                    unlink((public_path('uploads/purchase/'.$post["old_avatar"])));
                }
            }

            $row = Purchase_entry::find($id);
            $row->vendor_id = $post['vendor_id'];
            $row->date = $post['date'];
            $row->time = $post['time'];
            $row->note = $post['note'] == "" ? "" : $post['note'];
            $row->avatar = $avatar;
            $row->updated_by = Auth::user()->id;
            $row->updated_at = date("Y-m-d H:i:s");
            $row->save();
            $purchase_entry_id = $id;

            Purchase_entry_item::where("purchase_entry_id",$purchase_entry_id)->delete();
            $insert_batch_data = array();
            $total_kg = 0;
            for($i = 0; $i < count($post["fish_id"]); $i ++) {
                $total_kg = $total_kg + $post["quantity"][$i];
                $insert_batch_data[] = array(
                    'purchase_entry_id' => $purchase_entry_id,
                    'fish_id' => $post["fish_id"][$i],
                    'quantity' => $post["quantity"][$i],
                    'amount' => $post["amount"][$i],
                    'note' => $post["fish_note"][$i] == "" ? "" : $post["fish_note"][$i],
                    'created_at' => date("Y-m-d H:i:s")
                );
            }
            Purchase_entry_item::insert($insert_batch_data);
            Purchase_entry::where("id",$purchase_entry_id)->update(["fish" => $total_kg]);

            return response()->json(['success' => true,'message' => "Purchase entry has been edited."], 200);
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
}
