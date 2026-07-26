<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class VideoController extends Controller
{
    /**
     * Stream a lecture video from the PRIVATE disk.
     * Reached only via a short-TTL signed URL (route middleware 'signed') by a
     * subscribed user. On LiteSpeed (Hostinger) an internal redirect keeps PHP
     * out of the byte path; elsewhere we stream the file directly.
     */
    public function stream(Request $request, Lecture $lecture): Response
    {
        abort_unless($lecture->is_published, 404);
        abort_unless(optional($request->user())->isSubscribed(), 403);
        abort_if(blank($lecture->video_path), 404);

        $disk = Storage::disk('videos');
        abort_unless($disk->exists($lecture->video_path), 404);

        // LiteSpeed / Apache mod_xsendfile internal redirect (fast path, no PHP streaming).
        if (str_contains((string) $request->server('SERVER_SOFTWARE'), 'LiteSpeed')) {
            return response('', 200, [
                'X-LiteSpeed-Location' => '/'.ltrim(config('filesystems.disks.videos.internal_prefix', 'protected-videos').'/'.$lecture->video_path, '/'),
                'Content-Type' => 'video/mp4',
                'Cache-Control' => 'private, no-store',
            ]);
        }

        // BinaryFileResponse supports HTTP Range requests → smooth seeking in the player.
        return response()->file($disk->path($lecture->video_path), [
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
