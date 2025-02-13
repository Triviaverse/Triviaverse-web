<?php

namespace App\Http\Controllers;
use App\Models\Kerdes;
use Illuminate\Http\Request;

class KerdesController extends Controller {
    public function index() {
        return Kerdes::with('valaszok')->get();
    }

    public function show($id) {
        return Kerdes::with('valaszok')->findOrFail($id);
    }
}
