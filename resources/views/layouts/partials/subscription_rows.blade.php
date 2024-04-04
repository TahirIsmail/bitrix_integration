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
            <td>1 month</td>
            <td>{{round($charge->amount, 2)}}</td>
        </tr>
    </table>