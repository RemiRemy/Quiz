<?php
$sPhoneNum = '+33649656439'; // Le numéro de téléphone qui recevra l'SMS (avec le préfixe, ex: +33)
$aProviders = array('vtext.com', 'tmomail.net', 'txt.att.net', 'mobile.pinger.com', 'page.nextel.com');

foreach ($aProviders as $sProvider) {
    print_r($sPhoneNum . '@' . $sProvider);
    if (mail($sPhoneNum . '@' . $sProvider, '', 'Ce texto a été envoyé avec PHP, tout simplement !')) {
        break;
    } else {
        continue;
    }
}