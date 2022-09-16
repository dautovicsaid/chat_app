@extends('layouts.user')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Friends</div>
                    <div class="card-body table-fluid">
                        <table class="table table-striped">
                            <tbody>
                            @if($friendships->isEmpty())
                                <h4 class="text-center mt-3">No friends yet! Check homepage to find and meet new people!</h4>
                            @else
                                @foreach($friendships as $fr)
                                    <tr>
                                        <div class="row">
                                            <td class="col-2">
                                                <div class="circular--user--icon">
                                                    <img
                                                        src="{{App\Models\User::return_profile_picture($fr->friend_profile_picture_path)}}"
                                                        alt="user"
                                                    >
                                                </div>
                                            </td>
                                            <td class="col-4">{{$fr->friend_name}}</td>
                                            <td class="col-2">
                                                <a href="{{route('users.show',["user" => $fr->friend_id])}}"
                                                   class="btn btn-outline-primary"> Profile </a></td>
                                            <td>
                                            <td class="col-2">
                                                <a href="{{route('message_history',["conversation" => $fr->conversation_id])}}"
                                                   class="btn btn-outline-warning"> Message </a></td>
                                            <td class="col-2">
                                                <form action="{{route('unfriend',["friendship" => $fr->id])}}"
                                                      method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-block btn-outline-danger">Unfriend</button>
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
