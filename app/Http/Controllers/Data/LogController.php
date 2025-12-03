<?php

namespace App\Http\Controllers\Data;

use App\Helpers\Date;
use App\Helpers\Helper;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class LogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $id_user = $request->id_user;
            $start = $request->start;
            $end = $request->end;
            $data = LogAktivitas::when($id_user != 'ALL', function ($query) use ($id_user) {
                $query->where('id_user', $id_user);
            })->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('tanggal', [$start, $end]);
            })->orderByDesc('created_at')->get();

            return DataTables::of($data)
                ->editColumn("tanggal", function ($row) {
                    return Date::format($row->created_at, 78);
                })
                ->editColumn("status", function ($row) {
                    return Helper::statusLogBadge($row->status);
                })
                ->rawColumns(['status'])
                ->addIndexColumn()
                ->make(true);
        }
        $breadcrumb = [
            ['title' => 'Log Aktivitas', 'url' => null]
        ];
        $data['user'] = User::withTrashed()->get();
        return view('log.index', compact('breadcrumb', 'data'));
    }
}
