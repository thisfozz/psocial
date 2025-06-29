<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostVideo extends Model
{
    protected $fillable = [
        'post_id',
        'platform',
        'video_id',
        'embed_code',
        'thumbnail_url'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    
    public static function extractVideoData($url)
    {
        $platform = self::detectPlatform($url);
        $videoId = self::extractVideoId($url, $platform);

        if (!$platform || !$videoId) {
            return null;
        }

        return [
            'platform' => $platform,
            'video_id' => $videoId,
            'embed_code' => self::getEmbedCode($url, $platform, $videoId),
            'thumbnail_url' => self::getThumbnailUrl($url, $platform, $videoId)
        ];
    }

    protected static function detectPlatform($url){
        $patterns = [
            'youtube' => '/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            'rutube' => '/^(https?:\/\/)?(www\.)?(rutube\.ru\/video\/)([a-zA-Z0-9_-]{11})/',
            'twitch_clip' => '/^(https?:\/\/)?(www\.)?(twitch\.tv\/[^\/]+\/clip\/[^\/\?]+)/',
            'twitch_stream' => '/^(https?:\/\/)?(www\.)?(twitch\.tv\/[^\/]+)(?:\/|$)/',
        ];

        foreach ($patterns as $platform => $pattern) {
            if(preg_match($pattern, $url)){
                return $platform;
            }
        }

        return null;
    }

    protected static function extractVideoId($url, $platform){
        switch ($platform) {
            case 'youtube':
                if(preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]+)/", $url, $matches)){
                    return $matches[1];
                }
                return null;
            case 'rutube':
                if(preg_match('/rutube\.ru\/video\/([a-zA-Z0-9_-]{32})/', $url, $matches)){
                    return $matches[1];
                }
                return null;
            case 'twitch_stream':
                if(preg_match('/twitch\.tv\/([a-zA-Z0-9_-]+)/', $url, $matches)){
                    return $matches[1];
                }
                return null;
            case 'twitch_clip':
                if(preg_match('/twitch\.tv\/[^\/]+\/clip\/([^\/\?]+)/', $url, $matches)){
                    return $matches[1];
                }
                return null;
            default:
                return null;
        }
    }

    protected static function getEmbedCode($url, $platform, $videoId){
        switch ($platform) {
            case 'youtube':
                return "https://www.youtube.com/embed/{$videoId}?rel=0&showinfo=0&autoplay=0";
            case 'rutube':
                return "https://rutube.ru/play/embed/{$videoId}?rel=0&showinfo=0&autoplay=0";
            case 'twitch_stream':
                return "https://player.twitch.tv/?channel={$videoId}&parent=".request()->getHost();
            case 'twitch_clip':
                return "https://clips.twitch.tv/embed?clip={$videoId}&parent=".request()->getHost();
            default:
                return null;
        }
    }

    protected static function getThumbnailUrl($url, $platform, $videoId){
        switch ($platform) {
            case 'youtube':
                return "https://img.youtube.com/vi/{$videoId}/0.jpg";
            case 'rutube':
                return "https://rutube.ru/video/{$videoId}/embed/img/poster.jpg";
            case 'twitch_stream':
                return "https://static-cdn.jtvnw.net/previews-ttv/live_user_{$videoId}.jpg";
            case 'twitch_clip':
                return "https://clips-media-assets2.twitch.tv/{$videoId}/thumbnail-480x272.jpg";
            default:
                return null;
        }
    }
}