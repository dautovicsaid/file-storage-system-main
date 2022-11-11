<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_id = auth()->id();

        $files_by_extension = File::query()
            ->select('extension', DB::raw('count(*) as count'))
            ->where('created_by', $user_id)
            ->whereNotNull('files.extension')
            ->groupBy('extension')
            ->get();

        $recently_shared_files = File::query()
            ->join('shared_files', 'files.id', '=', 'shared_files.file_id')
            ->join('users', 'users.id', '=', 'files.created_by')
            ->where('shared_files.user_id', '=', $user_id)
            ->orderBy('shared_files.created_at', 'desc')
            ->select('files.id', 'files.name', 'files.extension', 'users.name as shared_by', 'shared_files.created_at as shared_at')
            ->limit(3)
            ->get();

        return view('home.index', [
            'files_by_extension' => $files_by_extension,
            'recently_shared_files' => $recently_shared_files
        ]);
    }
}
