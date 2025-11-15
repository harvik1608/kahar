<option value="">Choose option</option>
@if(!empty($result))
	@foreach($result as $row)
		<option value="{{ $row['id'] }}" data-quantity="{{ $row['available_qty'] }}">{{ $row['name'] }}</option>
	@endforeach
@endif