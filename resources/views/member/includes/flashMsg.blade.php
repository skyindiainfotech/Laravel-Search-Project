@if(Session::has('success_message'))
<div class='container'>
    <div class='row'>
        <div class="clearfix">&nbsp;</div>
        <div class='alert alert-success msg-box-hide'>
			{!! Session::get('success_message') !!}
        </div>        
    </div>
</div>    
@endif

@if(Session::has('error_message'))
<div class='container'>
    <div class='row'>
    <div class="clearfix">&nbsp;</div>    
    <div class='alert alert-danger msg-box-hide'>
		{!! Session::get('error_message') !!}
    </div>
    </div>
</div>    
@endif
