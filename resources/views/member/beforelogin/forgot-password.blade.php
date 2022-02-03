@extends('member.layout.app')
<?php $action_url = route('process-forgot-password'); ?>
@section('auth_content')
@include('member.includes.flashMsg')
<div class="d-flex flex-column flex-root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url({{ asset('/themes/') }}/admin/media/illustrations/progress-hd.png)">
				<!--begin::Content-->
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
					<!--begin::Logo-->
					<a href="{{url('admin/')}}" class="mb-12">
						<img alt="Logo" src="{{ asset('/themes/') }}/admin/media/logos/logo-2-dark.svg"
						class="h-45px" />
					</a>
					<!--end::Logo-->
					<!--begin::Wrapper-->
					<div class="w-lg-500px bg-white rounded shadow-sm p-10 p-lg-15 mx-auto">
						<!--begin::Form-->
						{!! Form::open(['url' => $action_url, 'class' => 'form w-100', 'id' => 'kt_sign_in_form']) !!}
							<!--begin::Heading-->
							<div class="text-center mb-10">
								<!--begin::Title-->
								<h1 class="text-dark mb-3"> @lang('messages.forgot_password')</h1>
								<!--end::Title-->
							</div>
							<!--begin::Heading-->
							<!--begin::Input group-->
							<div class="fv-row mb-10">
								<!--begin::Label-->
								<label class="form-label fs-6 fw-bolder text-dark">@lang('messages.email')</label>
								<!--end::Label-->
								<!--begin::Input-->
								<input class="form-control form-control-lg form-control-solid" type="email" 
								name="email" autocomplete="off" />
								<!--end::Input-->
							</div>
							<!--end::Input group-->
							<!--begin::Actions-->
							<div class="text-center">
								<!--begin::Submit button-->
								<button type="submit" id="kt_sign_in_submit" 
									class="btn btn-lg btn-primary w-100 mb-5">
									<span class="indicator-label">@lang('messages.submit')</span>
									<span class="indicator-progress"> @lang('messages.please_wait')
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
									</span>
								</button>
								<!--end::Submit button-->
								<!--begin::Submit button-->
								<a type="submit" href="{{ url('/login') }}"
									class="btn btn-lg btn-outline-dark w-100 mb-5">
									<span class="indicator-label">@lang('messages.back')</span>
								</a>
								<!--end::Submit button-->
							</div>
							<!--end::Actions-->
						{!! Form::close() !!}
						<!--end::Form-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Content-->
				
			</div>
			<!--end::Authentication - Sign-in-->
		</div>

		<!--end::Main-->
		
@endsection