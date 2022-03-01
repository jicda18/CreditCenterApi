@component('mail::message', ['from' => $from_name, 'name' => $name])

# Greetings {{$name}},

@component('mail::panel')

<h2 class="m-0">{{$title ?? ''}}<h2>
<hr class="p-0 m-0">

<br>

{!! $msg ?? '' !!}

@endcomponent

@endcomponent
