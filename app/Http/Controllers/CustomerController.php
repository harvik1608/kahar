<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Country;
use Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $role = 4;
        $users = User::select("id","name","phone","avatar")->where("created_by",Auth::user()->id)->where("role",4)->orderBy("id","desc")->get();
        return view('admin.vendor.list',compact("role","users"));
    }

    public function create()
    {
        $admin = null;
        $_role = 4;
        $countries = Country::select("id","name")->where("is_active",1)->orderBy("name","asc")->get();
        return view('admin.vendor.add_edit',compact('admin','_role','countries'));
    }

    public function store(Request $request)
    {
        try {
            $post = $request->all();

            $check_phone = User::where("phone",$post["mobile_no"])->count();
            if($check_phone > 0) {
                return response()->json(['success' => false,'message' => "This mobile no. is already in use."], 200);
            }
            if(trim($post["email"]) != "") {
                $check_email = User::where("email",$post["email"])->count();
                if($check_email > 0) {
                    return response()->json(['success' => false,'message' => "This email is already in use."], 200);
                }
            }

            $avatar = "";
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // generate random file name
                $avatar = Str::random(20) . '.' . $image->getClientOriginalExtension();
                $path = $image->move(public_path('uploads/admin'), $avatar);
            }

            $row = new User;
            $row->name = $post['name'];
            $row->phone = $post['mobile_no'];
            $row->email = $post['email'] == "" ? "" : $post['email'];
            $row->country = $post['country'];
            $row->state = $post['state'];
            $row->city = $post['city'];
            $row->address = $post['address'];
            $row->password = Hash::make("123456");
            $row->is_approved = 1;
            $row->is_active = $post['is_active'];
            $row->avatar = $avatar;
            $row->role = $post["role"];
            $row->created_by = Auth::user()->id;
            $row->created_at = date("Y-m-d H:i:s");
            $row->save();

            return response()->json(['success' => true,'message' => "Customer added successfully."], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'message' => $e->getMessage()], 200);
        }
    }

    public function edit($id)
    {
        $admin = User::find($id);
        if(!$admin) {
            return redirect()->route("admin.dashboard");
        }
        if($admin->created_by != Auth::user()->id) {
            return redirect("admin/vendors");
        }
        $_role = 4;
        $countries = Country::select("id","name")->where("is_active",1)->orderBy("name","asc")->get();
        return view('admin.vendor.add_edit',compact('admin','_role','countries'));   
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
