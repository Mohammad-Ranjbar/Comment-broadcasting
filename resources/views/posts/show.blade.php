@extends('layouts.app')

@section('content')
    <comment :post="{{$post}}" :user="{{auth()->user()}}"></comment>

@endsection
