<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyFileRequest;
use App\Http\Requests\RenameFileRequest;
use App\Models\File;
use App\Http\Requests\StoreFilesRequest;
use App\Http\Requests\UpdateFilesRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    public function index()
    {
        $files = File::query()
            ->where('created_by', auth()->id())
            ->whereNull('folder_id')
            ->get();
        return view('files.index', [
            'files' => $files
        ]);
    }

    public function downloadFile(File $file)
    {
        return Storage::download($file->path, $file->name . "." . $file->extension);
    }

    public function store(StoreFilesRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $user_id = auth()->id();

        $uploaded_file = $request->file('file');

        $extension = $uploaded_file->extension();
        $size = $uploaded_file->getSize();
        $new_storage_used = $user->storage_used + $size;
        $folder_id = $request->folder_id;

        $fileExists = File::query()->where('folder_id', '=', $request->folder_id)
            ->where('name', '=', $request->name)
            ->where('extension', '=', $extension)
            ->where('created_by', '=', $user_id)->count();

        if ($fileExists > 0) {
            return redirect()->back()->withErrors(['file_name_error' => 'File upload failed - file with same name already exists.']);
        }

        if ($new_storage_used > $user->storage_limit) {
            return redirect()->back()->withErrors(['upload_error' => 'File upload failed - storage limit exceeded.']);
        }

        $path = Storage::put("files/$user_id", $uploaded_file);

        $file = File::create([
            'name' => $request['name'],
            'extension' => $extension,
            'path' => $path,
            'size' => $size,
            'created_by' => $user_id,
            'modified_by' => $user_id,
            'folder_id' => $folder_id
        ]);

        $user->storage_used += $size;
        $user->update();

        return redirect()->back();
    }

    public function renameFile(RenameFileRequest $request)
    {
        $user_id = auth()->id();

        $file = File::find($request->file_id);
        if ($file->created_by != $user_id) {
            return redirect()->back();
        }

        $fileExists = File::query()->where('folder_id', '=', $file->folder_id)
            ->where('name', '=', $request->name)
            ->where('extension', '=', $file->extension)
            ->where('created_by', '=', $user_id)->count();

        if ($fileExists > 0) {
            return redirect()->back()->withErrors(['file_rename_error' => 'File rename  - file with same name already exists.']);
        }

        return redirect()->back();
    }

    public function destroy(DestroyFileRequest $request)
    {

        $auth_user = auth()->user();

        $file = File::query()->find($request->file_id);
        if ($file->created_by != $auth_user->id) {
            return redirect()->back();
        }

        if ($file->size != null) {
            $auth_user->storage_used -= $file->size;
            $auth_user->update();
            Storage::delete($file->path);
        }
        $file->delete();
        // TODO delete files contained in folder, getting them with recursive query
        return redirect()->back();
    }


}
