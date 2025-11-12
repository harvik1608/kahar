@extends('include.header')
@section('content')
<div class="page-header">
    <div class="page-title">
        <h4>Purchase Entry</h4>
        <h6>(<span class='mandadory'>*</span>) indicates required field.</h6>
    </div>
</div>
<form action="{{ is_null($purchase) ? url('admin/purchase_entries') : url('admin/purchase_entries/'.$purchase->id) }}" method="POST" enctype="multipart/form-data" id="mainForm">
    @csrf
    @if(!is_null($purchase))
        <input type="hidden" name="_method" value="PUT" />
    @endif
    <input type="hidden" class="form-control" name="old_avatar" value="{{ is_null($purchase) ? '' : $purchase->avatar }}" />
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ is_null($purchase) ? "New" : "Edit" }} Purchase Entry</h4>
                </div>
                <div class="card-body profile-body">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Vendor<span class="text-danger ms-1">*</span></label>
                            <select class="select" name="vendor_id" id="vendor_id">
                                <option value="">Choose option</option>
                                @if(!$vendors->isEmpty())
                                    @foreach($vendors as $vendor)
                                        @if(!is_null($purchase) && $purchase->vendor_id == $vendor->id)
                                            <option value="{{ $vendor->id }}" selected>{{ $vendor->name }}</option>
                                        @else 
                                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            <label id="vendor_id-error" class="error" for="vendor_id"></label>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
                            <input type="date" class="form-control" name="date" value="{{ is_null($purchase) ? date('Y-m-d') : $purchase->date }}" />
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Time<span class="text-danger ms-1">*</span></label>
                            <input type="time" class="form-control" name="time" value="{{ is_null($purchase) ? date('H:i') : $purchase->time }}" />
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="fishList">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="15%">Fish</th>
                                            <th width="15%">Quantity</th>
                                            <th width="15%">Amount</th>
                                            <th width="50%">Note</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!is_null($purchase) && isset($purchase["items"]) && !empty($purchase["items"]))
                                            @foreach($purchase["items"] as $key => $val)
                                                <tr>
                                                    <td>
                                                        <select class="select" name="fish_id[]">
                                                            <option value="">Choose option</option>
                                                            @if(!$fishes->isEmpty())
                                                                @foreach($fishes as $fish)
                                                                    @if($val["fish_id"] == $fish->id)
                                                                        <option value="{{ $fish->id }}" selected>{{ $fish->name }}</option>
                                                                    @else 
                                                                        <option value="{{ $fish->id }}">{{ $fish->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td><input type="number" class="form-control" name="quantity[]" value="{{ $val['quantity'] }}" /></td>
                                                    <td><input type="number" class="form-control" name="amount[]" value="{{ $val['amount'] }}" /></td>
                                                    <td><input type="text" class="form-control" name="fish_note[]" placeholder="Write note here..." value="{{ $val['note'] }}" /></td>
                                                    <td>
                                                        @if($key == 0)
                                                            <a href="javascript:;" onclick="add_more()" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Add More" data-bs-original-title="Add More"><i class="ti ti-circle-plus me-1"></i></a>
                                                        @else 
                                                            <a href="javascript:;" onclick="add_more()" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Add More" data-bs-original-title="Add More"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>
                                                    <select class="select" name="fish_id[]">
                                                        <option value="">Choose option</option>
                                                        @if(!$fishes->isEmpty())
                                                            @foreach($fishes as $fish)
                                                                <option value="{{ $fish->id }}">{{ $fish->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </td>
                                                <td><input type="number" class="form-control" name="quantity[]" /></td>
                                                <td><input type="number" class="form-control" name="amount[]" value="0" /></td>
                                                <td><input type="text" class="form-control" name="fish_note[]" placeholder="Write note here..." /></td>
                                                <td><a href="javascript:;" onclick="add_more()" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Add More" data-bs-original-title="Add More"><i class="ti ti-circle-plus me-1"></i></a></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Note</label>
                            <textarea class="form-control" name="note" id="note" placeholder="Write a note here...">{{ is_null($purchase) ? '' : $purchase->note }}</textarea>
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
                        <a href="{{ url('admin/purchase_entries') }}" class="btn btn-secondary" id="backBtn">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
	var page_title = "Purchase Entry";
    $(document).ready(function(){
        $("#mainForm").validate({
            rules:{
                vendor_id:{
                    required: true
                }
            },
            messages:{
                vendor_id:{
                    required: "<small class='text-danger'>Please select a vendor.</small>"
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
                        } else {
                            $("#mainForm button[type=submit]").html("SUBMIT").attr("disabled",false);
                            show_toast("Oops!",response.message,"error");
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
    function add_more()
    {
        $.ajax({
            url: "{{ route('admin.add_more') }}",
            type: "GET",
            success:function(response){
                if(response.success) {
                    $("#fishList tbody").append(response.html);
                } else {
                    $("#mainForm button[type=submit]").html("SUBMIT").attr("disabled",false);
                    show_toast("Oops!",response.message,"error");
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
</script>
@endsection
