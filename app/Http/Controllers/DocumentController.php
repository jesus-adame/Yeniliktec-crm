<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::orderByDesc('created_at')->with
        ([
            'contact', 
            'leads'])
            ->paginate(10);

        return inertia('Documents/Index', compact('documents'));
    }

    public function create()
    {
        return inertia('Documents/Create');
    }

    /**
     * Store a document
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'document' => 'required|max:10000|mimes:doc,docx,pdf,jpg,png',
        ]);

        $data = $request->only(['name']);

        $filePath = $request->file('document')->store('documents');

        Document::create([
            'name' => $data['name'],
            'path' => $filePath,
            'mime_type' =>$request->file('document')->getMimeType(),
        ]);

        return response(['message' => 'Archivo creado correctamente']);
    }

    /**
     * Destroy a document
     */
    public function destroy(Document $document)
    {
        Storage::delete($document->path);
        $document->delete();
        
        logs('local')->error('Eliminando el documento ' . $document->name . ' por ' . auth()->user()->email);

        return response(['message' => 'Archivo eliminado correctamente']);
    }

    /**
     * Download a document
     */
    public function download(Document $document)
    {
        return Storage::download($document->path, $document->name . '.' . File::extension($document->path));
    }
}
