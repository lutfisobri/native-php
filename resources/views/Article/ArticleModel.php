<?php
namespace views\Article;

class ArticleModel
{
    public static function create($data)
    {
        // create with increment id
        $data['id'] = self::count() + 1;
        
        $articles = self::all();
        $articles[] = $data;
        file_put_contents(__DIR__ . '/data.json', json_encode($articles));
    }

    public static function all()
    {
        $data = file_get_contents(__DIR__ . '/data.json');
        return json_decode($data, true);
    }

    public static function find($id)
    {
        $articles = self::all();
        foreach ($articles as $article) {
            if ($article['id'] == $id) {
                return $article;
            }
        }

        throw new \Exception('Article not found.');
    }

    public static function update($id, $data)
    {
        $articles = self::all();
        $articles[$id - 1] = $data;
        file_put_contents(__DIR__ . '/data.json', json_encode($articles));
    }

    public static function delete($id)
    {
        $articles = self::all();
        unset($articles[$id - 1]);
        file_put_contents(__DIR__ . '/data.json', json_encode($articles));
    }

    public static function count()
    {
        $articles = self::all();
        if ($articles == null) {
            return 0;
        }
        return count($articles);
    }
}