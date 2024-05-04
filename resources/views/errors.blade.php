@if($errors->any())

    <div class="alert alert-warning text-center" >

          <ul>

          @foreach($errors->all() as $error)

                <li>{!! $error !!}</li>

          @endforeach

          </ul>

    </div>
 <script>
    	setTimeout(function() {
    			$(".alert").slideUp();
			}, 30000);
    </script>
@endif

