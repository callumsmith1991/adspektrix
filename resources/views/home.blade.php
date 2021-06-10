<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <title>Laravel File Upload</title>
    <style>
        .container {
            max-width: 500px;
        }

        dl,
        ol,
        ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }
    </style>
</head>

<body>

    <div class="container mt-5">


@foreach ($data as $event)

{{-- <pre>{{print_r($event)}}</pre> --}}

    <div class="event" style="margin-bottom: 100px;padding: 20px;border: 1px solid grey;text-align: center;" data-event-id="{{$event['id']}}">
  <h2>{{$event['name']}}</h2>

   @if (!empty($event['imageUrl']))
     <img src="{{$event['imageUrl']}}" alt="" width="200" height="200"> 
   @endif

   <h4 style="margin-top: 20px;">Dates</h4>
   @foreach ($event['dates'] as $date)

    <div class="date" style="padding: 20px; margin-top: 20px; border: 1px solid lightblue;" data-instance-id='{{$date['id']}}'>
      <p><strong>{{$date['dateStartFormatted']}}</strong></p>

      @if($date['availability']['available'] != 0)

          <p>Tickets Available for this date: {{$date['availability']['available']}}</p>
          
      @else

          <p>Sold out</p>
          
      @endif
      </div>
       
   @endforeach

</div>
@endforeach

</div>

</body>

</html>