@if(session()->has('message'))
    <div class="alert alert-success text-center" >
        {{ session()->get('message') }}
    </div>
    <script>
    	setTimeout(function() {
    			$(".alert").slideUp();
			}, 30000);
    </script>
@endif
