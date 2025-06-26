<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Keyword;

class KeywordController extends Controller
{
    public function index()
    {
        $keywords = Keyword::orderBy('name')->get();
        return view('admin.keywords', compact('keywords'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:keyword,name',
        ]);
        Keyword::create(['name' => $request->name]);
        return redirect()->route('keywords.index')->with('success', 'Tag adicionada!');
    }

    public function destroy($id)
    {
        $keyword = Keyword::findOrFail($id);
        $keyword->delete();
        return redirect()->route('keywords.index')->with('success', 'Tag removida!');
    }
}
