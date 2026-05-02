<?php

namespace App\Http\Controllers;

class KatalogController extends Controller {
    public function index() {
      $spareparts = \App\Models\Sparepart::all();
        return view('fe.index', compact('spareparts'),
        [
            'sparepats' => $spareparts,
            'title' => 'kt',
        ]); // Sesuaikan dengan nama file view kamu
    }
}