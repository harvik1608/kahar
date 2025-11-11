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
    <td><a href="javascript:;" onclick="add_more()" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Add More" data-bs-original-title="Add More"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></td>
</tr>