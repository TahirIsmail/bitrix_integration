@extends('layouts.app')

@section('content')

<div class="container-fluid container">
    <div class="row" style="margin-bottom:20px">

        <div class="col-lg-12" id="post" style="display:none;">
            <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <div class="col-md-9">
                            <h4 style="font-weight:600; float:left; margin-top:5px">Coupons</h4>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-danger btn-sm" id="close" style="float:right"><span class="fa fa-times"></span></button>
                        </div>
                    </div>
                    <form id="formSubmit">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Coupon Title"/>
                                    <input type="hidden" name="id" id="id">
                                    <small class="error_msg text-danger" id="title_msg"></small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Discount</label>
                                    <input type="number" name="discount" id="discount" class="form-control"/>
                                    <small class="error_msg text-danger" id="discount_msg"></small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Expiry Date</label>
                                    <input type="date" name="expiry" id="expiry" class="form-control"/>
                                    <small class="error_msg text-danger" id="expiry_msg"></small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description (optional)</label>
                                    <textarea name="description" id="description" class="form-control" placeholder="Description"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="col-md-12">
                            <button type="submit" id="btnSub" data-type="create" class="btn btn-primary mb-2" style="float:right">Submit</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>


            <div class="col-lg-12" id="details">
                <div class="card">
                    <div class="card-header">
                        <div class="col-md-9">
                            <h4 style="font-weight:600; float:left; margin-top:5px">Manage Coupons</h4>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="open" class="btn btn-sm btn-primary" style="float:right">Create Coupon</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover js-basic-example table_custom border-style spacing5">
                                <thead>
                                    <tr>
                                        <th>Coupon Code</th>
                                        <th>Title</th>
                                        <th>Discount</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.min.js"></script>
<script>

$('#formSubmit').on('submit',function(e){
    e.preventDefault()
    $('.error_msg').html('')
    var btnSub = $("#btnSub").attr('data-type')
    const formData = new FormData(this)
    var url = btnSub == 'create' ? "{{ url('coupons') }}" : `{{url('/coupons')}}/${$('#id').val()}`
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

$(document).on('click','.edit',function(e){
    e.preventDefault()
    let id = $(this).attr('data-id')
    $.ajax({
        url:`{{ url('coupons') }}/${id}/edit`,
        type:'GET',
        success:function(res){
            if(res.msg == 'success'){
                $('#title').val(res.post.name)
                $('#expiry').val(res.post.expires_at)
                $('#discount').val(res.post.discount_amount)
                $('#id').val(res.post.id)
                $('#description').val(res.post.description)
                $('#btnSub').attr('data-type','update')
                open()
            }
            else{
                Swal.fire('Error','Unable to fetch record','error')
            }
        }
    })
})

$(document).on('click','.deleted',function(e){
    e.preventDefault()
    let id = $(this).attr('data-id')
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajaxSetup({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            })
            $.ajax({
                url:`{{ url('coupons') }}/${id}`,
                type:'DELETE',
                success:function(res){
                    if(res.msg == 'success'){
                        Swal.fire('Success',res.res,'success')
                        getData()
                    }
                    else{
                        Swal.fire('Error','Error while deleteing record','error')
                    }
                }
            })
    }
    })
})


getData()

function getData(){
    $('#datatable').DataTable().destroy();
        var userTable = $('#datatable').DataTable({
          processing: true,
          serverSide: true,
          responsive: false,
          language: {
              search: "",
              searchPlaceholder: "Search records"
         },
          ajax:{
              url: "{{ url('coupons') }}"
           },
          columns: [
            { data: 'code', name: 'code' },
            { data: 'title', name: 'title' },
            { data: 'discount', name: 'discount' },
            { data: 'description', name: 'description' },
            { data: 'expiry', name: 'expiry' },
            { data: 'created_by', name: 'created_by' },
            { data: 'action', name: 'action', orderable: false },
        ]

    });
}




$('#open').click(function(e){ e.preventDefault(); open(); })
$('#close').click(function(e){ e.preventDefault(); close(); })

function open(){
    $('#post').show(1000)
    $('#close').show(1000)
    $('#details').css('display','none')
    $('#open').css('display','none')
}

function close(){
    $('#details').show(1000)
    $('#open').show(1000)
    $('#post').css('display','none')
    $('#close').css('display','none')
    $('#formSubmit')[0].reset();
    $('textarea').val('')
    $('.imgDiv').css('display','none')
    $('#btnSub').attr('data-type','create')
}

$(document).on('click','.showMore',function(){
    let id = $(this).attr('data-id')
    let type = $(this).attr('data-type')
    let less = $(this).attr('data-less')
    let more = $(this).attr('data-content')
    if(type == 'less'){
        $(`#${id}`).attr('data-type','more')
        $(`#content${id}`).html(more)
        $(`#${id}`).html('[See Less]')
    }
    else if(type == 'more'){
        $(`#${id}`).attr('data-type','less')
        $(`#content${id}`).html(less)
        $(`#${id}`).html('[See More]')
    }
})

</script>

@endsection
