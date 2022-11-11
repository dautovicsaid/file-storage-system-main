<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\SharedFile;
use App\Http\Requests\StoreSharedFileRequest;
use App\Http\Requests\UpdateSharedFileRequest;
use Illuminate\Http\Request;

class SharedFileController extends Controller
{

    public function getAllSharedFiles($folder_id = null, $previous_folder_id = null)
    {
        $sharedFiles = SharedFile::query()->where('user_id', auth()->id())->get();
        if ($folder_id == null) {
            $shared_files = File::query()
                ->join('shared_files', 'files.id', '=', 'shared_files.file_id')
                ->where('shared_files.user_id', '=', auth()->id())
                ->orderBy('extension')
                ->get();
        } else {
            // TODO implement authorization - allow access/show files authorized through shared parent folder (this is a workaround)
            $shared_files = File::query()
                ->where('files.folder_id', '=', $folder_id)
                ->orderBy('extension')
                ->get();
        }
        return view('sharedFiles.index', [
            'shared_files' => $shared_files,
            'folder_id' => $folder_id,
            'sharedFiles' => $sharedFiles
        ]);
    }
}
