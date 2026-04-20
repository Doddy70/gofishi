@extends('vendors.layout')

@section('content')
<div class="page-header">
    <h4 class="page-title">{{ __('Chat Inbox') }}</h4>
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{route('vendor.dashboard')}}">
                <i class="flaticon-home"></i>
            </a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">{{ __('Chat Inbox') }}</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Conversations') }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 border-end">
                        <ul class="list-group chat-list">
                            @foreach($chats as $chat)
                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                onclick="loadChat({{ $chat->id }})" style="cursor:pointer;"
                                id="chat-tab-{{ $chat->id }}">
                                @php $uname = $chat->user ? $chat->user->username : 'User'; @endphp
                                <span>{{ $uname }}</span>
                            </li>
                            @endforeach
                            @if($chats->isEmpty())
                            <li class="list-group-item">{{ __('No chats available') }}</li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-8">
                        <div id="chat-window" style="display:none;">
                            <div id="messages-container"
                                style="height: 400px; overflow-y: scroll; border: 1px solid #ebedf2; padding: 15px; margin-bottom: 15px; background: #f9fbfd;">
                                <!-- Messages go here via AJAX -->
                            </div>
                            <form id="send-message-form" method="POST">
                                @csrf
                                <input type="hidden" id="active_chat_id" name="chat_id">
                                <div class="input-group">
                                    <input type="text" id="message-input" name="message" class="form-control"
                                        placeholder="{{ __('Type a message...') }}" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">{{ __('Send') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let activeChatId = null;
    let pollInterval = null;

    function loadChat(chatId) {
        activeChatId = chatId;
        document.getElementById('active_chat_id').value = chatId;
        document.getElementById('chat-window').style.display = 'block';
        fetchMessages();
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(fetchMessages, 5000); // Poll every 5 seconds
    }

    function fetchMessages() {
        if (!activeChatId) return;
        $.ajax({
            url: "{{ url('vendor/chat/messages') }}/" + activeChatId,
            type: "GET",
            success: function (response) {
                let html = '';
                response.messages.forEach(msg => {
                    let alignment = msg.sender_type === 'vendor' ? 'text-right text-primary' : 'text-left text-dark';
                    html += `<div class="${alignment} mb-2"><p class="mb-0 border rounded p-2 d-inline-block bg-white">${msg.message}</p></div>`;
                });
                $('#messages-container').html(html);
                scrollToBottom();
            }
        });
    }

    $('#send-message-form').on('submit', function (e) {
        e.preventDefault();
        let msg = $('#message-input').val();
        if (!msg || !activeChatId) return;

        $.ajax({
            url: "{{ url('vendor/chat/send-message') }}/" + activeChatId,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                message: msg
            },
            success: function (response) {
                $('#message-input').val('');
                fetchMessages();
            }
        });
    });

    function scrollToBottom() {
        let container = document.getElementById("messages-container");
        container.scrollTop = container.scrollHeight;
    }
</script>
@endsection