<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\General_setting;
use App\Models\Faq;
use App\Models\Blog;
use App\Models\Service;
use App\Models\Project;
use App\Models\Inquiry;
use App\Models\WhyChoose;
use App\Models\Download;
use Illuminate\Support\Str;
use Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function general_settings()
    {
        $general_settings = General_setting::all();
        $data = array();
        foreach($general_settings as $general_setting) {
            $data[$general_setting->setting_key] = $general_setting->setting_val;
        }
        return view('admin.general_settings',$data);
    }

    public function submit_general_settings(Request $request)
    {
        try {
            $post = $request->all();
            unset($post['_token']);
            
            $data = array();
            foreach($post as $key => $val) {
                $data[] = ['setting_key' => $key,'setting_val' => $val,"created_at" => date("Y-m-d H:i:s")];
            }
            if($request->hasFile('banner_image')) {
                $banner_image = $request->file('banner_image');

                // generate random file name
                $banner_photo = Str::random(20) . '.' . $banner_image->getClientOriginalExtension();
                $path = $banner_image->move(public_path('uploads'), $banner_photo);

                if(isset($post["old_banner"]) && $post["old_banner"] != "" && file_exists(public_path('uploads/'.$post["old_banner"]))) {
                    unlink((public_path('uploads/'.$post["old_banner"])));
                }

                $data[] = ['setting_key' => "banner",'setting_val' => $banner_photo,"created_at" => date("Y-m-d H:i:s")];
            } else {
                $data[] = ['setting_key' => "banner",'setting_val' => $post["old_banner"],"created_at" => date("Y-m-d H:i:s")];
            }
            if($request->hasFile('about_image_1')) {
                $about_image_1 = $request->file('about_image_1');

                // generate random file name
                $about_photo_1 = Str::random(20) . '.' . $about_image_1->getClientOriginalExtension();
                $path = $about_image_1->move(public_path('uploads'), $about_photo_1);

                if(isset($post["old_about_image_1"]) && $post["old_about_image_1"] != "" && file_exists(public_path('uploads/'.$post["old_about_image_1"]))) {
                    unlink((public_path('uploads/'.$post["old_about_image_1"])));
                }

                $data[] = ['setting_key' => "about_image_1",'setting_val' => $about_photo_1,"created_at" => date("Y-m-d H:i:s")];
            } else {
                $data[] = ['setting_key' => "about_image_1",'setting_val' => $post["old_about_image_1"],"created_at" => date("Y-m-d H:i:s")];
            }
            if($request->hasFile('about_image_2')) {
                $about_image_2 = $request->file('about_image_2');

                // generate random file name
                $about_photo_2 = Str::random(20) . '.' . $about_image_2->getClientOriginalExtension();
                $path = $about_image_2->move(public_path('uploads'), $about_photo_2);

                if(isset($post["old_about_image_2"]) && $post["old_about_image_2"] != "" && file_exists(public_path('uploads/'.$post["old_about_image_2"]))) {
                    unlink((public_path('uploads/'.$post["old_about_image_2"])));
                }

                $data[] = ['setting_key' => "about_image_2",'setting_val' => $about_photo_2,"created_at" => date("Y-m-d H:i:s")];
            } else {
                $data[] = ['setting_key' => "about_image_2",'setting_val' => $post["old_about_image_2"],"created_at" => date("Y-m-d H:i:s")];
            }
            if($request->hasFile('why_choose_1')) {
                $why_choose_1 = $request->file('why_choose_1');

                // generate random file name
                $why_photo_1 = Str::random(20) . '.' . $why_choose_1->getClientOriginalExtension();
                $path = $why_choose_1->move(public_path('uploads'), $why_photo_1);

                if(isset($post["old_why_choose_1"]) && $post["old_why_choose_1"] != "" && file_exists(public_path('uploads/'.$post["old_why_choose_1"]))) {
                    unlink((public_path('uploads/'.$post["old_why_choose_1"])));
                }

                $data[] = ['setting_key' => "why_choose_1",'setting_val' => $why_photo_1,"created_at" => date("Y-m-d H:i:s")];
            } else {
                $data[] = ['setting_key' => "why_choose_1",'setting_val' => $post["old_why_choose_1"],"created_at" => date("Y-m-d H:i:s")];
            }
            if($request->hasFile('why_choose_2')) {
                $why_choose_2 = $request->file('why_choose_2');

                // generate random file name
                $why_photo_2 = Str::random(20) . '.' . $why_choose_2->getClientOriginalExtension();
                $path = $why_choose_2->move(public_path('uploads'), $why_photo_2);

                if(isset($post["old_why_choose_2"]) && $post["old_why_choose_2"] != "" && file_exists(public_path('uploads/'.$post["old_why_choose_2"]))) {
                    unlink((public_path('uploads/'.$post["old_why_choose_2"])));
                }

                $data[] = ['setting_key' => "why_choose_2",'setting_val' => $why_photo_2,"created_at" => date("Y-m-d H:i:s")];
            } else {
                $data[] = ['setting_key' => "why_choose_2",'setting_val' => $post["old_why_choose_2"],"created_at" => date("Y-m-d H:i:s")];
            }
            if($request->hasFile('why_choose_3')) {
                $why_choose_3 = $request->file('why_choose_3');

                // generate random file name
                $why_photo_3 = Str::random(20) . '.' . $why_choose_3->getClientOriginalExtension();
                $path = $why_choose_3->move(public_path('uploads'), $why_photo_3);

                if(isset($post["old_why_choose_3"]) && $post["old_why_choose_3"] != "" && file_exists(public_path('uploads/'.$post["old_why_choose_3"]))) {
                    unlink((public_path('uploads/'.$post["old_why_choose_3"])));
                }

                $data[] = ['setting_key' => "why_choose_3",'setting_val' => $why_photo_3,"created_at" => date("Y-m-d H:i:s")];
            } else {
                $data[] = ['setting_key' => "why_choose_3",'setting_val' => $post["old_why_choose_3"],"created_at" => date("Y-m-d H:i:s")];
            }
            if($request->hasFile('why_choose_4')) {
                $why_choose_4 = $request->file('why_choose_4');

                // generate random file name
                $why_photo_4 = Str::random(20) . '.' . $why_choose_4->getClientOriginalExtension();
                $path = $why_choose_4->move(public_path('uploads'), $why_photo_4);

                if(isset($post["old_why_choose_4"]) && $post["old_why_choose_4"] != "" && file_exists(public_path('uploads/'.$post["old_why_choose_4"]))) {
                    unlink((public_path('uploads/'.$post["old_why_choose_4"])));
                }

                $data[] = ['setting_key' => "why_choose_4",'setting_val' => $why_photo_4,"created_at" => date("Y-m-d H:i:s")];
            } else {
                $data[] = ['setting_key' => "why_choose_4",'setting_val' => $post["old_why_choose_4"],"created_at" => date("Y-m-d H:i:s")];
            }
            if($request->hasFile('video')) {
                $video = $request->file('video');

                // generate random file name
                $video_url = Str::random(20) . '.' . $video->getClientOriginalExtension();
                $path = $video->move(public_path('uploads'), $video_url);

                if(isset($post["old_video"]) && $post["old_video"] != "" && file_exists(public_path('uploads/'.$post["old_video"]))) {
                    unlink((public_path('uploads/'.$post["old_video"])));
                }

                $data[] = ['setting_key' => "video",'setting_val' => $video_url,"created_at" => date("Y-m-d H:i:s")];
            } else {
                $data[] = ['setting_key' => "video",'setting_val' => $post["old_video"],"created_at" => date("Y-m-d H:i:s")];
            }
            if($request->hasFile('contact_us_image')) {
                $contact_us_image = $request->file('contact_us_image');

                // generate random file name
                $contact_us_photo = Str::random(20) . '.' . $contact_us_image->getClientOriginalExtension();
                $path = $contact_us_image->move(public_path('uploads'), $contact_us_photo);

                if(isset($post["old_contact_us_image"]) && $post["old_contact_us_image"] != "" && file_exists(public_path('uploads/'.$post["old_contact_us_image"]))) {
                    unlink((public_path('uploads/'.$post["old_contact_us_image"])));
                }

                $data[] = ['setting_key' => "contact_us_image",'setting_val' => $contact_us_photo,"created_at" => date("Y-m-d H:i:s")];
            } else {
                $data[] = ['setting_key' => "contact_us_image",'setting_val' => $post["old_contact_us_image"],"created_at" => date("Y-m-d H:i:s")];
            }
            if($request->hasFile('testimonial')) {
                $testimonial = $request->file('testimonial');

                // generate random file name
                $testimonial_photo = Str::random(20) . '.' . $testimonial->getClientOriginalExtension();
                $path = $testimonial->move(public_path('uploads'), $testimonial_photo);

                if(isset($post["old_testimonial"]) && $post["old_testimonial"] != "" && file_exists(public_path('uploads/'.$post["old_testimonial"]))) {
                    unlink((public_path('uploads/'.$post["old_contact_us_image"])));
                }

                $data[] = ['setting_key' => "testimonial",'setting_val' => $testimonial_photo,"created_at" => date("Y-m-d H:i:s")];
            } else {
                $data[] = ['setting_key' => "testimonial",'setting_val' => $post["old_testimonial"],"created_at" => date("Y-m-d H:i:s")];
            }
            
            General_setting::truncate();
            General_setting::insert($data);
            return response()->json(['success' => true,'message' => "General Settings updated."], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'message' => $e->getMessage()], 200);
        }
    }

    public function inquiries()
    {
        return view('admin.inquiry');
    }

    public function load_inquiries(Request $request)
    {
        try {
            $draw = intval($request->get('draw', 0));
            $start = intval($request->get('start', 0));
            $length = intval($request->get('length', 10));
            $searchValue = $request->input('search.value', '');

            $query = Inquiry::query();
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%");
                    $q->where('email', 'like', "%{$searchValue}%");
                    $q->where('phone', 'like', "%{$searchValue}%");
                });
            }
            $recordsTotal = Inquiry::count();
            $recordsFiltered = $query->count();
            $rows = $query->offset($start)->limit($length)->orderBy('id', 'desc')->get();

            $formattedData = [];
            foreach ($rows as $index => $row) {
                $formattedData[] = [
                    'id' => $start + $index + 1,
                    'name' => $row->name,
                    'email' => $row->email,
                    'phone' => $row->phone,
                    'message' => $row->message,
                    'created_at' => date('d M, Y h:i A',strtotime($row->created_at)),
                ];
            }
            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $formattedData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
