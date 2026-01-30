<table style="width: 100%;background: #F3F2EF;border-radius: 6px;padding:20px;font-size:15px">
    <tr>
        <th>

            <table style="width: 500px;font-family: Arial;font-weight:normal" align="center">
                <tr style="text-align: left">
                    <td style="padding-bottom: 15px">
                        <h3 style="margin-bottom: 0px">Hi {{ $customer_name }},</h3>
                        <p style="margin-top:10px;margin-bottom:0px">
                            Terima kasih sudah menghubungi {{ $ba_name }}. Berikut informasi mengenai Tiket Anda
                            pada {{ $ticket->created_at }}
                        </p>
                        @if (@$additionalContent)
                            <p style="margin-top:10px;margin-top:15px;margin-bottom: 0px">
                                {{ $additionalContent }}
                            </p>
                        @endif
                    </td>
                </tr>
                <tr style="text-align: left">
                    <td>
                        <div class="box-chat">
                            <b>Category</b>
                            <p style="margin: 7px 0">{{ $ticket->product_category }}</p>
                            <b style="display:block;margin-bottom:7px">Status Information</b>
                            <table style="width: 100%;margin-left: -2px">
                                <tr>
                                    <td style="padding-bottom: 10px">
                                        Ticket Number : <br>
                                        {{ $ticket->ticket_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px">
                                        Date : <br>
                                        {{ $ticket->date }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px">
                                        Time : <br>
                                        {{ $ticket->time }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px">
                                        Agent : <br>
                                        {{ $agentName }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px">
                                        Notes to/from Caller : <br>
                                        {{ $ticket->note ?: '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom: 10px;padding-top: 20px">
                                        <b>Product</b> <br>
                                        @if (in_array($ticket->product_category, ['Product', 'Form']))
                                            {{ $ticket->product?->name ?: $ticket->product_name }}
                                        @else
                                            {{ $ticket->product_category }} <br>
                                            {{ $ticket->product?->name ?: $ticket->product_name }} <br>
                                            {{ $ticket->product?->detail?->pic_name }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            @foreach ($ticket->bookingDetails as $booking)
                                <table
                                    style="width: 100%;border-top: 1px dashed #ddd;margin-top:15px;padding-top: 10px;margin-left: -2px">
                                    <tr>
                                        <td style="padding-bottom: 10px">
                                            Name : <br>
                                            {{ $booking->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 10px">
                                            Date : <br>
                                            {{ date('Y-m-d', strtotime($booking->counseling_date)) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 10px">
                                            Time : <br>
                                            {{ $booking->time }}
                                        </td>
                                    </tr>
                                    @if($ticket->product_category=='Schedule Other')
                                    <tr>
                                        <td style="padding-bottom: 10px">
                                            Booking Quantity : <br>
                                            {{ $booking->number }}
                                        </td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td style="padding-bottom: 10px">
                                            Booking Number : <br>
                                            {{ $booking->queue_number }}
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            @endforeach
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Demikian informasi yang dapat kami sampaikan.</p>
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
