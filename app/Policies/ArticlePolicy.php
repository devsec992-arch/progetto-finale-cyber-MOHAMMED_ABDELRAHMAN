<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Article;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //

    }
    public function update(User $user, Article $article)
    {
        // Solo l'autore dell'articolo può modificarlo
        return $user->id === $article->user_id;
    }
    public function delete(User $user, Article $article)
    {
        // Solo l'autore dell'articolo può eliminarlo
        return $user->id === $article->user_id;
    }
}
