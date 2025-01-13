@section('content')
<tr class="detail body_table">
    <td class="detail">
        <strong>{{ $data->student->material_fee->type }} (Cicilan 
            {{ $installment_info['current'] }}/{{ $installment_info['total'] }})</strong>
    </td>
    <td class="detail">Rp. {{ number_format($data->amount_installment - $data->charge, 0, ',', '.') }}</td>
</tr>
@endsection