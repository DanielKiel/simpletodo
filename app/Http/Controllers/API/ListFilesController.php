<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\ListFile;
use App\Lists;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ListFileValidation;

class ListFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $listId = $request->get('listId');

        return ListFile::where('lists_id', $listId)->get()->toArray();
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

        //needed list to get token to take this as directory
        $list = Lists::findOrFail($request->input('lists_id'));

        $file = $request->file('upload');

        $path =  Storage::putFile( 'local', $file);

        if ($file instanceof UploadedFile) {
            $name = $file->getClientOriginalName();
        }
        else {
            $name = $file->name;
        }

        $data = [
            'originalName' => $name
        ];

        $input = $request->all();
        array_forget($input, 'files');

        array_set($input, 'path', $path);
        array_set($input, 'data', $data);

        return ListFile::create($input)->fresh();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ListFile  $listFile
     * @return \Illuminate\Http\Response
     */
    public function show(ListFile $listFile, $thumb = 1)
    {
        if ( (bool) $thumb === false ) {
            return $listFile->getRaw();
        }
        else {
             return $listFile->getThumb();

        }

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
