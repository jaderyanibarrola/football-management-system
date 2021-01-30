<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadFileController extends Controller
{
    public function index()
    {
    	$filePath = public_path("template.csv");
    	$headers = ['Content-Type: text/csv'];
    	$fileName = 'template.csv';

    	return response()->download($filePath, $fileName, $headers);
    }
}