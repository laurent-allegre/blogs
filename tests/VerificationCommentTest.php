<?php

namespace App\Tests;

use App\Entity\Comment;
use App\Service\VerificationComment;
use PHPUnit\Framework\TestCase;

class VerificationCommentTest extends TestCase
{
    public function testContientMotInterdit()
    {
        $service = new VerificationComment();

        $comment = new Comment();
        $comment->setContenu("ceci est le commentaire avec mauvais");

        $result = $service->commentaireNonAutorise($comment);

        $this->assertTrue($result);
    }

    public function testNeContientPasMotInterdit()
    {
        $service = new VerificationComment();

        $comment = new Comment();
        $comment->setContenu("ceci est le commentaire avec rien d'interdit ");

        $result = $service->commentaireNonAutorise($comment);

        $this->assertFalse($result);
    }


}
