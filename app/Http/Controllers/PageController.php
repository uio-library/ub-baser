<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class PageController extends Controller
{
    protected $logGroup = 'pages';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->view('pages.index', [
            'pages' => Page::orderBy('slug')->get(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Page $page
     * @return Response
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
     * @param Page $page
     * @return Response
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
     * Show the form for editing the specified resource.
     *
     * @param Page $page
     * @return JsonResponse
     */
    public function lock(Page $page)
    {
        $this->authorize($page->permission);

        $lock = $page->locks()->first();

        if ($lock) {
            $user = ($lock->user->id = auth()->id()) ? 'deg (i et annet vindu)' : $lock->user->name;
            return response()->json([
                'error' => 'locked',
                'user' => $user,
            ], 423);
        }

        $lock = $page->locks()->create([
            'user_id' => auth()->user()->id,
            'lockable_id' => $page->id,
            'lockable_type' => Page::class,
        ]);

        $this->log('Locked page ' . $page->id);

        return response()->json([
            'status' => 'ok',
            'lock_id' => $lock->id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param Page $page
     * @return Response
     * @throws AuthorizationException
     */
    public function unlock(Request $request, Page $page)
    {
        $this->authorize($page->permission);

        $lock = $page->locks()->where('id', '=', $request->get('lock'))->first();
        $lock->delete();
        $this->log('Unlocked page ' . $page->id);

        return response('ok');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Page $page
     * @return JsonResponse
     */
    public function update(Request $request, Page $page)
    {
        $this->authorize($page->permission);

        if ($page->exists) {
            $lock = $page->locks()->where('id', '=', $request->get('lock'))->first();
            if (is_null($lock)) {
                return response()->json([
                    'error' => 'Invalid lock',
                ], 423);
            }
            $lock->delete();
        }

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

        return response()->json([
            'status' => 'ok',
        ]);
    }

    /**
     * Generate and store a thumbnail.
     *
     * @param FilesystemManager $fm
     * @param ImageManager $im
     * @param File|UploadedFile  $file
     * @param int $maxWidth
     * @param int $maxHeight
     * @param string $filename
     */
    private function storeThumb(FilesystemManager $fm, ImageManager $im, $file, $maxWidth, $maxHeight, $filename)
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
}
