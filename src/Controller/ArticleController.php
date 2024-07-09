<?php

namespace App\Controller;

use App\Model\Article;
use App\Model\Comment;
use Twig\Environment;

class ArticleController
{
    private Article $articleModel;
    private Comment $commentModel;
    private Environment $twig;

    public function __construct(Article $articleModel, Comment $commentModel, Environment $twig)
    {
        $this->articleModel = $articleModel;
        $this->commentModel = $commentModel;
        $this->twig = $twig;
    }

    public function list(): array
    {
        $articles = $this->articleModel->getAll();
        return [
              'template' => 'article_list.twig',
              'data' => ['articles' => $articles]
        ];
    }

    public function show($id): array
    {
        $article = $this->articleModel->getById($id);
        if (!$article) {
            return [
                  'template' => '404.twig',
                  'data' => []
            ];
        }
        $comments = $this->commentModel->getByArticleId($id);
        return [
              'template' => 'article.twig',
              'data' => ['article' => $article, 'comments' => $comments]
        ];
    }

    public function like($id): void
    {
        $this->articleModel->like($id);
        header('Location: /articles/' . $id);
    }

    public function createForm(): array
    {
        return [
              'template' => 'create_article.twig',
              'data' => []
        ];
    }

    public function create(): void
    {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $this->articleModel->create($title, $content);
        header('Location: /articles');
    }

    public function edit($id): array
    {
        $article = $this->articleModel->getById($id);
        if (!$article) {
            return [
                  'template' => '404.twig',
                  'data' => []
            ];
        }
        return [
              'template' => 'edit_article.twig',
              'data' => ['article' => $article]
        ];
    }

    public function update($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $this->articleModel->update($id, $title, $content);
            header('Location: /articles/' . $id);
            exit;
        }
    }

    public function delete($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->articleModel->delete($id);
            header('Location: /articles');
            exit;
        }
    }
}
