<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function getAllFilesInFolder($id)
    {
        return File::query()->where('folder_id', $id)->get();
    }

}
