@extends('layouts.user')

@section('css')
    <link rel="stylesheet" href="{{asset('/css/messages.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

@endsection

@section('content')
    <div class="container py-5 px-4 messenger">
        <div class="row rounded-lg overflow-hidden shadow h-100">
            <!-- Users box-->
            <div class="col-5 px-0">
                <div class="bg-white">

                    <div class="bg-gray px-4 py-2 bg-light">
                        <p class="h5 mb-0 py-1">Recent</p>
                    </div>

                    <div class="messages-box">
                        <div class="list-group rounded-0">@foreach($conversations as $conv)
                                @php
                                    if ($conv->id == $current_conversation) {
                                    $friend_profile_picture = $conv->friend_profile_picture_path;
                                    }
                                @endphp
                                <a href="{{route('message_history',["conversation" => $conv->id])}}"
                                    @class([
                                            'list-group-item list-group-item-action rounded-0',
                                            'active text-white' => $conv->id == $current_conversation,
                                            'list-group-item-light' => $conv->id != $current_conversation
                                    ])>
                                    <div class="media">
                                        <div class="circular--user--icon"><img
                                                src="{{App\Models\User::return_profile_picture($conv->friend_profile_picture_path)}}"
                                                alt="user">
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
            <!-- Chat Box-->
            <div class="col-7 px-0 h-100">
                <div class="px-4 py-5 chat-box bg-white">
                    <div id="message_history"></div>
                    <!-- Sender Message-->
                    <div id="message_input">
                        <form onsubmit="return sendMessage(event)"
                              class="bg-light" method="POST">
                            <div class="input-group">
                                <input type="text" id="message_content" name="message_content"
                                       placeholder="Type a message"
                                       aria-describedby="button-addon2"
                                       class="form-control rounded-0 border-0 py-4 bg-light">
                                <div class="input-group-append">
                                    <button id="sendMessageBtn" type="submit" class="btn btn-link"><i
                                            class="fa fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        @endsection
        @section('scripts')
            <script>
                const authUserId = {{auth()->id()}};
                const apiToken = "{{auth()->user()->api_token}}";
                var messages = <?php echo $messages ?>;
                var messageHistoryContainer;

                async function sendMessage(event) {
                    event.preventDefault();

                    let formData = new FormData(event.target);
                    if (!formData.get('message_content')) {
                        return;
                    }

                    const submitBtn = document.getElementById('sendMessageBtn');
                    submitBtn.disabled = true;

                    const storeMessageRoutePath = "{{route('messages.store',["conversation" => $current_conversation])}}";

                    const response = await fetch(storeMessageRoutePath, {
                        method: "post",
                        body: formData,
                        headers: {
                            'Authorization': 'Bearer ' + apiToken,
                            'Accept': 'application/json'
                        },
                    });

                    var message = await response.json();
                    displayMessage(message);
                    event.target.reset();
                    scrollMessageHistoryContainerToBottom();
                    submitBtn.disabled = false;
                }

                function displayMessage(message) {
                    if (messages.some(m => m.id === message.id)) {
                        return;
                    }

                    messages.push(message);

                    let messageDisplay = getMessageDisplay(message);
                    messageHistoryContainer.innerHTML += messageDisplay;
                }

                const dateTimeFormat = {
                    day: 'numeric',
                    month: 'numeric',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    second: '2-digit'
                };

                function getMessageDisplay(message) {
                    const createdAt = new Date(message['created_at']);
                    const formattedDateTime = createdAt.toLocaleDateString('en-GB', dateTimeFormat);

                    if (authUserId == message['created_by']) {
                        return `
                                <div class="media w-50 ml-auto mb-3">
                                    <div class="media-body">
                                        <div class="bg-primary rounded py-2 px-3 mb-2">
                                            <p class="text-small mb-0 text-white">${message['content']}</p>
                                         </div>
                                         <p class="small text-muted">${formattedDateTime}</p>
                                    </div>
                                </div>`;
                    }
                    return `
                            <div class="media w-50 mb-3">
                                <div class="circular--user--icon">
                                    <img
                                        src="{{App\Models\User::return_profile_picture($friend_profile_picture)}}"
                                        alt="user"/>
                                        </div>
                               <div class="media-body ml-3">
                                   <div class="bg-light rounded py-2 px-3 mb-2">
                                       <p class="text-small mb-0 text-muted">${message['content']}</p>
                                   </div>
                                   <p class="small text-muted">${formattedDateTime}</p>
                               </div>
                            </div>`;
                }

                function scrollMessageHistoryContainerToBottom() {
                    messageHistoryContainer.scrollTop = messageHistoryContainer.scrollHeight;
                }
            </script>
            <script type="module">
                document.addEventListener("DOMContentLoaded", () => {
                    messageHistoryContainer = document.getElementById('message_history');
                    messageHistoryContainer.innerHTML = messages.map(message => getMessageDisplay(message))
                        .join('');

                    scrollMessageHistoryContainerToBottom();

                    @if($current_conversation)
                    Echo.channel('conversations.' + {{ $current_conversation }})
                        .listen('NewMessageEvent', e => {
                            displayMessage(e.message);
                            scrollMessageHistoryContainerToBottom();
                        });
                    @endif
                });
            </script>
@endsection
