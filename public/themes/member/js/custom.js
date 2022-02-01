function popMessage($status,$msg){
    if($status == 'error'){
        Swal.fire({
            text: $msg,
            icon: $status,
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        })
    }else{
        Swal.fire({
            text: $msg,
            icon: $status,
            showConfirmButton: false,
        })
    }
   
}