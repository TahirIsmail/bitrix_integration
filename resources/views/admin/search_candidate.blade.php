@extends('layouts.app')

@section('content')

<div class="container-fluid container">
    <div class="card">
         @include('errors')
         @include('messages')
        <h5 class="card-header">Search Candidate</h5>
        <div class="card-body">
            <form action="<?= url('admin/dtc-community-modifications'); ?>" method="POST">
            @csrf
            @method('post')
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                    <label>Email</label>
                    <input type="email" required value="{{old('email')}}" class="form-control" name="email"/>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                    <label>Select Type</label>
                    <select class="form-control" name="type">
                    <option value="digital-incubator">Digital Incubator</option>
                    <option value="community" {{((old('type') == 'community')?'selected':'')}}>Community</option>
                    </select>
                    </div>
                </div>
                <div class="col-md-2">
                     <br>
                    <button type="submit" class="btn btn-primary" >Search</button>
                </div>
            </div>
            </form>
            <hr>
            <br>
            @if(isset($data))
            <h3>Candidate Detail
            <form action="{{url('admin/incubator/delete-incubatee')}}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{$data->id}}"/>
            <button class="btn btn-sm btn-danger" style="float:right;" onclick="return confirm('Are you sure you would like to delete this Incubatee?');">Delete Incubatee</button>
            </form>
            </h3>
            <p>{!!ucwords($data->name).'<br>'.$data->email.'<br>'.$data->whatsapp_number!!}</p>
            <br>
            <table class="table table-striped">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Status</th>
                        <th>Applied Date</th>
                        <th>Amount</th>
                        <th>Expiry</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @if($data->count() > 0)

                 @foreach ($data as $value)
                     <tr>
                        <td>{{uppercase($value->status)}}</td>
                        <td>{{(($value->created_at != null)?$value->created_at->format('d-M-Y m:h:s'):'NAN')}}</td>
                        <td>{{$value->subscription_period}}</td>
                        <td><input type="text" data-id="{{$value->id}}" value="{{$value->totalAmount}}" name="amount_deposit"/><br>
                        {{@$value->voucher->reference_no}}
                        </td>
                        <td>{{(($value->expiry_date != null)?\Carbon\Carbon::parse($value->expiry_date)->format('d-M-Y'):'NAN')}}</td>
                        <td>
                            @if($value->status == 'pending')
                            <a href="{{url('admin/incubator/regenerate-voucher/'. @$value->id)}}" class="btn btn-sm btn-primary">Recreate Voucher</a>
                            <form action="{{url('admin/incubator/delete-incubatee-subscription')}}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{$value->id}}" />
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you would like to delete this entry?');">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                 @endforeach
                @else
                <tr>
                    <td colspan="6" class="text-center">No Data found</td>
                </tr>
                @endif
                </tbody>
            </table>
            @else
                No Data Found.
            @endif
        </div>
  </div>
</div>

@endsection

@section('script')
<script>
$('body').on('keydown', 'input[name=amount_deposit]', function(event) {
            if(event.keyCode == 13){
                let $value = $(this).val();
                let $rowId = $(this).data('id');
                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                $.ajax({
                    url: `{{url('admin/incubator/changeDipositAmount')}}`,
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
</script>

@endsection

