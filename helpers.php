<?php

if (!function_exists('page_url')) {
    /**
     * Generate a url for a page
     *
     * @param \Modules\Page\Entities\Page|integer $page
     * @return string
     */
    function page_url($page)
    {
        if (!$page instanceof \Modules\Page\Entities\Page) {
            $page = app('Modules\Page\Repositories\PageRepository')->find($page);
        }
        return url(LaravelLocalization::getCurrentLocale() . '/' . $page->slug);
    }
}
