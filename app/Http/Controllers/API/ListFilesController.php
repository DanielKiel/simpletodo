<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\ListFile;
use App\Lists;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ListFileValidation;

class ListFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ListFileValidation $request
     * @return \Illuminate\Http\Response
     */
    public function store(ListFileValidation $request)
    {
        $this->authorize('create', ListFile::class);

        $files = $request->files;

        //needed list to get token to take this as directory
        $list = Lists::findOrFail($request->input('lists_id'));

        if (empty($files)) {
            return response('no files uploaded', 422)->json(['errors' => [
                'files' => [
                    'no files uploaded'
                ]
            ]]);
        }

        $uploaded = [];

        foreach ($files as $file) {
            $path =  Storage::putFile('list_files', $file);
            $data = [];

            if ($file instanceof UploadedFile) {
                $name = $file->getClientOriginalName();
            }
            else {
               $name = $file->name;
            }

            $data = [
                'originalName' => $name
            ];

            array_push($uploaded,[
                'path' => $path,
                'data' => $data
            ]);
        }

        $input = $request->all();
        array_forget($input, 'files');

        $results = collect();

        foreach ($uploaded as $upload) {
            array_set($input, 'path', array_get($upload, 'path'));
            array_set($input, 'data', array_get($upload, 'data', []));

            $results->push(ListFile::create($input));
        }

        return $results;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ListFile  $listFile
     * @return \Illuminate\Http\Response
     */
    public function show(ListFile $listFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ListFile  $listFile
     * @return \Illuminate\Http\Response
     */
    public function edit(ListFile $listFile)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ListFileValidation  $request
     * @param  \App\ListFile  $listFile
     * @return \Illuminate\Http\Response
     */
    public function update(ListFileValidation $request, ListFile $listFile)
    {
        $this->authorize('update', $listFile);

        $listFile->update($request->input());

        return $listFile->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ListFile  $listFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(ListFile $listFile)
    {
        $this->authorize('delete', $listFile);
    }
}
