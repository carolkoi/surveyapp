@extends('layouts.app')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3c8dbc !important;
    }
    .modal {
        display:    none;
        position:   fixed;
        z-index:    1000;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background-image: url('http://i.stack.imgur.com/FhHRx.gif');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;

    }
    body.loading {
        overflow: hidden;
    }

    body.loading .modal {
        display: block;
    }
    .select2-container--default .select2-selection--single{
        border-radius: unset;
    }
    .select2-container .select2-selection--single {
        height: unset;
    }

</style>
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
                   {!! Form::model($allocation, ['route' => ['allocations.update', $template->id], 'method' => 'patch']) !!}

                        @include('allocations.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
@section('scripts')
    <script>
        jQuery(document).ready(function () {
            $(".select2").select2({
                tags: true,
                tokenSeparators: [',', ' '],
                createTag: function(term, data) {
                    var value = term.term;
                    if(validateEmail(value)) {
                        return {
                            id: value,
                            text: value
                        };
                    }
                    return null;
                }

            });

            // $('#client_list, #others_email').css({'display': 'none'});
            // $('#client').on('click', function () {
            //     $('#client_list').show();
            // });
            // $('#others').on('click', function () {
            //     $('#others_email').show();
            // });

            //changing survey types
            // let selectedType = $('#survey_type_id :selected').val();
            // setDropDownOptions(selectedType);

            $('#survey_type_id').on('change', function () {
                let type = $('#survey_type_id :selected').val();
                setDropDownOptions(type);
            });
        });

        let setDropDownOptions = function (type) {

            $.ajax({
                url: '/survey-type/' + type,
                type: 'get',
                dataType: "json",
                success: function (response) {
                    let template_id = $('#template_id');
                    let surveys =  response['data'];

                    template_id.empty();
                    template_id.append(new Option('', '', false, false)).trigger('change');

                    Object.keys(surveys).forEach(function (key)
                    {
                        let newOption = new Option(surveys[key], key, false, false);
                        template_id.append(newOption).trigger('change');
                    });

                },

            });
            return false;
        }
        function validateEmail(email) {
            var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }
    </script>
@endsection

