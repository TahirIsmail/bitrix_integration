@extends('layouts.app') @section('content')

<div class="container-fluid container">
    @extends('layout-mini')
    <style type="text/css">
        .table-earning thead th{padding: 10px 29px !important;}
        .table-earning tbody th{font-weight: normal !important;}
        .card{box-shadow: none !important; border: none !important; margin-bottom: 0px !important;}
    </style>
    @section('content')
    <div class="">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card table--no-card">
                    <div class="card-header" style="background-color:#333333; color: white !important;">
                        {{ __('User Information') }}
                    </div>
                    <div class="card-body">
                        @include('messages') @include('errors')
                        <div class="row">

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('Status') }}</label>
                                            <div class="col-md-3">
                                                <?php
   $status = '<div id="status'.@$incubatorDetail->incubator->id.'">';
    if(@$incubatorDetail->incubator->status == '4'){
        $status .= '<span class="badge badge-danger">Expired</span>';
    }
    elseif(@$incubatorDetail->incubator->incubator_status == 'Suspend'){
        $status .= '<span class="badge badge-danger">Expired</span>';
    }
    elseif(@$incubatorDetail->incubator->status == '0'){
        $status .= '<span class="badge badge-warning">Pending</span>';
    }
    elseif(@$incubatorDetail->incubator->status == '1'){
        $status .= '<span class="badge badge-success">Approved</span>';
    }
    elseif(@$incubatorDetail->incubator->status == '2'){
        $status .= '<span class="badge badge-danger">Rejected</span>';
    }
    elseif(@$incubatorDetail->incubator->status == '3'){
        $status .= '<span class="badge badge-warning">Ignore</span>';
    }



    $status .= '</div>';
    echo $status;
    ?>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row justify-content-center " id="post" style="display: none">
                                        <div class="card col-lg-8 border">
                                            <h5 class="card-header d-flex justify-content-between align-items-center bg-dark text-light">
