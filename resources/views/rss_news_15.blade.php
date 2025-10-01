<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" media="screen" type="text/css" title="" href="/css/news_15.css" />
</head>
<body>
<div id="table_info_news">
    <h5 class="newsTitle"><a href="http://www.prestashop.com/blog/" target="_blank">{{ l('View more') }}</a> {{ l('PrestaShop News !') }}</h5>
    <ul>
        @foreach ($xml_items as $item)
            @if ($loop->first)
                <li id="block_news_first">
                    <a href="{{ $item['link'] }}" target="_blank">
                        {{ truncate($item['title'], 40) }}
                    </a>
                    <p>
                        {{ Str::limit($item['description'], 150, '...') }}
                    </p>
                </li>
            @else
                <li class="block_news_{{ $loop->index % 2 ? 'odd' : 'pair' }}">
                    <a href="{{ $item['link'] }}" target="_blank">
                        {{ truncate($item['title'], 40) }}
                    </a>
                </li>
            @endif
        @endforeach



        <li id="see_newsletter">
            <a href="http://newsletter.prestashop.com/newsletters/" target="_blank">
                {{ l('Read our latest newsletter') }}
            </a>
        </li>
            <li id="follow_on_facebook">
                <a href="http://www.facebook.com/pages/PrestaShop/114089955274622" target="_blank">
                    {{ l('Become fan of prestashop on Facebook') }}
                </a>
            </li>
            <li id="follow_on_twitter">
                <a href="http://www.twitter.com/prestashop" target="_blank">
                    {{ l('Follow us on Twitter') }}
                </a>
            </li>
    </ul>

</div>
</body>
</html>
