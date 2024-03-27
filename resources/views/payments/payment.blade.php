
    <body onload="closethisasap()">
        <div class="box container mb-5" style="display: none;">
        <h3>Manual Payment</h3>
        <form id="manualpaymentform" method="POST" action="{{env('EC_PAY_URL')}}">
            @method('POST')
            @csrf
            <input type="hidden" id="form_id_field" name="id" value="{{$id}}">
            <input type="hidden" id="form_data_field" name="data" value="{{$data}}">
            <div class="col-md-6 mb-3">
                <div class="field-label is-normal">
                    &nbsp;
                </div>
                <div class="field-body pull-right">
                    <div class="field is-fullwidth">
                        <div class="control ">
                            <button id="ajaxSubmit" class="button btn-primary btn-sm">
                                Pay Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            </div>

            
        </form>
    </div>
    </body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
   function closethisasap() {
     document.forms["manualpaymentform"].submit();
      }
</script>

