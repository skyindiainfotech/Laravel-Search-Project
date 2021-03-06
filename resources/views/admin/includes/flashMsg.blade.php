@if(Session::has('success'))
<script  type="text/javascript">
$(document).ready(function () {
    $msg = "{{ Session::get('success') }}";
    popMessage('success',$msg);
});  
</script>    

@endif

@if(Session::has('error'))  
<script  type="text/javascript">
$(document).ready(function () {
    $msg = "{{ Session::get('error') }}";
    popMessage('error',$msg);
}); 
</script>

@endif


@if ($errors->any())

@php($error_str = '')
@foreach ($errors->all() as $error)
    @php($error_str .= $error)
@endforeach

<script  type="text/javascript">
$(document).ready(function () {
    $msg = "Whoops!"+" {{ __('input_issue') }}";
    $msg += "{{ $error_str }}";
    popMessage('error',$msg);
}); 
</script>
@php($errors = array())
@endif