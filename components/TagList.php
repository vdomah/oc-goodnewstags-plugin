<?php namespace Vdomah\GoodNewsTags\Components;

use Vdomah\GoodNewsTags\Models\Tag;
use Lovata\Toolbox\Classes\Component\SortingElementList;

class TagList extends SortingElementList
{

    public function componentDetails()
    {
        return [
            'name'        => 'Список тэгов',
            'description' => 'Список тэгов'
        ];
    }

    public function make()
    {
        return Tag::has('articles')->orderBy('name')->get();
    }

    public function onAjaxRequest()
    {
        return true;
    }

    public function handle()
    {
        if (get('tags')) {
            if ($tag = Tag::whereName(get('tags'))->first()) {
                $this['article_ids_by_tag'] = $tag->articles->lists('id');
            } else {
                $this['article_ids_by_tag'] = [];
            }
        } elseif (get('tags') === '') {
            $url = '/blog';
            if (get('tags')) {
                $url .= '?search=' . get('search');
            }
            return redirect($url);
        }
    }
}