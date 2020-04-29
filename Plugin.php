<?php namespace Vdomah\GoodNewsTags;

use Vdomah\GoodNewsTags\Components\TagList;
use Event;
use Lovata\GoodNews\Controllers\Articles;
use Lovata\GoodNews\Models\Article;
use System\Classes\PluginBase;
use Vdomah\GoodNewsTags\Classes\Handlers\AticlesControllerHandler;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            TagList::class => 'TagList',
        ];
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        Event::subscribe(AticlesControllerHandler::class);

        Article::extend(function($model) {
            $model->belongsToMany['tags'] = [
                'Vdomah\GoodNewsTags\Models\Tag',
                'table' => 'vdomah_goodnewstags_article_tag',
            ];
        });
        Articles::extendFormFields(function($form, $model, $context) {

            if (!$model instanceof Article)
                return;

            $form->addTabfields([
                'tags' => [
                    'label'     => 'vdomah.goodnewstags::lang.tag.label',
                    'tab'       => 'lovata.toolbox::lang.tab.settings',
                    'type'      => 'taglist',
                    'mode'      => 'relation',
                    'span'      => 'left',
                    'path'      => '$/vdomah/goodnewstags/views/_tags.htm'
                ],
            ]);
        });
    }
}
