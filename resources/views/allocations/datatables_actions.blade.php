{!! Form::open(['route' => ['allocations.destroy', $template_id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @if(!$approved)
        <a href="{{ route('allocations.show', $template_id) }}" class='btn btn-default btn-sm'>
            <i class="glyphicon glyphicon-eye-open"></i>
        </a>
        @endif

    <a href="{{ route('allocations.edit', $template_id) }}" class='btn btn-default btn-sm'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-sm',
        'onclick' => "return confirm('Are you sure?')"
    ]) !!}
</div>
{!! Form::close() !!}
<br/>
<a href="{{route('send.survey',$template_id)}}" class='btn btn-primary'>Send</a>


