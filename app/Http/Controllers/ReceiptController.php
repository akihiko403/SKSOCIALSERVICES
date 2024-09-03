<?php
namespace App\Http\Controllers;

use App\Models\Fuellog;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function showFullscreen($id)
    {
        $receipt = Fuellog::findOrFail($id);
        return view('fullscreen', compact('receipt'));
    }
}

