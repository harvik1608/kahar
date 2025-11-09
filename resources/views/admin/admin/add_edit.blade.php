@extends('include.header')
@section('content')
<div class="page-header">
    <div class="page-title">
        <h4>Admin List</h4>
        <h6>(<span class='mandadory'>*</span>) indicates required field.</h6>
    </div>
</div>
<form action="{{ is_null($admin) ? url('admin/admins') : url('admin/admins/'.$admin->id) }}" method="POST" enctype="multipart/form-data" id="mainForm">
    @csrf
    @if(!is_null($admin))
        <input type="hidden" name="_method" value="PUT" />
    @endif
    <input type="hidden" class="form-control" name="old_avatar" value="{{ is_null($admin) ? '' : $admin->avatar }}" />
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ is_null($admin) ? "New" : "Edit" }} Admin</h4>
                </div>
                <div class="card-body profile-body">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" value="{{ is_null($admin) ? '' : $admin->name }}" autofocus />
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Mobile No.<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" name="mobile_no" id="mobile_no" value="{{ is_null($admin) ? '' : $admin->phone }}" />
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" id="email" value="{{ is_null($admin) ? '' : $admin->email }}" />
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Country</label>
                            <select class="select" name="country" id="country">
                                <option value="">Choose Country</option>
                                @if(!$countries->isEmpty())
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" class="form-control" name="state" id="state" value="{{ is_null($admin) ? '' : $admin->state }}" />
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city" id="city" value="{{ is_null($admin) ? '' : $admin->city }}" />
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" id="address" value="{{ is_null($admin) ? '' : $admin->address }}" />
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Password<span class="text-danger ms-1">{{ is_null($admin) ? '*' : '' }}</span></label>
                            <input type="text" class="form-control" name="password" id="password" />
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Is Approved?</label>
                            <select class="select" name="is_approved" id="is_approved">
                                <option value="1" {{ !is_null($admin) && $admin->is_approved == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !is_null($admin) && $admin->is_approved == 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Status</label>
                            <select class="select" name="is_active" id="is_active">
                                <option value="1" {{ !is_null($admin) && $admin->is_active == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !is_null($admin) && $admin->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="file-drop mb-3 text-center">
                                <span class="avatar avatar-sm bg-primary text-white mb-2">
                                    <i class="ti ti-upload fs-16"></i>
                                </span>
                                <h6 class="mb-2">Upload Photo</h6>
                                <p class="fs-12 mb-0"></p>
                                <input type="file" name="image" id="image">
                            </div>
                        </div>
                    </div>
                    <div class="text-end mt-2">
                        <button type="submit" class="btn btn-primary">SUBMIT</button>
                        <a href="{{ url('admin/admins') }}" class="btn btn-secondary" id="backBtn">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
	var page_title = "Admin List";
    var is_edit_page = "{{ is_null($admin) ? 0 : 1 }}";
    $(document).ready(function(){
        $("#mainForm").validate({
            rules:{
                name:{
                    required: true
                },
                mobile_no:{
                    required: true
                },
                password:{
                    required: is_edit_page == 0,
                    minlength: 6
                }
            },
            messages:{
                name:{
                    required: "<small class='text-danger'><b>Name is required.</b></small>"
                },
                mobile_no:{
                    required: "<small class='text-danger'><b>Mobile No. is required.</b></small>"
                },
                password:{
                    required: "<small class='text-danger'><b>Password is required.</b></small>",
                    minlength: "<small class='text-danger'><b>Password must be at least 6 characters long.</b></small>",
                }
            }
        });
        $("#mainForm").submit(function(e){
            e.preventDefault();

            if($("#mainForm").valid()) {
                $.ajax({
                    url: $("#mainForm").attr("action"),
                    type: $("#mainForm").attr("method"),
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend:function(xhr){
                        xhr.setRequestHeader("csrf-token", $("input[name=_csrf]").val());
                        $("#mainForm button[type=submit]").html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>').attr("disabled",true);
                    },
                    success:function(response){
                        if(response.success) {
                            show_toast("Success!",response.message,"success");
                            setTimeout(function(){
                                window.location.href = $("#backBtn").attr("href");
                            },3000);
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#mainForm button[type=submit]").html("SUBMIT").attr("disabled",false);
                        if (xhr.status === 400) {
                            const res = xhr.responseJSON;
                            show_toast("Oops!",res.message,"error");
                        } else {
                            show_toast("Oops!","Something went wrong","error");
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
