<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\ModelNotFoundException;
    use Illuminate\Support\Facades\File;

    class Post{

        static public function all()
        {
            $files = File::files(resource_path("posts/"));

            return array_map(function($file){
                return $file->getContents();
            }, $files);
        }

        public static function find($slug)
        {
            $path = resource_path("/posts/{$slug}.html");
            if(! file_exists($path)){
               abort(404);
            }

            $post =  cache()->remember("posts.{$slug}", now()->addHour(), function() use ($path){
                var_dump('file gets content');
                return file_get_contents($path);
            });
            
            return $post;
        }

    }