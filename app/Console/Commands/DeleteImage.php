<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Image;
use Illuminate\Console\Command;
use Carbon\Carbon;

class DeleteImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Blog:delete-image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Blog image delete';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $blog_images = Image::where([['created_at', '<', Carbon::now()->subHours(8)],['blog_id','=',null]])->get();
        foreach ($blog_images as $image) {
            $image->delete();
            unlink("public/assets/front/img/blog/".$image->image_title);
        }
    }
}
