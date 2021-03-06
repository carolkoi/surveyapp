@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Question
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($question, ['route' => ['question.update', $question->id], 'method' => 'patch']) !!}

                        @include('questions.editfields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
@section('scripts')

    <script >
        $(document).ready(function () {
            if( $('#evaluation_id').is(':checked')){
                $("#evaluation_required_id").attr('value', this.checked ? 1 : 0).hide();
                console.log()
                $('.selectAnswer').css({'display':'none'});
            }
            if($("#multiple").is(':checked')){
                $('#evaluation_required_id').hide();
            }


        })

        function getTemplate(id){
            if(id == 1){
                $('.selectAnswer').css({'display':'none'});
                $('#required_id').show();

            }else if(id == 2){
                $('.selectAnswer').css({'display':'none'});
                $('#required_id').show();

            }else if(id == 3){
                $('.selectAnswer').css({'display':'inline-block'});
                $('#required_id').hide().on('mouseleave', function () {
                    $('#required_id').hide();
                })

            }else if(id == 4){
                $('.selectAnswer').css({'display':'none'});
                $('#required_id').show();
            }else if(id == 5){
                $('.selectAnswer').css({'display':'none'});
                $('#required_id').show();
            }else if(id == 6){
                $('.selectAnswer').css({'display':'inline-block'});
                $('#required_id').show();
            }else if(id ==7){
                $('.selectAnswer').css({'display':'none'});
                $('#evaluation_required_id').attr('value', this.checked ? 1 : 0).hide();
            }
        }

        var  count= 2;

        function addAnswer(event){
            event.preventDefault();
            var htmlrow='<div class="row form-group div'+count+'">'+
                '<div class="col-md-10">'+
                '{!! Form::text('options[]', null, ['class' => 'form-control']) !!}'+
                '</div>'+
                '<div class="col-md-2">'+
                '<button type="button" class="btn btn-danger btn_remove" onclick="remove('+count+')"> X </button>'+
                '</div>'+
                '</div>';
            count ++;
            $('#InputContainer').append(htmlrow);
        }

        function remove(id)
        {
            $('.div'+id).remove();
        }

        // $('#status').on('change', function(){
        //     this.value = this.checked ? 1 : 0;
        //     console.log(this.value);
        // }).change();

    </script>


@endsection
