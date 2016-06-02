<?php

namespace Modules\Page\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Page\Repositories\PageTypeRepository;
use Modules\Search\Entities\Searchable;

class Page extends Model
{
    
    use Translatable;
    use Searchable;
    
    protected $table = 'page__pages';
    
    protected $translatedAttributes = [
        'page_id',
        'title',
        'slug',
        'status',
        'body',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
    ];
    
    protected $fillable = [
        'is_home',
        'template',
        'type',
        // Translatable fields
        'page_id',
        'title',
        'slug',
        'status',
        'body',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
    ];
    
    protected $casts = [
        'is_home' => 'boolean',
    ];
    
    /**
     * All searchable fields, translatable or not, as column => priority pairs. Priority indicates
     * a higher search score when results are found in such fields.
     *
     * @var array
     */
    public $searchable = [
        'title' => 10,
        'body' => 8,
        'slug' => 4,
    ];
    
    /**
     * Get the page type, when value contains a string cast to PageType instance
     *
     * @return \Modules\Page\Entities\PageType
     */
    public function getTypeAttribute()
    {
        $type = $this->getAttributeFromArray('type');
        
        if (is_string($type)) {
            $type = empty($type)
                ? app(PageTypeRepository::class)->getDefaultType()
                : app(PageTypeRepository::class)->findByClass($type);
            $this->setAttribute('type', $type);
        }
        
        return $type;
    }
    
    /**
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        #i: Convert array to dot notation
        $config = implode('.', ['asgard.page.config.relations', $method]);
        
        #i: Relation method resolver
        if (config()->has($config)) {
            $function = config()->get($config);
            
            return $function($this);
        }
        
        #i: No relation found, return the call to parent (Eloquent) to handle it.
        return parent::__call($method, $parameters);
    }
    
}