Renew Subscription
<button type="button" class="btn btn-sm btn-danger fas fa-window-close" id="close"></button>
</h5>

                                            <form id="RenewalFrom" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="form-group payment col-md-6" id="payment_mode_box">
                                                            <label class="float-left">Subscription Period</label>
                                                            <select class="form-control" name="payment_mode" id="payment_mode">
                                                                <option value="0" selected>Select Payment Mode</option>
                                                                <option value="1 month" {{ old( 'payment_mode')=='1 month' ? 'selected' : '' }}>1 month</option>
                                                                <option value="3 months - 5% off" {{ old( 'payment_mode')=='3 months' ? 'selected' : '' }}>3 months - 5% off</option>
                                                                <option value="6 months - 10% off" {{ old( 'payment_mode')=='6 months - 10% off' ? 'selected' : '' }}>6 months - 10% off</option>
                                                                <option value="12 months - 15% off" {{ old( 'payment_mode')=='12 months - 10% off' ? 'selected' : '' }}>12 months - 10% off</option>
                                                            </select>
                                                            <small class="text-danger" id="payment_mode_msg"></small>
                                                        </div>
                                                        <div class="form-group payment col-md-6" id="amount_deposit_box">
                                                            <label class="float-left">Amount Deposited (PKR)</label>
                                                            <input type="number" name="amount_deposit" id="amount_deposit" class="form-control {{$errors->has('amount_deposit') ? 'is-danger' : ''}}" value="{{ old('amount_deposit') }}" placeholder="Amount Deposited (PKR)" />
                                                            <small class="text-danger" id="amount_deposit_msg"></small>
                                                        </div>

                                                        <div class="form-group payment col-md-6" id="transaction_id_box">
                                                            <label class="float-left">Transaction ID</label>
                                                            <input type="text" name="transaction_id" id="transaction_id" class="form-control {{$errors->has('transaction_id') ? 'is-danger' : ''}}" value="{{ old('transaction_id') }}" placeholder="Transaction ID Here..." />
                                                            <small class="text-danger" id="transaction_id_msg"></small>
                                                        </div>

                                                        <div class="form-group payment col-md-6">
                                                            <label>Your Bank Account Title</label>
                                                            <input type="text" name="account_title" id="account_title" value="{{ old('account_title') }}" class="form-control {{$errors->has('account_title') ? 'is-danger' : ''}}">
                                                            <small class="text-danger" id="account_title_msg"></small>
                                                        </div>
                                                        <div class="form-group payment col-md-6">
                                                            <label>Your Bank Account Number</label>
                                                            <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}" class="form-control {{$errors->has('account_number') ? 'is-danger' : ''}}">
                                                            <small class="text-danger" id="account_number_msg"></small>
                                                        </div>
                                                        <div class="form-group payment col-md-6" id="payment_proof_box">
                                                            <label>Payment Proof</label>
                                                            <input type="file" name="payment_proof[]" multiple="" id="payment_proof" class="form-control">
                                                            <small class="text-danger" id="payment_proof_msg"></small>
                                                        </div>

                                                        <div class="form-group col-md-6" id="transaction_date_box">
                                                            <label>Date Of Transaction</label>
                                                            <input type="date" name="transaction_date" id="transaction_date" class="form-control {{$errors->has('transaction_date') ? 'is-danger' : ''}}" value="{{ old('transaction_date') }}" />
                                                            <small class="text-danger" id="transaction_date_msg"></small>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Comment</label>
                                                                <textarea class="form-control" name="comment" id="comment" rows="8" placeholder="Comment Here..."></textarea>
                                                                <small class="text-danger" id="comment_msg"></small>
                                                                <input type="hidden" name="email" value="{{$user->email}}" />
                                                                <input type="hidden" id="id" name="id" value="{{@$incubatorDetail->id}}" />
                                                                <input type="hidden" id="paymentId" name="name" value="{{$user->name}}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger closeModal">Close</button>
                                                    <button type="Submit" class="btn btn-primary" id="SubForm">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="name" class="col-md-6 col-form-label text-md-right">{{ __('Full Name') }}</label>
                                            <div class="col-md-6">
                                                {{ $user->name }}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="email" class="col-md-6 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                            <div class="col-md-6">
                                                {{ $user->email }}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="age" class="col-md-6 col-form-label text-md-right">{{ __('Age') }}</label>
                                            <div class="col-md-6">
                                                @if($user->date_of_birth) {{ \Carbon\Carbon::parse($user->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y years, %m months and %d days') }} @else No Data @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="city" class="col-md-6 col-form-label text-md-right">{{ __('City, Country') }}</label>

                                            <div class="col-md-6">
                                                {{ @$user->city.", " .@$user->country->name}}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="fb_profile_link" class="col-md-6 col-form-label text-md-right">{{ __('FB Profile Link') }}</label>

                                            <div class="col-md-6">
                                                @if($user->fb_profile_link) @if(strpos($user->fb_profile_link, "https://") != false)
                                                <a target="_blank" href="{{ $user->fb_profile_link }}">Profile Link</a> @else
                                                <a target="_blank" href="http://{{ $user->fb_profile_link }}">Profile Link</a> @endif @else No Data @endif
                                            </div>
                                        </div>

                                        <div class="row form-group">
                                            <div class="col-md-6 col-form-label text-md-right">
                                                <label for="cnic" class=" form-control-label">Purpose</label>
                                            </div>
                                            <div class="col-md-6">
                                                {{ $incubatorDetail->purpose ?? "Data Unavailable" }}
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="marital_status_id" class="col-md-6 col-form-label text-md-right">{{ __('Marital Status') }}</label>

                                            <div class="col-md-6">
                                                {{ $user->marital_status->name ?? "Data Unavailable" }}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="kids" class="col-md-6 col-form-label text-md-right">{{ __('Kids') }}</label>

                                            <div class="col-md-6">
                                                {{ $user->kids ?? "Data Unavailable" }}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="phone_number" class="col-md-6 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                                            <div class="col-md-6">
                                                {{ $user->phone_number ?? "Data Unavailable" }}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="whatsapp_number" class="col-md-6 col-form-label text-md-right">{{ __('Whatsapp Number') }}</label>

                                            <div class="col-md-6">
                                                {{ $user->whatsapp_number ?? "Data Unavailable" }}
                                            </div>
                                        </div>

                                        <div class="row form-group">
                                            <div class="col-md-6 col-form-label text-md-right">
                                                <label for="cnic" class=" form-control-label">CNIC (Front)</label>
                                            </div>
                                            <div class="col-md-6">
                                                @if($user->cnic)
                                                <div style="cursor:pointer;color:blue;" href="{{ $user->cnic }}" class="lightbox">View</div>
                                                @else No Data @endif
                                            </div>
                                        </div>

                                        <div class="row form-group">
                                            <div class="col-md-6 col-form-label text-md-right">
                                                <label for="cnic" class=" form-control-label">Remarks</label>
                                            </div>
                                            <div class="col-md-6">
                                                {{ $incubatorDetail->comment ?? "Data Unavailable" }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <br>
                    </div>
                </div>

                <div class="card table--no-card">
                    <div class="card-header" style="background-color:#333333; color: white !important;">
                        {{ __('Payment Details') }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 m-b-40">
                                <table class="table table-striped table-responsive table-earning table--no-card">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>ID</th>
                                            <th>Amount Deposit</th>
                                            <th>Payment Mode</th>
                                            <th>Booking Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($incubatorPaymentDetail as $payment)
                                        <tr>
                                            <td>
                                                <?php
                    $status = '<div>';
                    if(@$payment->status == '0'){
                        $status .= '<span class="badge badge-warning">Pending</span> ';
                    }
                    elseif(@$payment->status == '1'){
                        $status .= '<span class="badge badge-success">Approved</span>';
                    }
                    elseif(@$payment->status == '2'){
                        $status .= '<span class="badge badge-danger">Rejected</span>';
                    }
                    elseif(@$payment->status == '3'){
                        $status .= '<span class="badge badge-warning">Awaiting fee</span>';
                    }
                    elseif(@$payment->status == '4'){
                        $status .= '<span class="badge badge-danger">Expired</span>';
                    }
                    elseif(@$payment->status == '5'){
                        $status .= '<span class="badge badge-warning">Refunded</span>';
                    }
                    if (auth('web')->user()->isIncubatorAdmin()) {
                      $status .= '<br><div id="status'.$payment->id.'">';
                    $status .=  '<select name="status" class="form-control status" data-id="'.$payment->incubator_id.'" data-userid="'.$payment->user_id.'" >';
                    $status .= '<option value="0" data-userid="'.@$payment->user_id.'" data-id="'.@$payment->id.'" '.((@$payment->status == '0')?'selected':'').'>Select Status</option>';
                    $status .= '<option value="1" data-userid="'.@$payment->user_id.'" data-id="'.@$payment->id.'" '.((@$payment->status == '1')?'selected':'').'>Approved</option>';
                    $status .= '<option value="2" data-userid="'.@$payment->user_id.'" data-id="'.@$payment->id.'" '.((@$payment->status == '2')?'selected':'').'>Rejected</option>';
                    $status .= '<option value="3" data-userid="'.@$payment->user_id.'" data-id="'.@$payment->id.'" '.((@$payment->status == '3')?'selected':'').'>Awaiting Fee</option>';
                    $status .= '<option value="4" data-userid="'.@$payment->user_id.'" data-id="'.@$payment->id.'" '.((@$payment->status == '4')?'selected':'').'>Expired</option>';
                    $status .= '<option value="5" data-userid="'.@$payment->user_id.'" data-id="'.@$payment->id.'" '.((@$payment->status == '5')?'selected':'').'>Refund</option>';
                    $status .= '</select>';
               $status .= '</div>';
                    }
                    echo $status;
                    ?>
                                            </td>
                                            <td>{{$payment->incubator_id}}</td>
                                            <td>
                                                <?php if (auth('web')->user()->isIncubatorModerator()): ?>
                                                    <input type="number" data-id="{{@$payment->id}}" name="amount_deposit" value="{{$payment->amount_deposit}}" />
                                                    <?php else: ?>
                                                        {{@$payment->amount_deposit}}
                                                        <?php endif ?>

                                            </td>
                                            <td>
                                                {{$payment->payment_mode}}
                                            </td>
                                            <td>Booking Type</td>
                                        </tr>
                                        @endforeach @if(count(@$incubatorPaymentDetail) == 0)
                                        <tr>
                                            <td colspan="6" style="text-align:center">No data available in table</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <br>
                    </div>
                </div>

            </div>

            @endsection @section('script')

            <script>
                $('body').on('keydown', 'input[name=amount_deposit]', function(event) {
                if(event.keyCode == 13){
                let $value = $(this).val();
                let $rowId = $(this).data('id');

                $.ajax({
                url: `{{url('incubator/changeDipositAmount')}}`,
                type: 'POST',
                data: {'value': $value,'id':$rowId},
                })
                .done(function(result) {
                alert("Deposited Amount Updated Successfully", "success");

                })
                .fail(function() {
                alert("Something Went Wrong", "danger");
                });
                }
                });
                $('body').on('change', 'input[name=expiry_date]', function(event) {
                event.preventDefault();
                let $value = $(this).val();
                let $rowId = $(this).data('id');
                let $courseId = $('#selectcourse').val();
                $.ajax({
                url: `{{url('incubator/changeExpiry')}}`,
                type: 'POST',
                data: {'date': $value,'id':$rowId,'type':'payment'},
                })
                .done(function(result) {
                notification("Successfully Set Expiry Date", "success");

                })
                .fail(function() {
                notification("Something Went Wrong", "danger");
                });

                });
                $('body').on('change', 'input[name=joining_date]', function(event) {
                event.preventDefault();
                let $value = $(this).val();
                let $rowId = $(this).data('id');
                let $courseId = $('#selectcourse').val();
                $.ajax({
                url: `{{url('incubator/changeExpiry')}}`,
                type: 'POST',
                data: {'date': $value,'id':$rowId,'type':'payment_joining_date'},
                })
                .done(function(result) {
                notification("Joining Date update Successfully", "success");

                })
                .fail(function() {
                notification("Something Went Wrong", "danger");
                });

                });
                function UserRestriction(obj)
                {
                //console.log($(obj).is(":checked"));
                //e.preventDefault();
                $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                jQuery.ajax({
                url: "{{ url('/students') }}" + "/" + $(obj).attr('user-id'),
                method: 'post',
                data: {
                id: $(obj).attr('user-id'),
                active: $(obj).is(":checked"),
                _token: $('meta[name="_token"]').attr('content'),
                _method: "PATCH"
                },
                success: function(result){
                if(result == 1){
                if($(obj).is(":checked") == true){
                notification("Student " + $(obj).attr('user-name') + " Enabled", "success");
                }
                else{
                notification("Student " + $(obj).attr('user-name') + " Disabled", "danger");
                }
                }
                }});
                }

                $('#open').click(function(e){
                e.preventDefault();
                $('#id').val($(this).data('id'));
                open();
                })

                $('#close').click(function(e){
                e.preventDefault();
                close();

                })
                function open(){
                $('#post').show(500)
                $('#close').show(500)
                $('#details').css('display','none')
                $('#open').css('display','none')


                }
                function close(){
                $('#details').show(500)
                $('#open').show(500)
                $('#post').css('display','none')
                $('#close').css('display','none')
                $('#RenewalFrom')[0].reset();
                $('#durationlabel').html("");
                $('#duration').html("") ;
                $('textarea').val('')
                $('.imgDiv').css('display','none')
                $('.error_msg').html('')
                $('.js-example-basic-multiple').val('');
                $('.js-example-basic-multiple').trigger('change');
                }

                $("#RenewalFrom").on('submit', function(e) {
                e.preventDefault()
                var btn = $('.btn-note'),
                initialText = btn.data('initial-text'),
                loadingText = btn.data('loading-text');
                btn.html(loadingText).addClass('disabled');
                $('.error_msg').html('')
                $.ajax({
                url:'{{url("incubator/renewal")}}',
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                success: function(res) {
                $('.btn-note').html(initialText).removeClass('disabled');
                if(res.msg=='success'){
                Swal.fire('Success','Done Successfully','success')
                close()
                }
                },
                error:function(error){
                $('.btn-note').html(initialText).removeClass('disabled');
                $.each(error.responseJSON.errors,function(key,value) {
                $("#"+key+"_msg").html(value);
                });
                },
                })
                });

                $(document).on('change','.changes',function(e){
                e.preventDefault()
                let val = $(this).val();
                let type = $(this).attr('data-type')
                let id = $(this).attr('data-id')
                $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Confirm!'
                }).then((result) => {
                if (result.value) {

                $.ajax({
                url:`{{ url('/incubator/update-data') }}`,
                type:'POST',
                data:{'type':type,'val':val,'id':id},
                success:function(res){
                if(res.msg == 'success'){
                Swal.fire('Success',res.res,'success')
                getData()
                }
                else if(res.error){
                Swal.fire('Error',res.res,'error')
                }
                else{
                Swal.fire('Error','Error while updatinf status','error')
                }
                }
                })

                }
                else{

                }
                })

                })
                $('select[name=status]').on('change', function(e){
                $('input[name=user_id]').val($(this).find(':selected').data('userid'))
                $('input[name=id]').val($(this).find(':selected').data('id'))
                $('#statusValue').val($(this).val())
                $('#statusModal').modal({
                backdrop: 'static',
                keyboard: false,
                show:true
                })

                })
                $('body').on('click', '#SubForm', function(event) {
                event.preventDefault();

                $('.error_msg').html('')
                var auth = "{{Auth::check()}}"
                if(!auth){
                window.location.href = "{{url('/login')}}"
                return false;
                }
                else{

                var formData = new FormData()
                formData.append('comment',$('#status_comment').val())
                formData.append('status',$('#statusValue').val())
                formData.append('id',$('#id').val())
                formData.append('user_id',$('#user_id').val())
                formData.append('paymentId',$('#paymentId').val())
                formData.append('receivedAmount',$('#receivedAmount').val())
                formData.append('type','inner_update')
                for (var key of formData.entries()) {
                console.log(key[0] + ', ' + key[1]);
                }
                let id = $('#id').val()
                let val = $('#statusValue').val()

                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Confirm!'
                }).then((result) => {
                if (result.value) {
                $.ajaxSetup({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                })
                $.ajax({
                contentType: false,
                cache: false,
                processData: false,
                url: "{{url('incubator/old-status-update')}}",
                method: 'POST',
                data: formData,
                beforeSend:function(){
                $("#SubForm").prop("disabled", true);
                },
                success:function(res){

                if(res.msg == 'success'){
                $('#statusModal').modal('hide')
                $('#comment').val('')
                Swal.fire('Success','Status Update Successfully','success')
                location.reload();
                }
                else{
                Swal.fire('Error','Error while updating status','error')
                }

                $("#SubForm").prop("disabled", false);
                },
                error:function(error){
                $.each(error.responseJSON.errors,function(key,value) {
                $("#"+key+"_msg").html(value);
                });
                $("#SubForm").prop("disabled", false);
                },
                })
                }
                else{
                const id = $('#id').val()
                $(`.current${id}`).prop('disabled',true)
                $(`.current${id}`).prop('selected',true)
                // $(`.current${id}`).attr('disabled',true)

                }
                })
                }

                })

                $('.closeModal').click(function(){
                const id = $('#id').val()
                $('#statusModal').modal('hide')
                $('#incubatorStatusModal').modal('hide')
                $('#incCityTransferModal').modal('hide')
                })
            </script>
            @endsection
