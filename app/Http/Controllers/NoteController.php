<?php

namespace App\Http\Controllers;

use App\Domain\Note\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function show(Note $note)
    {
        return view('test', compact('note'));
    }
}
