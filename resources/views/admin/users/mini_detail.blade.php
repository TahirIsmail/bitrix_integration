@extends('layouts.app')

@section('content')

<div class="container-fluid container">
    <div class="card">
         @include('errors')
         @include('messages')
        <h5 class="card-header">Candidate Details</h5>
        <div class="card-body">

            @if(isset($data))
            <p>{!! 'NAME: '. ucwords($data->candidate->name).'<br> EMAIL: '.$data->candidate->email.'<br> PHONE: '.$data->candidate->whatsapp_number!!}</p>
            <br>
            <h5>Candidate Registrations</h5>
            <hr>
            <form id="formSubmit" method="POST">
            @csrf
            @method('post')
            <div class="row">
                <div class="col-md-3">
                <div class="form-group">
                    <label for="charges">Charges</label>
                    <input type="text" class="form-control" name="charges" value="{{$data->amount}}">
                </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="course1">Course1</label>
                        <select id="course1" class="form-control select2" name="course1">
                        <option value="0">Select Course</option>
                        @foreach ($courses as $course)
                            <option value="{{$course->id}}" {{(($course->id == $data->course1)?'selected':'')}}>{{$course->title}}</option>
                        @endforeach
                        </select>
                        <input type="hidden" name="id" value="{{$data->id}}"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="course2">Course2</label>
                        <select id="course2" class="form-control select2" name="course2">
                        <option value="0">Select Course</option>
                        @foreach ($courses as $course)
                            <option value="{{$course->id}}" {{(($course->id == $data->course2)?'selected':'')}}>{{$course->title}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="course3">Course3</label>
                        <select id="course3" class="form-control select2" name="course3">
                            <option value="0">Select Course</option>
                            @foreach ($courses as $course)
                                <option value="{{$course->id}}" {{(($course->id == $data->course3)?'selected':'')}}>{{$course->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                     <br>
                    <button type="submit" class="btn btn-primary" data-type="update">Update Courses</button>
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

$('#formSubmit').on('submit',function(e){
    e.preventDefault()
    $('.error_msg').html('')
    var btnSub = $("#btnSub").attr('data-type')
    const formData = new FormData(this)
    var url = "{{ url('admin/user/update-course') }}";
    btnSub == 'update' && formData.append('_method','PUT')
    $.ajaxSetup({
        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    })
    $.ajax({
        contentType: false,
        cache: false,
        processData: false,
        url: url,
        method: 'POST',
        data: formData,
        beforeSend:function(){
            $("#btnSub").prop("disabled", true);
        },
        success:function(res){
            if(res.msg == 'success'){
                $.notify(res.res, { globalPosition:"top center", autoHideDelay: 5000, className:'success' });
                close()
                $('#btnSub').attr('data-type','create')
                getData()
            }
            else{
                $.notify(res.res, { globalPosition:"top center", autoHideDelay: 5000, className:'error' });
                $('#btnSub').attr('data-type','create')
            }
            $("#btnSub").prop("disabled", false);
        },
        error:function(error){
            $.each(error.responseJSON.errors,function(key,value) {
                $("#"+key+"_msg").html(value);
            });
                $("#btnSub").prop("disabled", false);
        },
    })

})

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

