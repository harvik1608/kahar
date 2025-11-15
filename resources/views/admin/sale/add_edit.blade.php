@extends('include.header')
@section('content')
<div class="page-header">
    <div class="page-title">
        <h4>Sale Entry</h4>
        <h6>(<span class='mandadory'>*</span>) indicates required field.</h6>
    </div>
</div>
<form action="{{ is_null($sale) ? url('admin/sale_entries') : url('admin/sale_entries/'.$sale->id) }}" method="POST" enctype="multipart/form-data" id="mainForm">
    @csrf
    @if(!is_null($sale))
        <input type="hidden" name="_method" value="PUT" />
    @endif
    <input type="hidden" class="form-control" name="old_avatar" value="{{ is_null($sale) ? '' : $sale->avatar }}" />
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ is_null($sale) ? "New" : "Edit" }} Sale Entry</h4>
                </div>
                <div class="card-body profile-body">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Customer<span class="text-danger ms-1">*</span></label>
                            <select class="select" name="customer_id" id="customer_id">
                                <option value="">Choose option</option>
                                @if(!$customers->isEmpty())
                                    @foreach($customers as $customer)
                                        @if(!is_null($sale) && $sale->customer_id == $customer->id)
                                            <option value="{{ $customer->id }}" selected>{{ $customer->name }}</option>
                                        @else 
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            <label id="customer_id-error" class="error" for="customer_id"></label>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
                            <input type="date" class="form-control" name="date" value="{{ is_null($sale) ? date('Y-m-d') : $sale->date }}" />
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Time<span class="text-danger ms-1">*</span></label>
                            <input type="time" class="form-control" name="time" value="{{ is_null($sale) ? date('H:i') : $sale->time }}" />
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="saleCartList">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="15%">Vendor</th>
                                            <th width="15%">Fish</th>
                                            <th width="15%">Available Quantity</th>
                                            <th width="15%">Quantity</th>
                                            <th width="15%">Amount</th>
                                            <th width="15%">Total</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Amount to Pay</label>
                            <input type="number" class="form-control" id="amount_to_pay" value="{{ is_null($sale) ? '' : $sale->amount }}" disabled />
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Payment Type<span class="text-danger ms-1">*</span></label>
                            <select class="select" name="payment_type" id="payment_type">
                                <option value="">Choose option</option>
                                <option value="1" {{ !is_null($sale) && $sale->payment_type == 1 ? 'selected' : '' }}>Cash</option>
                                <option value="2" {{ !is_null($sale) && $sale->payment_type == 2 ? 'selected' : '' }}>Debit</option>
                            </select>
                            <label id="payment_type-error" class="error" for="payment_type"></label>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Payment Method<span class="text-danger ms-1">*</span></label>
                            <select class="select" name="payment_method" id="payment_method">
                                <option value="">Choose option</option>
                                @if(!$payment_methods->isEmpty())
                                    @foreach($payment_methods as $payment_method)
                                        @if(!is_null($sale) && $sale->payment_method == $payment_method->id)
                                            <option value="{{ $payment_method->id }}" selected>{{ $payment_method->name }}</option>
                                        @else 
                                            <option value="{{ $payment_method->id }}">{{ $payment_method->name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            <label id="payment_method-error" class="error" for="payment_method"></label>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Note</label>
                            <textarea class="form-control" name="note" id="note" placeholder="Write a note here...">{{ is_null($sale) ? '' : $sale->note }}</textarea>
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
                        <a href="{{ url('admin/sale_entries') }}" class="btn btn-secondary" id="backBtn">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
	var page_title = "Sale Entry";
    var sale_id = "{{ is_null($sale) ? 0 : $sale->id }}";
    $(document).ready(function(){
        load_cart(1);
        $("#mainForm").validate({
            rules:{
                customer_id:{
                    required: true
                },
                date:{
                    required: true
                },
                time:{
                    required: true
                },
                payment_type:{
                    required: true
                },
                payment_method:{
                    required: true
                }
            },
            messages:{
                customer_id:{
                    required: "<small class='text-danger'><b>Please select a customer.</b></small>"
                },
                date:{
                    required: "<small class='text-danger'><b>Please select a date.</b></small>"
                },
                time:{
                    required: "<small class='text-danger'><b>Please select a time.</b></small>"
                },
                payment_type:{
                    required: "<small class='text-danger'><b>Please select a payment type.</b></small>"
                },
                payment_method:{
                    required: "<small class='text-danger'><b>Please select a payment method.</b></small>"
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
    function load_cart(no)
    {
        $.ajax({
            url: "{{ route('admin.add_more_sale') }}",
            type: "GET",
            data: {sale_id:sale_id},
            success:function(response){
                if(response.success) {
                    if(no == 1) {
                        $("#saleCartList tbody").html(response.html);
                    } else {
                        $("#saleCartList tbody").append(response.html);
                    }
                    if(sale_id > 0) {
                        fetch_vendor_fish();
                    }
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
    function add_more(no)
    {
        $.ajax({
            url: "{{ route('admin.add_more_sale') }}",
            type: "GET",
            success:function(response){
                if(response.success) {
                    $("#saleCartList tbody").append(response.html);
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
    function fetch_vendor_fish()
    {
        $("#saleCartList tbody tr").each(function(){
            fetch_fish($(this).find(".entry").val(),$(this).find(".default_fish_id").val());
        });
    }
    function fetch_fish(no,fish_id = 0)
    {
        $.ajax({
            url: "{{ route('admin.fetch_vendor_fish') }}",
            type: "GET",
            data: {vendor_id: $("#row-"+no+" select:eq(0)").val()},
            success:function(response){                
                if(response.success) {
                    $("#row-"+no+" select:eq(1)").html(response.html);
                    if(fish_id > 0) {
                        $("#row-"+no+" select:eq(1)").val(fish_id);
                    }
                } else {
                    $("#mainForm button[type=submit]").html("SUBMIT").attr("disabled",false);
                    show_toast("Oops!",response.message,"error");
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 400) {
                    const res = xhr.responseJSON;
                    show_toast("Oops!",res.message,"error");
                } else {
                    show_toast("Oops!","Something went wrong","error");
                }
            }
        });
    }
    function check_quantity(no)
    {
        $("#row-"+no+" .availableQty").html($("#row-"+no+" select:eq(1) option:selected").attr("data-quantity"));
    }
    function calc_total(no)
    {
        var qty = amt = amount_to_pay = 0;
        if($("#row-"+no+" .qty").val() != "") {
            qty = parseFloat($("#row-"+no+" .qty").val());
        }
        if($("#row-"+no+" .amt").val() != "") {
            amt = parseFloat($("#row-"+no+" .amt").val());
        }
        $("#row-"+no+" .tot").val(qty*amt); 

        $("#saleCartList tbody tr").each(function(){
            amount_to_pay = amount_to_pay + parseFloat($(this).find(".tot").val());
        });
        $("#amount_to_pay").val(amount_to_pay);
    }
</script>
@endsection
