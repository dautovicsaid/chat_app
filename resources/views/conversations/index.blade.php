@extends('layouts.user')

@section('css')
    <link rel="stylesheet" href="{{asset('/css/messages.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
@endsection

@section('content')

    <div class="container py-5 px-4 messenger">
        <div class="row rounded-lg overflow-hidden shadow h-100">

            @if($conversations->isNotEmpty())
                <div class="col-5 px-0">
                    <div class="bg-white">

                        <div class="bg-gray px-4 py-2 bg-light">
                            <p class="h5 mb-0 py-1">Recent</p>
                        </div>

                        <div class="messages-box">
                            <div class="list-group rounded-0">
                                @foreach($conversations as $conv)
                                    <a href="{{route('message_history',["conversation" => $conv->id])}}"
                                       class="list-group-item list-group-item-action list-group-item-light rounded-0">
                                        <div class="media">
                                            <div class="circular--user--icon"><img
                                                    src="{{App\Models\User::return_profile_picture($conv->friend_profile_picture_path)}}"
                                                    alt="user"
                                                >
                                            </div>
                                            <div class="media-body ml-4">
                                                <div class="d-flex align-items-center justify-content-between mb-1">
                                                    <h6 class="mb-0">{{$conv->friend_name}}</h6><small
                                                        class="small font-weight-bold">@if(isset($conv->created_at)){{date('d M Y', strtotime($conv->created_at))}} @endif</small>
                                                </div>
                                                <p class="font-italic mb-0 text-small">
                                                    @if($conv->last_message)
                                                        {{ \App\Models\Conversation::is_logged_in_users_message($conv->created_by) ? "You: " : '' }}
                                                        {{ $conv->last_message }}
                                                    @else
                                                        No messages yet!
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div @class(['px-0 h-100', 'col-7' => $conversations->isNotEmpty(), 'col-12' => $conversations->isEmpty()])>
                <div class="row mt-5">
                    <div class="col-6 offset-3 text-center">
                        <svg aria-label="Direct" class="_ab6-" color="#262626" fill="#262626" height="96" role="img"
                             viewBox="0 0 96 96" width="96">
                            <circle cx="48" cy="48" fill="none" r="47" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2"></circle>
                            <line fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                                  x1="69.286"
                                  x2="41.447" y1="33.21" y2="48.804"></line>
                            <polygon fill="none"
                                     points="47.254 73.123 71.376 31.998 24.546 32.002 41.448 48.805 47.254 73.123"
                                     stroke="currentColor" stroke-linejoin="round" stroke-width="2"></polygon>
                        </svg>
                        <h3 class="mt-3">This is your inbox</h3>
                        <p>{{$conversations->isEmpty()
                                ? 'You will see your conversations here as soon as you start chatting with your friends!'
                                : 'Send private messages to your friends'}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
