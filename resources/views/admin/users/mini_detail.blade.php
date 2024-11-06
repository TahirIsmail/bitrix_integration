@extends('layouts.app')

@section('content')

<div class="container-fluid container">
    <div class="card">
         @include('errors')
         @include('messages')
        <h5 class="card-header">Candidate Details</h5>
        <div class="card-body">

            @if(isset($data))
            <h5>Candidate Detail</h5>
            <p>{!! 'NAME: '. ucwords($data->candidate->name).'<br> EMAIL: '.$data->candidate->email.'<br> PHONE: '.$data->candidate->whatsapp_number!!}</p>
            <br>
            <h5>Candidate Registrations Courses</h5>
            <form action="<?= url('admin/search/users'); ?>" method="POST">
            @csrf
            @method('post')
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="course1">Course1</label>
                        <select id="course1" class="form-control" name="course1">
                        @foreach ($courses as $course)
                            <option value="{{$course->id}}" {{(($course->id == $data->course1)?'selected':'')}}>{{$course->title}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="course1">Course2</label>
                        <select id="course1" class="form-control" name="course1">
                        @foreach ($courses as $course)
                            <option value="{{$course->id}}" {{(($course->id == $data->course2)?'selected':'')}}>{{$course->title}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="course1">Course3</label>
                        <select id="course1" class="form-control" name="course1">
                            @foreach ($courses as $course)
                                <option value="{{$course->id}}" {{(($course->id == $data->course3)?'selected':'')}}>{{$course->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                     <br>
                    <button type="submit" class="btn btn-primary" >Update Courses</button>
                </div>
            </div>
            </form>
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

