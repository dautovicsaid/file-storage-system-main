<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\SharedFileEmail;
use App\Models\File;
use App\Models\SharedFile;
use App\Models\User;
use App\Notifications\NewSharedFileNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FileController extends Controller
{
    public function shareFile(Request $request, $id)
    {
        $user = User::find($request->userId);

        $shared_file = SharedFile::create([
            "file_id" => $id,
            "user_id" => $user->id,
            "created_by" => auth()->id()
        ]);

        $user->notify(new NewSharedFileNotification($shared_file));

        return response(['data' => $shared_file], Response::HTTP_OK);
    }

    public function searchShareableUsers(Request $request, $id)
    {
        $file = File::find($id);

        $search_term = $request->searchTerm;
        return User::query()
            ->where('name', 'like', '%' . $search_term . '%')
            ->whereNot('id', $file->created_by)
            ->whereNotExists(function ($query) use ($id) {
                $query->select(DB::raw(1))
                    ->from('shared_files', 'sf')
                    ->where('sf.file_id', $id)
                    ->where('sf.user_id', DB::raw('users.id'));
            })
            ->select('id', DB::raw("concat(name, ' (', email, ')') as text"))
            ->get();
    }

    public function getSharedWithUsers($file_id)
    {
        $users = User::query()
            ->join('shared_files', 'users.id', 'shared_files.user_id')
            ->where('shared_files.file_id', '=', $file_id)
            ->where('shared_files.created_by', '=', auth()->id())
            ->get();

        return $users;
    }

    public function destroy($file_id, $user_id)
    {

        $shared_file = SharedFile::query()->where('user_id', '=', $user_id)
            ->where('file_id', '=', $file_id)
            ->where('created_by', '=', auth()->id())
            ->first();

        if ($shared_file == null) {
            return response([], Response::HTTP_UNAUTHORIZED);
        }

        $shared_file->delete();

        return response(['data' => $shared_file], Response::HTTP_OK);;
    }
}
