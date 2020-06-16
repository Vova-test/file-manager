<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\User;
use App\Models\Voice;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(File $file, User $user, Voice $voice)
    {
        $files = $file->all()
                      ->toArray();

        $id = Auth::user()->id;

        $fileArray = [];

        foreach ($files as $file) {
            $fileId = $file['id'];

            $users = $user->leftJoin('voices', function($join) use($fileId) 
                {
                    $join->on('users.id', '=', 'voices.user_id')
                         ->where('voices.file_id', '=', $fileId);
                })
                          ->select([
                              'users.id',
                              'users.name'
                          ])
                          ->selectRaw('IFNULL(voices.vote, 0) as status')
                          ->orderBy('users.name')
                          ->get();

            $status = $voice->where('user_id', $id)
                            ->where('file_id', $fileId)
                            ->value('vote');

            $fileArray[] = [
                'id' => $file['id'],
                'name' => $file['name'],
                'path' => $file['path'],
                'users' => $users,
                'status' => $status
            ];
        }
        
        return view('files', [
            'files' => $fileArray,
            'userId' => $id
        ]);
    }

    public function addVote(Voice $voice, Request $request)
    {
        $class = get_class($voice); 

        $newVoice = new $class();

        if ($newVoice->create($request->except(['_token']))) {
            $success = true;
        }

        return response()->json(['success' => $success]);
    }
}
