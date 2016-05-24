<?php namespace Modules\Page\Entities;

abstract class PageType
{
    protected static $attributes = [
        'slug',
        'name'
    ];
    protected static $validationRules = [];
    protected static $transValidationRules = [];
    protected static $slug;
    protected static $name;
    protected static $pluralName;

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);

        if (method_exists($this, $method)) {
            return $this->$method();
        }
        if (isset(static::$$name)) {
            return static::$$name;
        }

        return $this->$name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return static::class;
    }
}
