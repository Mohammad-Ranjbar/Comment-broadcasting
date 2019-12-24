@extends('layouts.app')

@section('content')

    <comment :post="{{$post}}" :user="{{auth()->check() ? auth()->user() : 'null'}}"></comment>

@endsection
