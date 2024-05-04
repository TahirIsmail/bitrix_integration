<div class="s_card">
    <p class="s_card-title">Personal Information</p>
    <table id="top-table">

        <tr>
            <td>Name : {{$data['user_name']}}</td>
            <td>Email : {{$data['email']}}</td>

        </tr>
        <tr>
            <td>Gender : {{$data['gender']}}</td>
            <td>Mobile/WhatsApp No : {{$data['whatsapp_number']}}</td>

        </tr>
        <tr>
            <td>CNIC No : {{$data['cnic_number']}}</td>
            <td>Facebook Profile : {{$data['facebook_profile']}}</td>

        </tr>
    </table>
    <div class="go-corner">
        <div class="go-arrow">2</div>
    </div>
</div>


<div class="s_card">
    <p class="s_card-title">Payment Summary</p>



    <table id="top-table">
        <tr>
            <th>INCUBATOR CITY</th>
            <th>SHIFT</th>
            <th>SUBSCRIPTION PERIOD</th>
            <th>TOTAL AMOUNT TO DEPOSIT (PKR)</th>
        </tr>
        <tr>
            <td>{{$data['incubator_city']}}</td>
            <td>{{$data['shift']}}</td>
            <td>
                {{ ($data['subscription_period'] == 7) ? '7 months or more' : ($data['subscription_period'].' month'.($data['subscription_period'] > 1 ? 's' : '')) }}
            </td>
            <td>{{$data['totalAmount']}}</td>
        </tr>



    </table>
    <div class="go-corner">
        <div class="go-arrow">3</div>
    </div>
</div>
