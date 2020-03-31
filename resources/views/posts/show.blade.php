@extends('layouts.app')

@section('content')

    <comment :post="{{$post}}" :user="{{auth()->check() ? auth()->user() : 'null'}}"></comment>
    <comment :post="{{$post}}" :user="{{auth()->check() ? auth()->user() : 'null'}}"></comment>
    <comment :post="{{$post}}" :user="{{auth()->check() ? auth()->user() : 'null'}}"></comment>
    <comment :post="{{$post}}" :user="{{auth()->check() ? auth()->user() : 'null'}}"></comment>
    <comment :post="{{$post}}" :user="{{auth()->check() ? auth()->user() : 'null'}}"></comment>
    <comment :post="{{$post}}" :user="{{auth()->check() ? auth()->user() : 'null'}}"></comment>
    <comment :post="{{$post}}" :user="{{auth()->check() ? auth()->user() : 'null'}}"></comment>
    <comment :post="{{$post}}" :user="{{auth()->check() ? auth()->user() : 'null'}}"></comment>
    <comment :post="{{$post}}" :user="{{auth()->check() ? auth()->user() : 'null'}}"></comment>

    <back-to-top text="برو به بالا"></back-to-top>
@endsection
