@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">posts ...</div>

                    <div class="card-body">

                        <a href="/posts/2">Post test</a>
                        <div>
                            <autocomplete></autocomplete>
                        </div>
                    </div>
                    <div class="card-footer">
                        <passport-clients></passport-clients>
                        <passport-authorized-clients></passport-authorized-clients>
                        <passport-personal-access-tokens></passport-personal-access-tokens>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
