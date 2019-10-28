@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Template
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($template, ['route' => ['templates.update', $template->id], 'method' => 'patch']) !!}

                        @include('templates.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection