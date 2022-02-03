@extends('member.layout.app')
<?php $action_url = route('process-verification'); ?>
@section('auth_content')
@include('member.includes.flashMsg')
	<!--begin::Main-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Authentication - Two-stes -->
		<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(assets/media/illustrations/progress-hd.png)">
			<!--begin::Content-->
			<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
				<!--begin::Logo-->
				<a href="index.html" class="mb-12">
					<img alt="Logo" src="{{ asset('/themes/') }}/member/media/logos/logo-2-dark.svg" class="h-45px" />
				</a>
				<!--end::Logo-->
				<!--begin::Wrapper-->
				<div class="w-lg-600px bg-white rounded shadow-sm p-10 p-lg-15 mx-auto">
					<!--begin::Form-->
					{!! Form::open(['url' => $action_url, 'class' => 'form w-100 mb-10', 'id' => 'kt_sing_in_two_steps_form']) !!}
						<!--begin::Icon-->
						<div class="text-center mb-10">
							<img alt="Logo" class="mh-125px" src="{{ asset('/themes/') }}/member/media/icons/gmail.png" />
						</div>
						<!--end::Icon-->
						<!--begin::Heading-->
						<div class="text-center mb-10">
							<!--begin::Title-->
							<h1 class="text-dark mb-3">Two Step Verification</h1>
							<!--end::Title-->
							@if(Session::has('secured_email')) 
								<!--begin::Sub-title-->
								<div class="text-muted fw-bold fs-5 mb-5">Enter the verification code we sent to</div>
								<!--end::Sub-title-->
								<!--begin::Email -->
								@php($email = Session::get('secured_email'))
								<div class="fw-bolder text-dark fs-3">{{ substr($email,0,2).str_repeat("*", strlen($email)-5).substr($email,-3) }}</div>
								<!--end::Email no-->
							@else
								<!--begin::Sub-title-->
								<div class="text-muted fw-bold fs-5 mb-5">Enter the verification code below</div>
								<!--end::Sub-title-->
							@endif
						</div>
						<!--end::Heading-->
						<!--begin::Section-->
						<div class="mb-10 px-md-10">
							<!--begin::Label-->
							<div class="fw-bolder text-start text-dark fs-6 mb-1 ms-1">Type your 6 digit security code</div>
							<!--end::Label-->
							<input type="hidden" name="token_key" value="<?php echo $_GET['token'] ?? ''; ?>" />
							<input type="hidden" name="code" class="code" value="" />
							<!--begin::Input group-->
							<div class="d-flex flex-wrap flex-stack">
								<input type="text" name="" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2 code1" value="" required />
								<input type="text" name="" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2 code2" value="" required/>
								<input type="text" name="" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2 code3" value="" required/>
								<input type="text" name="" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2 code4" value="" required/>
								<input type="text" name="" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2 code5" value="" required/>
								<input type="text" name="" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2 code6" value="" required/>
							</div>
							<!--begin::Input group-->
						</div>
						<!--end::Section-->
						<!--begin::Submit-->
						<div class="d-flex flex-center">
							<button type="submit" id="kt_sing_in_two_steps_submit" class="btn btn-lg btn-primary fw-bolder">
								<span class="indicator-label">Submit</span>
								<span class="indicator-progress">Please wait...
								<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
							</button>
						</div>
						<!--end::Submit-->
					{!! Form::close() !!}
					<!--end::Form-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Content-->
		</div>
		<!--end::Authentication - Two-stes-->
	</div>
	<!--end::Main-->
	<script  type="text/javascript">
$(document).ready(function () {

	jQuery('#kt_sing_in_two_steps_form').submit(function(){ 
		$val = '';
		for(let $i = 1; $i <= 6; $i++) {
			$val += $('.code'+$i).val();
		}
		$('input[name=code]').val($val);
	});
});  
</script>
@endsection