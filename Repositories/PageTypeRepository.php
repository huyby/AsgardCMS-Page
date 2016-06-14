<?php namespace Modules\Page\Repositories;

use Illuminate\Support\Collection;
use Modules\Page\Entities\PageType;
use Modules\Page\Entities\PageTypes\DefaultPageType;

class PageTypeRepository
{
    /**
     * The collection of page type class names
     *
     * @var Collection
     */
    protected $types;

    /**
     * The default page type class name
     *
     * @var DefaultPageType
     */
    protected static $defaultPageType = DefaultPageType::class;

    public function __construct($types = [])
    {
        $this->setTypes($types);
    }

    /**
     * Get default page type instance
     * @return PageType
     */
    public function getDefaultType()
    {
        return new self::$defaultPageType;
    }

    /**
     * Set page types from array
     *
     * @param array $types
     */
    public function setTypes($types)
    {
        if (!in_array(self::$defaultPageType, $types)) {
            array_unshift($types, self::$defaultPageType);
        }
        $this->types = new Collection();

        foreach ($types as $type) {
            $this->types[$type] = new $type;
        }
    }

    /**
     * Get list of all page type class names
     *
     * @return array
     */
    public function all()
    {
        return $this->types->all();
    }

    /**
     * Get list of all visible page type class name
     *
     * @todo should use permissions
     * @return array
     */
    public function allVisible()
    {
        return $this->types->filter(function ($item) {
            return $item->hide !== true;
        });
    }

    /**
     * Find a page type by slug
     *
     * @param string $slug
     * @return null|PageType
     */
    public function findBySlug($slug)
    {
        return $this->types->first(function ($key, PageType $value) use ($slug) {
            return $value->slug === $slug;
        });
    }

    /**
     * Find a page type by slug
     *
     * @param string $class
     * @return null|PageType
     */
    public function findByClass($class)
    {
        return $this->types[$class] ?? null;
    }
}
