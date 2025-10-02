OK|
		<div class="admin-box-tips_{{ $tip['name'] }}">
			<h5>{{ $tip['title'] }}</h5>
			<div class="tips-logo"><img src="{{ getImageUrl("/img/tips/tips.png") }}" /></div>
			<div class="tips-title"><a href="{{ $tip['url'] }}" target="_blank"><strong>{{ $tip['advice_title'] }}</strong></a></div><br clear="left" /><br />
			<div class="tips-body">{!! $tip['advice'] !!}
			<br /><br/><a href="{{ $tip['url'] }}" target="_blank"><strong>{{ $tip['more_info'] }}</strong></a>
			</div>
		</div>
		<style>
			.admin-box-tips_{{ $tip['name'] }} { border: 1px solid #cccccc; float: right; font-size: 8pt; margin-bottom: 20px; background-color: #f8f8f8; min-height:220px; border-radius:3px; }
			.admin-box-tips_{{ $tip['name'] }} h5 { color: #FFFFFF; font-size: 9pt; line-height: 29px; margin: 0; padding-left: 15px; background: url({{ getImageUrl("/img/tips-back-" . $tip['name'] . ".png") }}); }
			.tips-logo { float: left; padding-top: 10px; padding-left: 20px; padding-right: 5px; }
			.tips-title { padding-top: 15px; padding-right: 10px }
			.tips-title, a { color: #003368; font-size: 10pt; }
			.tips-body { font-family:Georgia; font-style:italic; font-size:11px; color:#484848; padding: 5px; padding-left: 20px; padding-right: 10px; }
		</style>
