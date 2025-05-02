@extends('layouts.terminal')
@section('content')
<h1>Тестовый заголовок</h1>
<div class="chat">
    <div class="top">
        <div>
            <p>
                {{ $friend->first_name }} {{ $friend->last_name }}
            </p>
        </div>
    </div>
    <div class="messages">
        @foreach($messages as $message)
            @include('messages.receive', ['message' => $message])
        @endforeach

    </div>
    <div class="bottom">
        <form id="chat-form">
            <input type="text" id="message" placeholder="Message...">
            <button type="submit">Send</button>
        </form>
    </div>
    <script>
        window.chatConfig = {
            authId: {{ auth()->id() }},
            friendId: {{ $friend->id }},
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    @vite(['resources/js/app.js'])
</div>
@endsection