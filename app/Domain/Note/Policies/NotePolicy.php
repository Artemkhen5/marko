<?php

namespace App\Domain\Note\Policies;


use App\Domain\Note\Note;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function modify(User $user, Note $note)
    {
        return $user->id === $note->user_id ? Response::allow() : Response::deny('You do not have permission to modify this note.');
    }
}
