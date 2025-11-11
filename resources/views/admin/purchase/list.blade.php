@extends('include.header')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
<div class="page-header">
    <div class="add-item d-flex">
        <div class="page-title">
            <h4 class="fw-bold">Purchase Entry</h4>
            <h6></h6>
        </div>
    </div>
    <div class="page-btn">
        <a href="{{ url('admin/purchase_entries/create') }}" class="btn btn-primary text-white"><i class="ti ti-circle-plus me-1"></i> New Purchase Entry</a>
    </div>
</div>
<div class="row">
	<div class="col-xxl-12 col-md-6 d-flex">
		<div class="card flex-fill">
			<div class="card-body">
				<div class="alert alert-danger alert-dismissible fade show custom-alert-icon shadow-sm d-flex align-items-centers" role="alert">
                    <i class="feather-alert-octagon flex-shrink-0 me-2"></i>
                    You haven’t added any purchase entry yet.
                </div>
			</div>
		</div>
	</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
<script>
	var page_title = "Purchase Entry";
	$(document).ready(function(){

	});
</script>
@endsection
