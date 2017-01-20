<?php


namespace Bichinger\Helper;


class UrlHelper
{

    public static function getUrlByPageId($pageId)
    {
        $domain = \Environment::get('base');
        $approvedPage = \PageModel::findWithDetails($pageId);

        if ($approvedPage->domain != '') {
            $domain = (\Environment::get('ssl') ? 'https://' : 'http://') . $approvedPage->domain . TL_PATH . '/';
        }

        return $domain . \Frontend::generateFrontendUrl($approvedPage->row(), false, $approvedPage->language);
    }
}