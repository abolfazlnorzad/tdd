@extends("Layouts.layout")

@section("content")

    @foreach($tags as $yag)
        <h1>{{$yag->name}}</h1>
    @endforeach

@endsection
