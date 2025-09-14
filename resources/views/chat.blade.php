@extends('layouts.app')

@section('content')
<div class="chat-container">
  <div class="online-users">
    <h3>Online Users</h3>
    <ul id="online-list"></ul>
  </div>

  <div class="chat-box">
    <div id="messages-container">
      {{-- previous messages loaded here --}}
      @foreach ($messages as $msg)
        <div class="message">
          <strong>{{ $msg->from_user->name }}</strong>: {{ $msg->message }}
        </div>
      @endforeach
    </div>

    <form id="message-form">
      @csrf
      <input type="text" name="message" id="message-input" placeholder="Type message..." />
      <button type="submit">Send</button>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){

    const user = {
        id: {{ auth()->user()->id }},
        type: '{{ auth()->user() instanceof \App\Models\Teacher ? "teacher" : "student" }}'
    };

    let other = {
        id: /* id of the person you're chatting with */,
        type: /* "teacher" or "student" */
    };

    // Listen for presence channel to get online users
    Echo.join('online-users')
        .here((users) => {
            updateOnlineList(users);
        })
        .joining((user) => {
            addOnlineUser(user);
        })
        .leaving((user) => {
            removeOnlineUser(user);
        });

    function updateOnlineList(users){
        const ul = document.getElementById('online-list');
        ul.innerHTML = '';
        users.forEach(u => {
            let li = document.createElement('li');
            li.textContent = u.name + ' (' + u.type + ')';
            ul.appendChild(li);
        });
    }

    // Chat private channel
    const channelName = `chat.${user.type}.${user.id}.${other.type}.${other.id}`;
    Echo.private(channelName)
        .listen('MessageSent', (e) => {
            // append message to messages-container
            const mc = document.getElementById('messages-container');
            const div = document.createElement('div');
            div.innerHTML = `<strong>${e.from.name}</strong>: ${e.message}`;
            mc.appendChild(div);
        });

    // Send message via AJAX
    document.getElementById('message-form').addEventListener('submit', function(e){
        e.preventDefault();
        const msg = document.getElementById('message-input').value;

        fetch('/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: msg,
                to_id: other.id,
                to_type: other.type
            })
        }).then(res => res.json())
          .then(data => {
              // clear input
              document.getElementById('message-input').value = '';
          });
    });

});
</script>
@endpush
