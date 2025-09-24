<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">
<html>
<head>
	<meta charset="utf-8" content="">
    <title></title>
    <link href="//fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
    <link href="//fonts.googleapis.com/css?family=Ubuntu:300" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/version_update.css') }}" rel="stylesheet" type="text/css">
</head>
<body>

@if ($new_version_check['has_new_version'] && !$parameters['hosted_mode'])
    <div class="dash_version_iframe panel panel-warning">
		<div class="panel-heading"><span class="icon-update"></span>{{ l('PrestaShop Update') }}</div>
		<div class="panel-body">
			<p>{{ l('You can update to PrestaShop ') }} {{ $new_version_check['version'] }}</p>
			<a class="btn btn-default" target="_blank" href="{{ $new_version_check['link'] }}">{{ l('Download now ') }}</a>
		</div>
	</div>
@else
    <div class="dash_version_iframe panel panel-success">
		<div class="panel-heading"><span class="icon-update"></span>{{ l('PrestaShop Updates') }}</div>
		<div class="panel-body">
			{{ l('Your PrestaShop version is up to date') }}
		</div>
	</div>
@endif

</body>
</html>
