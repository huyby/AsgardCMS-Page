<?php

if (!function_exists('page_url')) {
    /**
     * Generate a url for a page
     *
     * @param \Modules\Page\Entities\Page|integer $page
     * @param string $locale
     * @return string|null
     */
    function page_url($page, $locale = null)
    {
        if (!$page instanceof \Modules\Page\Entities\Page) {
            $page = app('Modules\Page\Repositories\PageRepository')->find($page);
        }
        if (!$page) {
            return null;
        }
        if ($locale !== null) {
            $page = $page->getTranslation($locale);
        }
        return url(($locale ?: LaravelLocalization::getCurrentLocale()) . '/' . ($page ? $page->slug : ''));
    }
}
