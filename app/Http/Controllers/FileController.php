<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use PHPUnit\TextUI\XmlConfiguration\Logging\Logging;
use SebastianBergmann\Environment\Console;

class FileController extends Controller
{

    function index(Request $request) {
        $path = $request->file('file')->store('docs');

        $file = new File();
        $file->name = 'Archivo 1';
        $file->file_name = 'nombre';
        $file->user_id = $request->user()->id;
        $file->file_path = $path;
        $file->save();

        return View('dashboard');
    }

    /**
     * Guarda un archivo en el almacenamiento y lo agrega al registro.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $path = $request->file('file')->store('docs');

        $files = $request->file('file');
        $filename = $files->getClientOriginalName();

        // $path = $request->file('file')->move(public_path('docos'));
        // $path = $files->storeAs('public', $filename);
        $path = $request->file('file')->store('docs');

        $file = new File();
        $file->name = $request->filename;
        $file->file_name = $filename;
        $file->user_id = $request->user()->id;
        $file->file_path = $path;
        $file->save();

        $id = auth()->id();
        $dataset = User::find($id)->getFiles;
        return View('dashboard')->with('dataset', $dataset);
        //return Storage::download($path, $filename);
    }

    /**
     * Guarda un archivo en el almacenamiento y lo agrega al registro.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function show($id) {
        $fileInstance = File::find($id);
        return Storage::download($fileInstance->file_path, $fileInstance->file_name);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $file = File::find($id);
        $file->name = $request->get('new-filename');
        $file->save();
        return redirect('/dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = File::find($id);
        $file->delete();
        return redirect('/dashboard');
    }
}
