@if(!is_null($entries))
    @foreach($entries as $key => $entry)
        <tr id="row-{{ $entry->id }}">
            <td>
                <input type="hidden" class="entry" value="{{ $entry->id }}" />
                <input type="hidden" class="default_fish_id" value="{{ $entry->fish_id }}" />
                <select class="select" name="vendor_id[]" onchange="fetch_fish({{ $entry->id }});">
                    <option value="">Choose option</option>
                    @if(!$vendors->isEmpty())
                        @foreach($vendors as $vendor)
                            @if($vendor->id == $entry->vendor_id)
                                <option value="{{ $vendor->id }}" selected>{{ $vendor->name }}</option>
                            @else 
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </td>
            <td>
                <select class="select" name="fish_id[]" onchange="check_quantity({{ $entry->id }});">
                    <option value="">Choose option</option>
                </select>
            </td>
            <td align="center"><span class="availableQty">0</span></td>
            <td>
                <input type="number" class="form-control qty" min="0" name="quantity[]" placeholder="Enter quantity" onblur="calc_total({{ $entry->id }})" value="{{ $entry->quantity }}" />
            </td>
            <td><input type="number" class="form-control amt" min="0" name="amount[]" onblur="calc_total({{ $entry->id }})" value="{{ $entry->amount }}" /></td>
            <td><input type="number" class="form-control tot" min="0" name="total[]" value="{{ $entry->quantity*$entry->amount }}" disabled /></td>
            <td>
                @if($key == 0)
                    <a href="javascript:;" onclick="add_more(1)" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Add More" data-bs-original-title="Add More"><i class="ti ti-circle-plus me-1"></i></a>
                @else
                    <a href="javascript:;" onclick="add_more()" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Add More" data-bs-original-title="Add More"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                @endif
            </td>
        </tr>
    @endforeach
@else 
    <tr id="row-{{ $no }}">
        <td>
            <select class="select" name="vendor_id[]" onchange="fetch_fish({{ $no }});">
                <option value="">Choose option</option>
                @if(!$vendors->isEmpty())
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td>
            <select class="select" name="fish_id[]" onchange="check_quantity({{ $no }});">
                <option value="">Choose option</option>
            </select>
        </td>
        <td><span class="availableQty">0</span></td>
        <td>
            <input type="number" class="form-control qty" min="0" name="quantity[]" placeholder="Enter quantity" onblur="calc_total({{ $no }})" />
        </td>
        <td><input type="number" class="form-control amt" min="0" name="amount[]" onblur="calc_total({{ $no }})" /></td>
        <td><input type="number" class="form-control tot" min="0" name="total[]" disabled /></td>
        <td><a href="javascript:;" onclick="add_more(1)" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Add More" data-bs-original-title="Add More"><i class="ti ti-circle-plus me-1"></i></a></td>
    </tr>
@endif