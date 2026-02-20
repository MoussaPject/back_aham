<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        return Promotion::where('active', true)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'reduction' => 'required|numeric',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date'
        ]);

        return Promotion::create($request->all());
    }
}
