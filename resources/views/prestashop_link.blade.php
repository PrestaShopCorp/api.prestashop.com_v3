OK|<div id="table_info_link" class="admin-box2">
    <h5>{{ l('prestashop_link_title') }}</h5>
    <ul id="prestashop_link" class="admin-home-box-list" style="background-position: 0 15px;float:left">
        <li style="float:left;height:80px;padding-top:0">
            <p>{{ l('prestashop_link_discover') }}</p>
            <a href="http://doc.prestashop.com/display/PS15/User+Guide" target="_blank">{{ l('prestashop_link_guide') }}</a>
            @if ($parameters['iso_lang'] == 'fr' || $parameters['iso_lang'] == 'en')
            <a href="http://www.lulu.com/spotlight/PRESTASHOP" target="_blank">{{ l('prestashop_link_order_guide') }}</a>
            @endif
            <a href="http://doc.prestashop.com/display/PS15/English+documentation" target="_blank">{{ l('prestashop_link_all_documentation') }}</a>
        </li>
        <li style="float:left">
            <p>{{ l('prestashop_link_forum_announcement') }}</p>
            <a href="http://www.prestashop.com/forums/" target="_blank">{{ l('prestashop_link_forum_link') }}</a>
        </li>
        <li style="float:left">
            <p>{{ l('prestashop_link_themes') }}</p>
            <a href="http://addons.prestashop.com" target="_blank">{{ l('prestashop_link_addons') }}</a>
        </li>
    </ul>
</div>
