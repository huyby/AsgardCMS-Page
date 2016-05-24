<?php namespace Modules\Page\Services;

use Modules\Page\Repositories\PageTypeRepository;

class PageTypeService
{
    /**
     * @var PageTypeRepository
     */
    protected $pageTypeRepository;

    protected $pageTypes = [];

    /**
     * PageTypeService constructor.
     *
     * @param PageTypeRepository $pageTypeRepository
     */
    public function __construct(PageTypeRepository $pageTypeRepository)
    {
        $this->pageTypeRepository = $pageTypeRepository;
    }

    /**
     * Get key value list for selecting a page type from a drop down list
     *
     * @return array
     */
    public function getClassListSelectValues()
    {
        $list = [];

        foreach ($this->pageTypeRepository->all() as $pageType) {
            $list[get_class($pageType)] = $pageType->name;
        }

        return $list;
    }

    public function getSlugListSelectValues()
    {
        $list = [];

        foreach ($this->pageTypeRepository->all() as $pageType) {
            $list[$pageType->slug] = $pageType->name;
        }

        return $list;
    }

    public function getClassList()
    {
        $list = [];

        foreach ($this->pageTypeRepository->all() as $pageType) {
            $list[] = get_class($pageType);
        }

        return $list;
    }
}