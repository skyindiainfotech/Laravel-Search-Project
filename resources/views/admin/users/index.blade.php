@extends('admin.layout.app')
<?php 
$delete_url = '/admin-users';
?>
@section('content')
@include('admin.includes.flashMsg')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3">
                    <!--begin::Title-->
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Users Management</h1>
                    <!--end::Title-->
                    <!--begin::Separator-->
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <!--end::Separator-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container">
                <!--begin::Card-->
                <div class="card">

                    <!--begin::Modal - Add task-->
                    <div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
                        <!--begin::Modal dialog-->
                        <div class="modal-dialog modal-dialog-centered mw-650px">
                            <!--begin::Modal content-->
                            <div class="modal-content">
                                <!--begin::Modal header-->
                                <div class="modal-header" id="kt_modal_add_user_header">
                                    <!--begin::Modal title-->
                                    <h2 class="fw-bolder">User Details</h2>
                                    <!--end::Modal title-->
                                    
                                </div>
                                <!--end::Modal header-->
                                <!--begin::Modal body-->
                                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                    <!--begin::Form-->
                                    <div class="form">
                                        <!--begin::Scroll-->
                                        <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
                                            
                                            <!--begin::Input group-->
                                            <h3>Member Details</h3>
                                            <div class="fv-row mb-7">
                                                <!--begin::Label-->
                                                <label class="fw-bold fs-6 mb-2" id="member"></label>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <h3>User Details</h3>
                                            <div class="fv-row mb-7">
                                                <!--begin::Label-->
                                                <label class="fw-bold fs-6 mb-2" id="username"></label>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Input group-->
                                            
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-7">
                                                <!--begin::Label-->
                                                <label class="fw-bold fs-6 mb-2"  id="password" ></label>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Input group-->
                                                <!--begin::File group-->
                                            <div class="display-file"></div>
                                            <!--end::File group-->

                                        </div>
                                        <!--end::Scroll-->
                                    </div>
                                    <!--end::Form-->
                                </div>
                                <!--end::Modal body-->
                            </div>
                            <!--end::Modal content-->
                        </div>
                        <!--end::Modal dialog-->
                    </div>
                    <!--end::Modal - Add task-->

                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">Sr. no.</th>
                                    <th class="min-w-125px">Member</th>
                                    <th class="min-w-125px">Username</th>
                                    <th class="min-w-125px">File</th>
                                    <th class="min-w-125px">Last Updated Date</th>
                                    <th class="text-end min-w-100px">Actions</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="text-gray-600 fw-bold">

                            @if(isset($rows) && count($rows) > 0)                    
                                @foreach($rows as $key => $row)
                                <!--begin::Table row-->
                                <tr>
                                    <!--begin::Sr. no. -->
                                    <td>{{ $key+1 }}</td>
                                     <!--end::Sr. no. -->

                                    <!--begin::Member name -->
                                    <td>
                                        {{$row->first_name ?? ''}}
                                        {{$row->last_name ?? ''}}
                                        <br>
                                        {{$row->email ?? ''}}
                                    </td>
                                     <!--end::Member name -->

                                    <!--begin::User name -->
                                    <td>{{$row->username ?? ''}}</td>
                                    <!--end::User name -->
                                    
                                    <!--begin::file name -->
                                    <td>
                                        @if(isset($row->file) && $row->file != '')

                                            @php($fileInfo = pathinfo($row->file))
                                            @php($extension = $fileInfo['extension'] ?? '')
                                            @php($filename = $fileInfo['filename'] ?? '')

                                            <a href="{{ $row->file ?? '' }}" target="_blank">
                                                {{ $filename.".".$extension }}
                                            </a>
                                        @endif
                                    </td>
                                    <!--end::file name -->
                                   
                                    <!--begin::updated at-->
                                    <td>{{ $row->updated_at }}</td>
                                    <!--begin::updated at-->

                                    <!--begin::Action=-->
                                    <td class="text-end">
                                        <a href="javascript:void(0)" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">Actions
                                        <!--begin::Svg Icon | path: icons/duotone/Navigation/Angle-down.svg-->
                                        <span class="svg-icon svg-icon-5 m-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                    <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)" />
                                                </g>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon--></a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3 view-row"  data-id="{{ $row->id ?? '' }}" >
                                                <a class="menu-link px-3">View</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $row->id],'onsubmit' => 'return confirm("Are you sure?")']) !!}

                                            <button type="submit" class="btn-delete-custom menu-link px-3" >Delete</button>

                                            {!! Form::close() !!}
                                                
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                    <!--end::Action=-->
                                </tr>
                                <!--end::Table row-->
                                @endforeach         
                            @else
                                <tr>
                                    <td colspan="4">No Records Found.</td>
                                </tr>                    
                            @endif
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->

<script  type="text/javascript">
$(document).ready(function () {
    
    $('.display-file').empty();
    $('#user_id').val('');
    $user_present = false;

    $(document).on("click",".view-row",function() {
        $id = $(this).data('id');
        if($id != ''){
            jQuery.ajax({
                type: "POST",
                url: "{{ route('get-user-data-by-id-admin') }}",
                data: { 'id' : $id, },
                cache: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(data){
                    data = JSON.parse(data);
                    var $msg = data.msg;
                    if(data.status == 'success'){
                        $('#kt_modal_add_user').modal('show');
                        $('.display-file').empty();
                        $user_present = true;
                        $('#username').html("Username : "+data.content.username);
                        $('#password').html("Password : "+data.content.password);
                        $('#member').html("Member Name : "+data.content.first_name+" "+data.content.last_name);
                        $('.display-file').html('<br><a href="'+data.content.file+'"> File : '+data.content.file_name+'</a>');

                    }else{
                        if(data.slug == "logout"){
                            window.location.href = "{{ url('/login') }}";
                        }
                        popMessage('error',"Didn't found user !");
                    }
                }			
            });	
        }

        if(!$user_present){
            $('.display-file').empty();
        }
    });

  
});  
</script>
@endsection