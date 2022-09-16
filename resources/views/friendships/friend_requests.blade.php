@extends('layouts.user')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Friend requests</div>
                    <div class="card-body table-fluid">
                        <table class="table table-striped">
                            <tbody>
                            @if($friend_requests->isEmpty())
                                <h4 class="text-center mt-3">No friend requests yet!</h4>
                            @else
                                @foreach($friend_requests as $fr)
                                    <tr>
                                        <div class="row">
                                            <td class="col-2">
                                                <div class="circular--user--icon">
                                                    <img src="{{$fr->profile_picture}}" alt="user"/>
                                                </div>
                                            </td>
                                            <td class="col-4">
                                                {{$fr->name}}
                                            </td>
                                            <td class="col-2">
                                                <a href="{{route('users.show',["user" => $fr->user_id])}}"
                                                   class="btn btn-outline-primary"> Profile </a></td>
                                            <td>
                                            <td class="col-2">
                                                <form
                                                    action="{{route('accept_friend_request',["friendship" => $fr->id])}}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn btn-outline-success btn-block">Accept</button>
                                                </form>

                                            </td>
                                            <td class="col-2">
                                                <form
                                                    action="{{route('decline_friend_request',["friendship" => $fr->id])}}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn btn-block btn-outline-danger">Decline</button>
                                                </form>
                                            </td>
                                        </div>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
