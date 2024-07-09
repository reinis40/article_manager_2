<?php

namespace App\Controller;

use App\Model\Comment;
use Twig\Environment;

class CommentController
{
    private Comment $commentModel;
    private Environment $twig;

    public function __construct(Comment $commentModel, Environment $twig)
    {
        $this->commentModel = $commentModel;
        $this->twig = $twig;
    }

    public function addComment($articleId): void
    {
        $comment = $_POST['comment'];
        $name = $_POST['name'];
        $this->commentModel->create($articleId, $name, $comment);
        header('Location: /articles/' . $articleId);
    }

    public function likeComment($id): void
    {
        $this->commentModel->like($id);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
