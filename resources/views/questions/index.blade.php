@extends('layouts.app')

@section('content')
{{--    <div class="container">--}}
        <div class="row">
                <div class="col-md-6">
                    <div class="card">
                    <section class="content-header">
                        <h1 class="pull-left">Create Question</h1>
                        <a  href="{{ url('/templates')}}" class="btn btn-default pull-right"> Back</a>
                    </section>
                    <br/>
                    @include('questions.create')

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <section class="content-header">
                            <h1 class="pull-left">{{$template->name}} Questions</h1>
                            <a href="{{route('survey.preview',$template->id)}}" class='btn btn-primary pull-right'>Preview</a>

                        </section>
                        <br/><br>
                        <div class="box box-primary">
                            <div class="box-body ">
                            @if(count($questions) < 1)
                                <div class="alert alert-danger"> No Questions yet</div>
                            @endif
                            @php($count =1)
                            @foreach($questions as $question)
                                <table class="table-bordered">
                                    <tr>
                                    <div class="row form-group div1">
                                        <div class="col-md-8">
                                            {{ $count ++}} . {{$question->question}}
                                        </div>
                                        <div class="col-md-4">
                                            @include('questions.datatables_actions')
                                            {{--                                            <a href="{{url('question/'.$question->id.'/edit')}}">Edit</a>--}}

                                        </div>

                                    </div>
                                    </tr>
                                </table>



{{--                                     {{ $count ++}} . {{$question->question}}--}}

                                        <ul>
                                            @if($question->type == App\Models\Question::USER_INPUT)
                                                <label class="badge badge-success">Text input</label>
                                            @endif
{{--                                            <a href="{{url('question/'.$question->id.'/edit')}}">Edit</a>--}}
                                            @if($question->type == App\Models\Question::SELECT_ONE)
                                                <label class="badge badge-success"> True/False Choice</label>
                                                @foreach($question->answer as $ans)
                                                    <li> {{ $ans->choice }}</li>
                                                @endforeach
                                            @endif
                                            @if($question->type == App\Models\Question::SELECT_MULTIPLE)
                                                <label class="badge badge-success"> Multiple Choice</label>
                                                @foreach($question->answer as $ans)
                                                    <li> {{ $ans->choice }}</li>
                                                @endforeach
                                            @endif
                                            @if($question->type == App\Models\Question::DATE)
                                                    <label class="badge badge-success">Date input</label>
                                            @endif
                                            @if($question->type == App\Models\Question::NUMBER)
                                                    <label class="badge badge-success">Number input</label>
                                            @endif
                                            @if($question->type == App\Models\Question::DROP_DOWN_LIST)
                                                <label class="badge badge-success">Drop down list</label>
                                                @foreach($question->answer as $ans)
                                                    <li>{{$ans->choice}}</li>
                                                @endforeach
                                            @endif
                                                @if($question->type == App\Models\Question::RATING)
                                                    <label class="badge badge-success">Rating input</label>
                                                @endif

                                        </ul>

                            @endforeach
                        </div>
                    </div>
                    </div>

        </div>
    </div>
    </div>

@endsection

