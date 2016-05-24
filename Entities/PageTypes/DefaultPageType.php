<?php namespace Modules\Page\Entities\PageTypes;

use Modules\Page\Entities\PageType;

class DefaultPageType extends PageType
{
    protected static $slug = 'default';
    protected static $name = 'Default';
    protected static $pluralName = 'Default';

    protected static $validationRules = [];
}
