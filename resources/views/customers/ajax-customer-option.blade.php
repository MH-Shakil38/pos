<label for="customers">Customer</label>
<select class="form-control js-example-basic-single" name="customer_id" id="customers" required>
    <option></option>
    @foreach ($customers as $customer)
        <option value="{{ $customer->id }}" {{ $customer->id == $last->id ? 'selected' : '' }}>{{ $customer->name }} - ({{ $customer->phone }})
        </option>
    @endforeach
</select>
