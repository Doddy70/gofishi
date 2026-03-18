@extends('frontend.layout')

@section('pageHeading')
  {{ __('Chat Inbox') }}
@endsection

@section('content')
<section class="user-dashboard py-60">
  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        @includeIf('frontend.user.side-bar')
      </div>
      <div class="col-lg-9">
        <div class="user-wrapper">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-card p-0 overflow-hidden" style="border: 1px solid #eee; border-radius: 12px; height: 600px;">
                <div class="row g-0 h-100">
                  <!-- Contact List -->
                  <div class="col-md-4 border-end" style="background: #f9f9f9;">
                    <div class="p-3 border-bottom bg-white">
                      <h5 class="mb-0 fw-bold">{{ __('Messages') }}</h5>
                    </div>
                    <div class="overflow-auto" style="height: calc(600px - 60px);">
                      @forelse($chats as $chat)
                        <a href="javascript:void(0)" onclick="loadChat({{ $chat->id }}, '{{ $chat->vendor->username }}')" class="chat-contact-item p-3 d-flex align-items-center border-bottom text-decoration-none text-dark {{ request()->query('chat_id') == $chat->id ? 'bg-white active' : '' }}" id="chat-item-{{ $chat->id }}">
                          <div class="flex-shrink-0">
                            <img src="{{ asset('assets/admin/img/vendor-photo/' . $chat->vendor->photo) }}" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;" onerror="this.src='{{ asset('assets/img/blank-user.jpg') }}'">
                          </div>
                          <div class="ms-3 overflow-hidden">
                            <h6 class="mb-0 text-truncate fw-bold">{{ $chat->vendor->username }}</h6>
                            <p class="mb-0 text-truncate text-muted small" id="last-msg-{{ $chat->id }}">
                              {{ $chat->messages->first() ? $chat->messages->first()->message : __('No messages yet') }}
                            </p>
                          </div>
                        </a>
                      @empty
                        <div class="p-4 text-center text-muted">
                          {{ __('No active chats found.') }}
                        </div>
                      @endforelse
                    </div>
                  </div>

                  <!-- Chat Window -->
                  <div class="col-md-8 d-flex flex-column bg-white">
                    <div id="chat-header" class="p-3 border-bottom d-none">
                      <h6 class="mb-0 fw-bold" id="chat-with-name"></h6>
                    </div>
                    
                    <div id="message-container" class="flex-grow-1 p-3 overflow-auto d-flex flex-column" style="background: #fff;">
                      <div class="m-auto text-center text-muted" id="no-chat-selected">
                        <i class="fal fa-comments fa-3x mb-3"></i>
                        <p>{{ __('Select a contact to start messaging') }}</p>
                      </div>
                    </div>

                    <div id="chat-footer" class="p-3 border-top d-none">
                      <form id="chat-form" class="h-stack gap-2">
                        @csrf
                        <input type="text" id="chat-input" class="form-control" placeholder="{{ __('Type a message...') }}" autocomplete="off">
                        <button type="submit" class="btn btn-primary">
                          <i class="fal fa-paper-plane"></i>
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .chat-contact-item:hover { background: #fff; }
  .chat-contact-item.active { border-left: 4px solid #FF385C; background: #fff !important; }
  .message-bubble { max-width: 75%; padding: 10px 15px; border-radius: 18px; margin-bottom: 10px; font-size: 14px; line-height: 1.4; }
  .message-me { align-self: flex-end; background: #FF385C; color: #fff; border-bottom-right-radius: 4px; }
  .message-them { align-self: flex-start; background: #f1f0f0; color: #333; border-bottom-left-radius: 4px; }
</style>

<script>
  let currentChatId = null;
  let pollingInterval = null;

  function loadChat(chatId, vendorName) {
    currentChatId = chatId;
    $('#chat-header').removeClass('d-none');
    $('#chat-footer').removeClass('d-none');
    $('#no-chat-selected').addClass('d-none');
    $('#chat-with-name').text(vendorName);
    $('.chat-contact-item').removeClass('active');
    $(`#chat-item-${chatId}`).addClass('active');
    
    fetchMessages();
    
    if (pollingInterval) clearInterval(pollingInterval);
    pollingInterval = setInterval(fetchMessages, 3000);
  }

  function fetchMessages() {
    if (!currentChatId) return;
    
    $.get(`{{ url('/user/chat/messages') }}/${currentChatId}`, function(data) {
      let html = '';
      data.messages.forEach(msg => {
        const bubbleClass = msg.sender_type === 'user' ? 'message-me' : 'message-them';
        html += `<div class="message-bubble ${bubbleClass}">${msg.message}</div>`;
      });
      $('#message-container').html(html);
      $('#message-container').scrollTop($('#message-container')[0].scrollHeight);
    });
  }

  $('#chat-form').on('submit', function(e) {
    e.preventDefault();
    const message = $('#chat-input').val();
    if (!message || !currentChatId) return;

    $.post(`{{ url('/user/chat/send-message') }}/${currentChatId}`, {
      _token: '{{ csrf_token() }}',
      message: message
    }, function(res) {
      $('#chat-input').val('');
      $(`#last-msg-${currentChatId}`).text(message);
      fetchMessages();
    });
  });

  // Auto-load if chat_id in URL
  $(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const chatId = urlParams.get('chat_id');
    if (chatId) {
      $(`#chat-item-${chatId}`).click();
    }
  });
</script>
@endsection
