<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeywordController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q', '');
        $keywords = DB::table('keyword')
            ->when($q, function($query) use ($q) {
                $query->where('name', 'like', "%$q%") ;
            })
            ->orderBy('name')
            ->limit(15)
            ->pluck('name');
        // Tagify espera array de objetos {value: 'palavra'}
        return response()->json($keywords->map(function($k){return ['value'=>$k];}));
    }
}
