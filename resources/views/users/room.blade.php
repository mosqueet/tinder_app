@extends('layouts.app')
@push('css')
<link rel="stylesheet" href="{{ asset('css/room.css') }}">
@endpush

@section('content')
    <div class="container-fluid h-100">
        <div class="row justify-content-center h-100">

            <div class="chat"style="width:80vw;min-width:300px;max-width:550px;">
                <div class="card">
                    <div class="card-header msg_head bg-secondary">
                        <div class="d-flex bd-highlight">
                            <div class="img_cont">
                                <img src="data:image/png;base64,<?= $user->img_url ?>" class="rounded-circle user_img">
                            </div>
                            <div class="user_info">
                                <span>{{ $user->name }}</span>
                                <p>message count : {{ $user->get_room_messages_count }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body msg_card_body">

                    </div>
                    <div class="card-footer bg-secondary">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text attach_btn">
                                    <i class="fas fa-paperclip"></i>
                                </span>
                            </div>
                            <textarea 
                                id="message" 
                                name="message" 
                                class="form-control type_msg"
                                placeholder="Type your message..."
                            >
                            </textarea>
                            <div class="input-group-append">
                                <span class="input-group-text send_btn">
                                    <i class="fas fa-location-arrow"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}" ></script>
    <script>
        $(document).ready(function(){
            $('.send_btn').on('click', function() {
                message = $('#message').val()
                send_message(message);
            });
            
            // post送信するためcsrfが必須。
            function send_message(message) {
                if (!message) {
                    return false;
                }
                create_message = auth_message(message, msg_time)
                $('.card-body').append(create_message);

                $('#message').val('');

                $.ajax({
                    type: 'post',
                    datatype: 'json',
                    url: "{{ route('store_message', $user) }}",
                    timeout: 3000,
                    data: {
                        'message': message
                    }, //送信するデータを指定
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });
            }
        });

        // html を作成する
        new Date().toLocaleString({ timeZone: 'Asia/Tokyo' })

        Date.prototype.yyyymmddhis = function() {
            var yyyy = this.getFullYear().toString();
            var mm   = (this.getMonth() + 1).toString(); // getMonth() is zero-based
            var dd   = this.getDate().toString();
            var h    = this.getHours().toString();
            var m    = this.getMinutes().toString();
            var s    = this.getSeconds().toString();

            return yyyy + "/" + (mm[1] ? mm : "0" + mm[0]) + "/" + (dd[1] ? dd : "0" + dd[0]) + " " + (h [1] ? h  : "0" + h [0]) + ":" + (m [1] ? m  : "0" + m [0]) + ":" + (s [1] ? s  : "0" + s [0]); // padding
        };

        msg_time = new Date();
        msg_time = msg_time.yyyymmddhis();
        
        // src="data:image/png;base64,<?= Auth::user()->img_url ?>"
        auth_img_url = "data:image/png;base64,<?= Auth::user()->img_url ?>"

        function auth_message(message, msg_time) {
            if (!message) {
                    return false;
            }

            create_message = `<div class="d-flex justify-content-end mb-4">`;
            create_message += '<div class="msg_cotainer_send">';
            create_message += `${message.replace(/\n/g, '<br>')}`;
            create_message += `<span class="msg_time_send text-nowrap">${msg_time}</span>`;
            create_message += '</div>';
            create_message += '<div class="img_cont_msg">';
            create_message += `<img src="${auth_img_url}" class="rounded-circle user_img_msg"></div></div>`;
        
            return create_message;
        }


        //ajaxでメッセージを取得

        getMessages();
        
        function getMessages() {
            $.ajax({
                    type: 'get',
                    datatype: 'json',
                    url: "{{ route('get_messages', $user) }}",
                    timeout: 3000,
                })
                .done(function(data) {
                    console.log(data);
                    auth_id = "{{ Auth::id() }}"
                    result = $(".card-body");
                    messages = [];

                    $.each(data, function(index, value) {
                        message = ``;
                        if (auth_id == value.from_user_id) {
                            message += auth_message(value.message, new Date(value.created_at).yyyymmddhis());
                        } else {
                            message += user_message(value.message, new Date(value.created_at).yyyymmddhis());
                        }
                        messages.push(message);
                    });
                    result[0].innerHTML = messages.join(""); 
                    
                });
        }

        // html の作成
        user_img_url = "data:image/png;base64,<?= $user->img_url ?>";

        function user_message(message, msg_time) {
            create_message = `<div class="d-flex justify-content-start mb-4">`;
            create_message += '<div class="img_cont_msg">';
            create_message += `<img src="${user_img_url}" class="rounded-circle user_img_msg"></div>`;
            create_message += '<div class="msg_cotainer">';
            create_message += `${message.replace(/\n/g, '<br>')}`;
            create_message += `<span class="msg_time text-nowrap">${msg_time}</span>`;
            create_message += '</div></div>';
            console.log(create_message);
            return create_message;
        } 

    </script>
@endpush