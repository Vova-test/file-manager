<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FileRequest;
use App\Http\Requests\FolderRequest;
use App\Models\File;
use App\Models\User;
use App\Models\Voice;
use App\Models\Folder;
use Auth;

class HomeController extends Controller
{
    public function __construct(
        File $file, 
        User $user, 
        Voice $voice, 
        Folder $folder)
    {
        $this->file = $file;
        $this->user = $user;
        $this->voice = $voice;
        $this->folder = $folder;
    }

    public function index($parent = null)
    {
        $folders = $this->folder
                        ->where('folder_id', '=', $parent)
                        ->get()
                        ->toArray();

        $files = $this->file
                      ->where('folder_id', '=', $parent)
                      ->get()
                      ->toArray();

        $id = Auth::user()->id;

        $fileArray = [];

        foreach ($files as $file) {
            $fileId = $file['id'];

            $users = $this->user
                          ->leftJoin('voices', function($join) use($fileId) {
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

            $status = $this->voice
                           ->where('user_id', $id)
                           ->where('file_id', $fileId)
                           ->value('vote');

            $fileArray[] = [
                'id' => $file['id'],
                'name' => $file['name'],
                'users' => $users,
                'status' => $status
            ];
        }
        
        return view('files', [
            'folders' => $folders,
            'files' => $fileArray,
            'userId' => $id,
            'folderId' => $parent
        ]);
    }

    public function addVote(Request $request)
    {
        $class = get_class($this->voice); 

        $newVoice = new $class();

        if ($newVoice->create($request->except(['_token']))) {
            $success = true;
        }

        return response()->json(['success' => $success]);
    }

    public function uploadFile(FileRequest $request)
    {
        $fileName = $request->file('image')->getClientOriginalName();
        $request->file('image')->storeAs('files/', $fileName, 'public');

        $class = get_class($this->file); 

        $newFile = new $class();

        $newFile->create([
            'name' => $fileName,
            'folder_id' => $request->folderId
        ]);

        return redirect()->route('home');
    }

    public function addFolder(FolderRequest $request)
    {
        $class = get_class($this->folder); 

        $newFolder = new $class();

        $newFolder->create($request->except(['_token']));
        
        return redirect()->route('home');
    }
}
