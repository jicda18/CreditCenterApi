@component('mail::message', ['from' => $from_name, 'name' => $name])


<h1 class="m-0 w-100 text-center">{{$title ?? ''}}<h1>


{!! $msg ?? '' !!}

{{-- Add Call to action --}}

@endcomponent
