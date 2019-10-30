@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Allocation
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'allocations.store']) !!}

                        @include('allocations.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $("#user_id, #client_id, #template_id").select2({
        });

    </script>
    @endsection