@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">posts ...</div>

                    <div class="card-body">

                        <a href="/posts/1">Post test</a>
                        <br>
                        <a href="/form" class="btn"> فرم ثبت نام</a>
                        <div>
                            <autocomplete></autocomplete>
                        </div>
                    </div>

                </div>
            </div>

        </div>


    </div>
@endsection
