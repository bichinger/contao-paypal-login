<?php


namespace Bichinger\Helper;


/**
 * Class UrlHelper
 * @package Bichinger\Helper
 */
class UrlHelper
{

    /**
     * Fetches URL by page id
     *
     * @param $pageId
     * @return string
     */
    public static function getUrlByPageId($pageId)
    {
        // get domain
        $domain = \Environment::get('base');
        // find page by pageid
        $page = \PageModel::findWithDetails($pageId);

        // if domain defined for page, use this one
        if ($page->domain != '') {
            $domain = (\Environment::get('ssl') ? 'https://' : 'http://') . $page->domain . TL_PATH . '/';
        }
        // grab frontendurl and build complete url
        return $domain . \Frontend::generateFrontendUrl($page->row(), false, $page->language);
    }
}