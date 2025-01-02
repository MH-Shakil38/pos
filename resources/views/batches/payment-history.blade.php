@forelse ($batch->payment as $info)
<div class="card">
    <div class="card-body">
        <table>
            <tr class="text-info">
                <th>Date</th>
                <td>:</td>
                <td  colspan="2">{{ Carbon\Carbon::parse($info->date)->format('d M y') }}</td>
            </tr>
            <tr class="text-success">
                <th>Paid Amount</th>
                <td>:</td>
                <td>{{ $info->paid_amount}}</td>
                <td class="text-info">taka</td>
            </tr>
            <tr class="text-danger">
                <th>Payment Type</th>
                <td>:</td>
                <td colspan="2">{{ $info->payment_type }}</td>
            </tr>
            <tr class="text-danger">
                <th>Note</th>
                <td>:</td>
                <td colspan="2">{{ $info->note }}</td>
            </tr>
        </table>
    </div>
</div>
@empty

@endforelse

