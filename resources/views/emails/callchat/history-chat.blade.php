<table style="width: 100%;background: #F3F2EF;border-radius: 6px;padding:20px;font-size:15px">
    <tr>
        <th>

            <table style="width: 500px;font-family: Arial;font-weight:normal" align="center">
                <tr style="text-align: left">
                    <td>
                        <h3 style="margin-bottom: 0px">Hi {{ $customer_name }},</h3>
                        <p style="margin-top:10px;margin-bottom:0px">
                            Please find below the transcript of the chat session with<br>
                            {{ $ba_name }} on {{ $date }} :
                        </p>
                    </td>
                </tr>
                <tr style="text-align: left">
                    <td>
                        <div class="box-chat" style="margin-top:20px;border-radius:5px">
                            <ul style="list-style: none;padding-left:0px;font-size:14px">
                                @foreach ($conversation as $chat)
                                    <li style="padding-top: 0px;margin-left:0px">
                                        <p>
                                            @if($chat->message_type!='information')
                                                {{ $chat->date }}
                                                <span>{{ $chat->user_name }}</span>
                                                :
                                            @endif
                                            @if ($chat->message_type === 'file')
                                                <a href="{{ $chat->message->url }}" target="_blank">
                                                    {{ $chat->message->name }}
                                                </a>
                                            @elseif($chat->message_type === 'location')
                                                <div>
                                                    <a target="_blank"
                                                        href="https://maps.google.com/maps?q={{ $chat->message->lat }},{{ $chat->message->lng }}&hl=es;z=10&output=embed">
                                                        https://maps.google.com/maps?q={{ $chat->message->lat }},{{ $chat->message->lng }}&hl=es;z=10&output=embed
                                                    </a>
                                                </div>
                                            @else
                                                {{ $chat->message }}
                                            @endif
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr style="text-align: left">
                    <td>
                        <p style="margin-top:20px;margin-bottom:0px">Thanks,</p>
                        <p style="margin-top:10px">{{ $ba_name }}</p>
                    </td>
                </tr>
            </table>

        </th>
    </tr>
</table>
