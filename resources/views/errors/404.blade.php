<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>{{ __('error.error_404') }}</title>
	<link href="{{ asset('public/css/core.min.css') }}" rel="stylesheet">
	<link href="{{ asset('public/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('public/css/styles.css') }}" rel="stylesheet">
	<link rel="shortcut icon" href="{{ url('public/img', config('settings.favicon')) }}" />
</head>

<body>
	<div class="wrap-center">
		<div class="container">
			<div class="row">
				<div class="col-md-12 error-page text-center parallax-fade-top" style="top: 0px; opacity: 1;">
					<a class="navbar-brand" href="https://oprivado.ao">
								<img src="/public/img/logotipo.svg" width="160px" data-logo="logo-1739358685.png" data-logo-2="logo_2-1740329415.png" alt="Oprivado Angola" class="logo align-bottom">
						</a>
					<p style=" font-size: 27px; font-weight: 500; color: black; " class="mt-3 mb-5 text-break">{{ __('error.error_404_subdescription') }}</p>
					<a href="javascript:history.back();" class="error-link mt-5">
						<i class="fa fa-long-arrow-alt-left mr-2"></i> {{ __('auth.back') }}
					</a>
					ou
					<a href="{{url('/')}}" class="error-link mt-5">{{ __('error.go_home') }}</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>