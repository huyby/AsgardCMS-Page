<?php namespace Modules\Page\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Filesystem\Filesystem;
use Modules\Core\Foundation\Theme\ThemeManager;
use Modules\Page\Entities\PageType;

class TemplateViewComposer
{
    /**
     * @var ThemeManager
     */
    private $themeManager;
    /**
     * @var Filesystem
     */
    private $finder;
    /**
     * @var PageType
     */
    protected $pageType;

    public function __construct(ThemeManager $themeManager, Filesystem $finder)
    {
        $this->themeManager = $themeManager;
        $this->finder = $finder;
        $this->pageType = request()->page_type ?: request()->page->type;
    }

    public function compose(View $view)
    {
        $view->with('all_templates', $this->getTemplates());
    }

    /**
     * Get list of templates available in theme
     * @return array
     */
    private function getTemplates()
    {
        $path = $this->getCurrentThemeBasePath();

        $templates = [];

        foreach ($this->finder->allFiles($path . '/views') as $template) {
            $relativePath = $template->getRelativePath();

            if ($this->isLayoutOrPartial($relativePath)) {
                continue;
            }
            $templateName = $this->getTemplateName($template);

            if (!$templateName) {
                continue;
            }
            $templatePageType = $this->getTemplateType($template);

            if ($templatePageType && $templatePageType !== (string) $this->pageType) {
                continue;
            }
            $file = $this->removeExtensionsFromFilename($template);

            if ($this->hasSubdirectory($relativePath)) {
                $templates[str_replace('/', '.', $relativePath) . '.' . $file] = $templateName;
            } else {
                $templates[$file] = $templateName;
            }
        }

        return $templates;
    }

    /**
     * Get the base path of the current theme.
     *
     * @return string
     */
    private function getCurrentThemeBasePath()
    {
        return $this->themeManager->find(setting('core::template'))->getPath();
    }

    /**
     * Read template name defined in comments.
     *
     * @param $template
     * @return null|string
     */
    private function getTemplateName($template)
    {
        return $this->getTemplateMeta($template, 'Page Template');
    }

    /**
     * Read template page type defined in comments.
     *
     * @param $template
     * @return null|string
     */
    private function getTemplateType($template)
    {
        return $this->getTemplateMeta($template, 'Page Type');
    }

    /**
     * Read template meta defined in comments.
     *
     * @param $template
     * @param $metaName
     * @return null|string
     */
    private function getTemplateMeta($template, $metaName)
    {
        preg_match(
            sprintf('/{{--\s*%s:\s*\b(.*)\b\s*--}}/', preg_quote($metaName)),
            $template->getContents(),
            $matches
        );

        if (count($matches) > 1) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Check if the given path is a layout or a partial.
     *
     * @param string $relativePath
     *
     * @return bool
     */
    private function isLayoutOrPartial($relativePath)
    {
        return preg_match("#(layouts|partials)#i", $relativePath) === 1;
    }

    /**
     * Remove the extension from the filename.
     *
     * @param $template
     *
     * @return mixed
     */
    private function removeExtensionsFromFilename($template)
    {
        return str_replace('.blade.php', '', $template->getFilename());
    }

    /**
     * Check if the relative path is not empty (meaning the template is in a directory).
     *
     * @param $relativePath
     *
     * @return bool
     */
    private function hasSubdirectory($relativePath)
    {
        return ! empty($relativePath);
    }
}
