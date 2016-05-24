<?php namespace Modules\Page\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Page\Entities\PageType;

interface PageRepository extends BaseRepository
{
    /**
     * Find the page set as homepage
     * @return object
     */
    public function findHomepage();

    /**
     * Count all records
     * @return int
     */
    public function countAll();

    /**
     * @param $slug
     * @param $locale
     * @return object
     */
    public function findBySlugInLocale($slug, $locale);
}
