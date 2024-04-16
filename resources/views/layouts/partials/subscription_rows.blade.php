<table id="bottom-table">


   

    <tr>
        <th>
            INCUBATOR CITY</th>
            <th>SHIFT</th>
            <th>SUBSCRIPTION PERIOD</th>
            <th> TOTAL AMOUNT TO DEPOSIT (PKR)</th>
            
        </tr>
        <tr>
            <td>{{$city->name}}</td>
            <td>{{$shift->name}}</td>
            <td>{{($subscription_months == 7) ?? $subscription_month.'months or more' : $subscription_months.'month'}}</td>
            <td id="totalAmount">{{round($totalAmount, 2)}}</td>
            {{-- {{$totalAmount}} --}}
        </tr>
    </table>