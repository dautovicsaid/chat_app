@extends('layouts.user')

@php
$auth_id = auth()->id();
@endphp
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @error('msg')
                <div class="alert alert-danger">{{ $message }}</div> @enderror
                <div class="card">
                    <div class="card-header">People you may know</div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-4">
                                <div class="circular--portrait">
                                    <img src="{{$user->profile_picture}}" id="image">
                                </div>
                            </div>
                            <div class="col-4 offset-2 text-center">
                                <h3>{{$user->name}}</h3>


                                @if($friendship->isEmpty())
                                    @if($auth_id != $user->id)
                                        <form action="{{route('add_friend',['target_user'=> $user->id])}}"
                                              method="POST">
                                            @csrf
                                            <button class="btn btn-outline-primary">Add friend</button>
                                            @else
                                                <a href="{{route('users.edit',["user" => $user->id])}}"
                                                   class="btn btn-outline-primary"> Edit profile </a>
                                                <a href="{{route('users.password_reset_edit',["user" => $user->id])}}"
                                                   class="btn btn-outline-primary"> Change password </a>

                                            @endif
                                            @else
                                                @if($friendship[0]->status == 'accepted')
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <a href="{{route('message_history',["conversation" => $friendship[0]->conversation_id])}}"
                                                               class="btn btn-outline-warning"> Message </a>
                                                        </div>
                                                        <div class="col-6">

                                                            <form
                                                                action="{{route('unfriend',["friendship" => $friendship[0]->id])}}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-block btn-outline-danger">
                                                                    Unfriend
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @elseif($friendship[0]->status == 'pending' && $friendship[0]->requested_by != $auth_id)
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <form
                                                                action="{{route('accept_friend_request',["friendship" => $friendship[0]->id])}}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button class="btn btn-outline-success btn-block">
                                                                    Accept
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <div class="col-6">

                                                            <form
                                                                action="{{route('decline_friend_request',["friendship" => $friendship[0]->id])}}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button class="btn btn-block btn-outline-danger">
                                                                    Decline
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                        @else
                                                    <form action="{{route('cancel_friend_request',['friendship'=> $friendship[0]->id])}}"
                                                          method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="btn btn-outline-primary">Cancel request</button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
