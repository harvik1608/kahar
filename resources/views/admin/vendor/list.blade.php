@extends('include.header')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
<div class="page-header">
    <div class="add-item d-flex">
        <div class="page-title">
            <h4 class="fw-bold">{{ $role == 3 ? "Vendor" : "Customer" }} List</h4>
            <h6></h6>
        </div>
    </div>
    <div class="page-btn">
        <a href="{{ $role == 3 ? url('admin/vendors/create') : url('admin/customers/create') }} " class="btn btn-primary text-white"><i class="ti ti-circle-plus me-1"></i>New {{ $role == 3 ? "Vendor" : "Customer" }}</a>
    </div>
</div>
<div class="row">
	<div class="col-xxl-12 col-md-6 d-flex">
		<div class="card flex-fill">
			<div class="card-body">
				@if(!$users->isEmpty())
					@foreach($users as $row)
						<div class="d-flex align-items-center justify-content-between border-bottom mb-3 pb-3 flex-wrap gap-2">
							<div class="d-flex align-items-center">
								<a href="javascript:void(0);" class="avatar avatar-lg flex-shrink-0">
									<img src="{{ $row->avatar == '' ? asset('assets/img/kahar_user.jpg') : asset('uploads/admin/'.$row->avatar) }}" alt="img">
								</a>
								<div class="ms-2">
									<h6 class="fs-14 fw-bold mb-1"><a href="javascript:void(0);">{{ $row->name }}</a></h6>
									<div class="d-flex align-items-center item-list">			
										<p class="d-inline-flex align-items-center"><i class="ti ti-phone me-1"></i>+{{ $row->phone }}</p>
									</div>
								</div>
							</div>
							<div class="text-end">
								<h5>
									<div class="edit-delete-action">
										<a href="{{ $role == 3 ? url('admin/vendors/'.$row->id.'/edit/') : url('admin/customers/'.$row->id.'/edit/') }}" class="me-2 edit-icon p-2 text-success" title="Edit">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
		                            			<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
		                            			<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
		                        			</svg>
		                    			</a>
		                    			<a href="javascript:;" onclick="remove_row('{{ $role == 3 ? url('admin/vendors/' . $row->id) : url('admin/customers/' . $row->id) }}')" data-bs-toggle="modal" data-bs-target="#delete-modal" class="p-2" title="Delete">
		                    				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
		                            			<polyline points="3 6 5 6 21 6"></polyline>
		                            			<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
		                            			<line x1="10" y1="11" x2="10" y2="17"></line>
		                            			<line x1="14" y1="11" x2="14" y2="17"></line>
		                        			</svg>
		                    			</a>
		                    		</div>
		                    	</h5>
							</div>									
						</div>
					@endforeach
				@else 
					<div class="alert alert-danger alert-dismissible fade show custom-alert-icon shadow-sm d-flex align-items-centers" role="alert">
                        <i class="feather-alert-octagon flex-shrink-0 me-2"></i>
                        You haven’t added any {{ $role == 3 ? 'vendors' : 'customers' }} yet.
                    </div>
				@endif
			</div>
		</div>
	</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
<script>
	var page_title = "{{ $role == 3 ? 'Vendor List' : 'Customer List' }}";
	$(document).ready(function(){

	});
</script>
@endsection
