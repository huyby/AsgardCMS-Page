<?php namespace Modules\Page\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class UpdatePageRequest extends BaseFormRequest
{
    protected $translationsAttributesKey = 'page::pages.validation.attributes';

    public function rules()
    {
        return $this->mergePageTypeRules([
            'template' => 'required',
        ]);
    }

    public function translationRules()
    {
        return $this->mergePageTypeTranslationRules([
            'title' => 'required',
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'template.required' => trans('page::messages.template is required'),
            'is_home.unique' => trans('page::messages.only one homepage allowed'),
        ];
    }

    public function translationMessages()
    {
        return [
            'title.required' => trans('page::messages.title is required'),
            'body.required' => trans('page::messages.body is required'),
        ];
    }

    /**
     * Extended set of rules to be merged with default rules for a page type form request.
     *
     * @param array $rules
     * @return array
     */
    public function mergePageTypeTranslationRules(array $rules = [])
    {
        $pageType = request()->page->type;

        return $pageType ? array_merge($rules, $pageType->transValidationRules) : $rules;
    }

    /**
     * Extended set of rules to be merged with default rules for a page type form request.
     *
     * @param array $rules
     * @return array
     */
    protected function mergePageTypeRules(array $rules = [])
    {
        $pageType = request()->page->type;

        return $pageType ? array_merge($rules, $pageType->validationRules) : $rules;
    }
}
