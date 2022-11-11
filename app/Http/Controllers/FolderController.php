<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFolderRequest;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{

    public function getAllFilesFromFolder(File $folder)
    {
        $files = File::where('folder_id', $folder->id)->where('created_by', auth()->id())->
        orderBy('extension')->get();
        return view('folders.index', [
            'folder' => $folder,
            'files' => $files
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(StoreFolderRequest $request)
    {

        $user_id = auth()->id();

        $folderExists = File::query()->where('folder_id', '=', $request->folder_id)
            ->where('name', '=', $request->name)
            ->where('extension', '=', null)
            ->where('created_by', '=', $user_id)
            ->count();

        if ($folderExists > 0) {
            return redirect()->back()->withErrors(['folder_name_error' => 'Folder upload failed - folder with same name already exists.']);
        }

        if (!Storage::exists($user_id))
            Storage::makeDirectory($user_id);

        $folder_id = $request->folder_id;
        $folder = File::create([
            'name' => $request['name'],
            'created_by' => $user_id,
            'modified_by' => $user_id,
            'folder_id' => $folder_id
        ]);

        return redirect()->back();
    }

}
