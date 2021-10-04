<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\ModelNotFoundException;
    use Illuminate\Support\Facades\File;
    use Spatie\YamlFrontMatter\Document;
    use Spatie\YamlFrontMatter\YamlFrontMatter;

    class Post{

        public $title;

        public $date;

        public $body;

        public function __construct($title, $date, $body){
            $this->title = $title;

            $this->date = $date;

            $this->body = $body;

        }



        static public function all()
        {
           return collect(File::files(resource_path("posts")))
           ->map(fn($file) => YamlFrontMatter::parseFile($file))
           ->map(fn($document) => new Post(
               $document->title,
               $document->date,
               $document->body()
           ));
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