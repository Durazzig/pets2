@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <Strong><h1>Consultas En Espera</h1></Strong>
                </div>
                @asyncWidget('consultas_recientes')
            </div>
        </div>
    </div>
</div>
@endsection
