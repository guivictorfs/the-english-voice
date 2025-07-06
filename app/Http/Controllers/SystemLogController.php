<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use PDF;
use App\Models\SystemLog;
use App\Models\ArticleHistory;
use App\Models\Comment;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;

class SystemLogController extends Controller
{


    public function export(Request $request, $format)
    {
        // Validar formato
        if ($format !== 'pdf') {
            abort(400, 'Formato inválido. Use pdf.');
        }

        // Buscar logs do sistema
        $logs = SystemLog::with(['user'])
            ->when($request->start_date, function ($query) use ($request) {
                $query->where('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->where('created_at', '<=', $request->end_date);
            })
            ->when($request->action, function ($query) use ($request) {
                $query->where('action', 'like', '%' . $request->action . '%');
            })
            ->when($request->user, function ($query) use ($request) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->user . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Buscar histórico de artigos
        $articleHistory = ArticleHistory::with(['user'])
            ->when($request->start_date, function ($query) use ($request) {
                $query->where('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->where('created_at', '<=', $request->end_date);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Buscar histórico de comentários
        $comments = Comment::with(['user'])
            ->where('updated_at', '!=', 'created_at')
            ->when($request->start_date, function ($query) use ($request) {
                $query->where('updated_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->where('updated_at', '<=', $request->end_date);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        // Gerar PDF
        $pdf = PDF::loadView('admin.exports.system_logs', [
            'logs' => $logs,
            'articleHistory' => $articleHistory,
            'comments' => $comments
        ]);
        return $pdf->download('logs-sistema.pdf');

        // Buscar logs do sistema
        $logs = SystemLog::with(['user'])
            ->when($request->start_date, function ($query) use ($request) {
                $query->where('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->where('created_at', '<=', $request->end_date);
            })
            ->when($request->action, function ($query) use ($request) {
                $query->where('action', 'like', '%' . $request->action . '%');
            })
            ->when($request->user, function ($query) use ($request) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->user . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Buscar histórico de artigos
        $articleHistory = ArticleHistory::with(['user'])
            ->when($request->start_date, function ($query) use ($request) {
                $query->where('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->where('created_at', '<=', $request->end_date);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Buscar histórico de comentários
        $comments = Comment::with(['user'])
            ->where('updated_at', '!=', 'created_at')
            ->when($request->start_date, function ($query) use ($request) {
                $query->where('updated_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->where('updated_at', '<=', $request->end_date);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        if ($format === 'excel') {
            return Excel::download(new \App\Exports\SystemLogExport($logs, $articleHistory, $comments), 'logs-sistema.xlsx');
        } else {
            // Gerar PDF
            $pdf = PDF::loadView('admin.exports.system_logs', [
                'logs' => $logs,
                'articleHistory' => $articleHistory,
                'comments' => $comments
            ]);
            return $pdf->download('logs-sistema.pdf');
        }
    }
}
