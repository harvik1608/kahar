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
        return view('admin.purchase.list');
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

            $insert_batch_data = array();
            for($i = 0; $i < count($post["fish_id"]); $i ++) {
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

            return response()->json(['success' => true,'message' => "Purchase entry has been added."], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'message' => $e->getMessage()], 200);
        }
    }

    public function edit($id)
    {
        $purchase = null;
        return view('admin.purchase.add_edit',compact('purchase'));   
    }

    public function update(Request $request,$id)
    {
        try {
            $post = $request->all();

            // $check_phone = User::where("phone",$post["mobile_no"])->where("id","<>",Auth::user()->id)->count();
            // if($check_phone > 0) {
            //     return response()->json(['success' => false,'message' => "This mobile no. is already in use."], 200);
            // }
            // if(trim($post["email"]) != "") {
            //     $check_email = User::where("email",$post["email"])->where("id","<>",Auth::user()->id)->count();
            //     if($check_email > 0) {
            //         return response()->json(['success' => false,'message' => "This email is already in use."], 200);
            //     }
            // }

            $avatar = $post["old_avatar"];
            if($request->hasFile('image')) {
                $image = $request->file('image');

                // generate random file name
                $avatar = Str::random(20) . '.' . $image->getClientOriginalExtension();
                $path = $image->move(public_path('uploads/admin'), $avatar);

                // delete old image
                if($post["old_avatar"] != "" && file_exists(public_path('uploads/admin/'.$post["old_avatar"]))) {
                    unlink((public_path('uploads/admin/'.$post["old_avatar"])));
                }
            }

            $row = User::find($id);
            $row->name = $post['name'];
            $row->phone = $post['mobile_no'];
            $row->email = $post['email'];
            $row->country = $post['country'];
            $row->state = $post['state'];
            $row->city = $post['city'];
            $row->address = $post['address'];
            $row->is_approved = 1;
            $row->is_active = $post['is_active'];
            $row->avatar = $avatar;
            $row->updated_by = Auth::user()->id;
            $row->updated_at = date("Y-m-d H:i:s");
            $row->save();

            return response()->json(['success' => true,'message' => "Customer edited successfully."], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'message' => $e->getMessage()], 200);
        }
    }

    public function destroy($id)
    {
        $row = User::find($id);
        $row->deleted_by = Auth::user()->id;
        $row->save();

        User::destroy($id);
        return response()->json(['success' => true,'message' => "Customer removed successfully."], 200);
    }
}
