@extends('layouts.app')

@section('content')

<div class="container-fluid container">
    <div class="card">
         @include('errors')
         @include('messages')
        <h5 class="card-header">Search Candidate</h5>
        <div class="card-body">
            <form action="<?= url('admin/search/users'); ?>" method="POST">
            @csrf
            @method('post')
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                    <label>Email</label>
                    <input type="email" required value="{{old('email')}}" class="form-control" name="email"/>
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
            <h5>Candidate Detail</h5>
            <p>{!! 'NAME: '. ucwords($data->name).'<br> EMAIL: '.$data->email.'<br> PHONE: '.$data->whatsapp_number!!}</p>
            <br>
            <h5>Candidate Registrations</h5>
            <table class="table table-striped">
                <thead class="bg-dark text-white">
                    <tr>
                        <th></th>
                        <th>Program</th>
                        <th>Course 1</th>
                        <th>Course 2</th>
                        <th>Course 3</th>
                    </tr>
                </thead>
                <tbody>
                @if(!empty($data->enrollments))
                 @foreach ($data->enrollments as $value)
                     <tr>
                        <td><a class="btn btn-primary btn-sm" href="{{url('mini/candidate/').$value->id}}">View detail</a></td>
                        <td>{{ucwords($value->program)}}</td>
                        <td>{{$value->course1Details->title}}</td>
                        <td>{{$value->course2Details->title}}</td>
                        <td>{{$value->course3Details->title}}</td>
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

