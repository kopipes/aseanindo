<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\Blog;
use Illuminate\Support\Facades\Session;
use Spatie\LaravelMarkdown\MarkdownRenderer;
use Symfony\Component\Yaml\Yaml;

class BlogController extends Controller
{
    protected function getViewPath($viewName)
    {
        $locale = session('locale', 'id');
        return $locale === 'en' ? "en/landingpage/{$viewName}" : "landingpage/{$viewName}";
    }

    public function getArticleData($slug, $locale = 'id')
    {
        $filePath = $locale === 'en' ? public_path('blogs/articles-en.json') : public_path('blogs/articles.json');

        if (!File::exists($filePath)) {
            abort(404, "File not found");
        }
        $articles = json_decode(File::get($filePath), true);
        foreach ($articles as $article) {
            $articleSlug = basename($article['link']);
            if ($articleSlug === $slug) {
                return $article;
            }
        }

        return null;
    }

    public function getArticleDataById($id, $locale = 'id')
    {
        $filePath = $locale === 'en' ? public_path('blogs/articles-en.json') : public_path('blogs/articles.json');

        if (!File::exists($filePath)) {
            abort(404, "File not found");
        }

        $articles = json_decode(File::get($filePath), true);

        foreach ($articles as $article) {
            if ($article['id'] == $id) {
                return $article;
            }
        }

        return null;
    }


    public function showBlog($slug)
    {
        $locale = session('locale', 'id');
        $articleData = $this->getArticleData($slug, $locale);

        if (!$articleData) {
            abort(404, "Article not found for slug: {$slug}");
        }

        $otherLocale = $locale === 'en' ? 'id' : 'en';
        $otherArticleData = $this->getArticleDataById($articleData['id'], $otherLocale);
        $otherSlug = $otherArticleData ? basename($otherArticleData['link']) : $slug;

        $markdownContent = Blog::getMarkdownContent($slug);
        if (!$markdownContent) {
            abort(404, "Markdown content not found for slug: {$slug}");
        }

        $parts = preg_split('/[\r\n]+---[\r\n]+/', $markdownContent, 2);
        $meta = Yaml::parse(trim($parts[0]));
        $content = $parts[1] ?? '';

        $markdownRenderer = app(MarkdownRenderer::class);
        $content = $markdownRenderer->toHtml($content);
        $viewPath = $this->getViewPath('blog.show');
        return view($viewPath, compact('meta', 'content', 'articleData', 'otherSlug'));
    }

    public function setLang($locale, $slug = null)
    {
        Session::put('locale', $locale);

        if ($slug) {
            $blogController = app(BlogController::class);
            $targetArticleData = $blogController->getArticleData($slug, $locale);

            if ($targetArticleData) {
                $targetLink = $locale === 'en' ? "/en" . $targetArticleData['link'] : $targetArticleData['link'];
                return redirect($targetLink);
            }
        }

        return redirect()->back();
    }
}
