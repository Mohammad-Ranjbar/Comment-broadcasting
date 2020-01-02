@extends('layouts.app')

@section('content')

    <div class="container">
        <table class="table table-hover">
            <thead class="text-primary">
                <th>
                    name
                </th>
                <th>
                    email
                </th>
                <th>
                    image
                </th>
            </thead>
            <tbody>
                @foreach($users as $user)

                    <tr>
                        <td>
                            {{$user->name}}
                        </td>
                        <td>
                            {{$user->email}}
                        </td>
                        <td>
                            <img  src="http://placeimg.com/80/80"  style="border-radius: 50px 50px;height: 50px">
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>


@endsection
