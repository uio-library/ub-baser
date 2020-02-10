<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class PageController extends Controller
{
    protected $logGroup = 'pages';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('pages.index', [
            'pages' => Page::orderBy('slug')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Page $page
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        if (!$page->exists) {
            return abort(404);
        }
        return response()->view('pages.show', [
            'page' => $page,
            'base' => $page->base,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Page $page
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        $this->authorize($page->permission);

        return response()->view('pages.edit', [
            'page' => $page,
            'base' => $page->base,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Page                $page
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $this->authorize($page->permission);

        // Workaround for CKEditor bug,
        $body = preg_replace('/ style="margin-left: 0cm;"/', '', $request->body);

        $page->body = $body;
        $page->updated_by = $request->user()->id;
        $page->save();

        $this->log(
            'Oppdaterte <a href="%s">%s</a>.',
            action('PageController@show', ['page' => $page->slug]),
            $page->slug
        );

        return redirect()->action('PageController@show', ['page' => $page->slug])
            ->with('status', 'Siden ble lagret.');
    }

    protected function storeThumb(FilesystemManager $fm, ImageManager $im, $file, $maxWidth, $maxHeight, $filename)
    {
        $blob = $im->make($file->path())
            ->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode(null, 85);

        $fm->disk('public')->put($filename, $blob);
    }

    /**
     * Store a new image uploaded from CKEditor.
     *
     * @param Request $request
     * @param FilesystemManager $fm
     * @return JsonResponse
     */
    public function uploadImage(Request $request, FilesystemManager $fm, ImageManager $im)
    {
        $user = \Auth::user();
        if (!count($user->rights)) {
            return response()->json(['Error' => 'Permission denied, no write access to any base.'], 401);
        }

        $file = $request->file('upload');
        $basename = Str::random(40);
        $ext = '.' . $file->extension();
        $filename = "{$basename}{$ext}";

        $publicPath = 'uploads/';

        if ($ext === '.gif') {
            // Don't generate thumbs
            $fm->disk('public')->putFileAs('.', $file, $filename);

            $url = asset($publicPath . $filename);
            $this->log("Lastet opp bilde: <a href='$url'>" . basename($url) . '</a>');

            return response()->json([
                'url' =>  $url,
            ]);
        }

        $thumbSizes = ['1200', '600', '300'];

        $this->storeThumb($fm, $im, $file, 1920, null, $filename);
        $urls = [
            'default' => asset($publicPath . $filename),
        ];
        foreach ($thumbSizes as $width) {
            $thumb_filename = $basename . '_' . $width . $ext;
            $this->storeThumb($fm, $im, $file, $width, null, "{$basename}_{$width}{$ext}");
            $urls[$width] = asset("{$publicPath}{$basename}_{$width}{$ext}");
        }

        $url = $urls['default'];
        $this->log("Lastet opp bilde: <a href='$url'>" . basename($url) . '</a>');

        return response()->json([
            'urls' => $urls,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
