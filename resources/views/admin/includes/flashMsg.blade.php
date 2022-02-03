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