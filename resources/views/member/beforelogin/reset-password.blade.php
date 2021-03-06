@extends('member.layout.app')
<?php $action_url = route('process-reset-password'); ?>
@section('auth_content')
@include('member.includes.flashMsg')
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
			<!--begin::Authentication - New password -->
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(assets/media/illustrations/progress-hd.png)">
				<!--begin::Content-->
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
					<!--begin::Logo-->
					<a href="{{url('/')}}" class="mb-12">
						<img alt="Logo" src="{{ asset('/themes/') }}/member/media/logos/logo-2-dark.svg" class="h-45px" />
					</a>
					<!--end::Logo-->
					<!--begin::Wrapper-->
					<div class="w-lg-550px bg-white rounded shadow-sm p-10 p-lg-15 mx-auto">
						<!--begin::Form-->
						{!! Form::open(['url' => $action_url, 'class' => 'form w-100', 'id' => 'kt_new_password_form']) !!}
							<!--begin::Heading-->
							<div class="text-center mb-10">
								<!--begin::Title-->
								<h1 class="text-dark mb-3">@lang('messages.reset_password')</h1>
								<!--end::Title-->
							</div>
							<!--begin::Heading-->
							<input type="hidden" name="token_key" value="{{ $token ?? '' }}">
							<!--begin::Input group-->
							<div class="mb-10 fv-row" data-kt-password-meter="true">
								<!--begin::Wrapper-->
								<div class="mb-1">
									<!--begin::Label-->
									
									<label class="form-label fw-bolder text-dark fs-6"> @lang('messages.password')</label>
									<!--end::Label-->
									<!--begin::Input wrapper-->
									<div class="position-relative mb-3">
										<input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="password" autocomplete="off" />
										<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
											<i class="bi bi-eye-slash fs-2"></i>
											<i class="bi bi-eye fs-2 d-none"></i>
										</span>
									</div>
									<!--end::Input wrapper-->
									<!--begin::Meter-->
									<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
									</div>
									<!--end::Meter-->
								</div>
								<!--end::Wrapper-->
								<!--begin::Hint-->
								<div class="text-muted">@lang('messages.password_info')</div>
								<!--end::Hint-->
							</div>
							<!--end::Input group=-->
							<!--begin::Input group=-->
							<div class="fv-row mb-10">
								<label class="form-label fw-bolder text-dark fs-6">@lang('messages.confirm_password')</label>
								<input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="confirm_password" autocomplete="off" />
							</div>
							<!--end::Input group=-->
							
							<!--begin::Action-->
							<div class="text-center">
								<button type="submit" id="kt_new_password_submit" class="btn btn-lg btn-primary fw-bolder">
									<span class="indicator-label">@lang('messages.submit')</span>
									<span class="indicator-progress">@lang('messages.please_wait')
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
								</button>
								<a href="{{url('/member')}}" class="btn btn-lg btn-light-primary fw-bolder">@lang('messages.cancel')</a>
							</div>
							<!--end::Action-->
						{!! Form::close() !!}
						<!--end::Form-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Content-->
			</div>
			<!--end::Authentication - New password-->
		</div>
		
<!--begin::Page Custom Javascript(used by this page)-->
<script src="{{ asset('/themes/') }}/member/js/custom/authentication/password-reset/new-password.js"></script>
<!--end::Page Custom Javascript-->
@endsection