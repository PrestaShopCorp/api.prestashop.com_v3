<html>
<head>
    <link href="{{ asset('css/hosting_iframe.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
@if ($parameters['iso_lang'] == 'fr')
    <a href="http://www.ovh.com/fr/web/prestashop/" target="_blank">
        <img src="/img/ovh.png" alt="OVH" />
        <button class="btn">{{ l('PrestaShop prêt à l\'emploi') }}</button>
    </a>
@elseif ($parameters['iso_lang'] == 'es')
    <a href="http://www.1and1.es/hosting-linux?couponCode=JCPEABAW&ac=OM.WE.WE903K14156T7073a&ref=676625" target="_blank" style="background: white url(/img/1and1.png) no-repeat 0 0;display:block;width:198px;height:198px;padding-top:160px">
        <button class="btn">Hosting para Prestashop</button>
    </a>
@else
    <a href="http://www.inmotionhosting.com/prestashop-promo" target="_blank">
        <img src="/img/inmotion.png" alt="Inmotion Hosting" />
        <button class="btn">PrestaShop Web Hosting</button>
    </a>
@endif
</body>
</html>

