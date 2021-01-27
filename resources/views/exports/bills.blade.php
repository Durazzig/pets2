<table class="table table-hover table-responsive-lg table-striped">
    <thead class="bg-primary text-white">
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('Proveedor') }}</th>
            <th scope="col">{{ __('Folio') }}</th>
            <th scope="col">{{ __('Fecha') }}</th>
            <th scope="col">{{ __('Fecha de Entrega') }}</th>
            <th scope="col">{{ __('Monto') }}</th>
            <th scope="col">{{ __('Entrega') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bills as $bill)
        <tr>
            <td>{{ $bill->id }}</td>
            <td>{{ $bill->provider->name }}</td>
            <td>{{ $bill->folio }}</td>
            <td>{{ $bill->fecha }}</td>
            <td>{{ $bill->fecha_entrega }}</td>
            <td>${{ $bill->monto }}</td>
            <td>{{ $bill->empleados->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>