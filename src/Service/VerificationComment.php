<?php

namespace App\Service;

use App\Entity\Comment;
use phpDocumentor\Reflection\Types\True_;

class VerificationComment
{
    public function commentaireNonAutorise(Comment $comment){
        $nonAutorise = [
            "mauvais",
            "pourri",
            "merde",
        ];

        foreach ($nonAutorise as $word){
            if (strpos($comment->getContenu(), $word)) {
                return true;
            }
        }
        return false;
    }
}