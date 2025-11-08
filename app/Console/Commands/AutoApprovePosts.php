<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Notification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AutoApprovePosts extends Command
{
    protected $signature = 'posts:auto-approve';
    protected $description = 'Automatically approve posts that have been pending for more than 2 hours';

    public function handle()
    {
        $twoHoursAgo = Carbon::now()->subHours(2);

        $posts = Post::where('status', 'pending')
            ->where('submitted_at', '<=', $twoHoursAgo)
            ->get();

        foreach ($posts as $post) {
            $post->update(['status' => 'approved']);

            // Notify post author
            Notification::create([
                'user_id' => $post->user_id,
                'type' => 'post_approved',
                'message' => 'Your post "' . $post->title . '" has been automatically approved!',
                'post_id' => $post->id,
            ]);

            $this->info("Post #{$post->id} - '{$post->title}' has been auto-approved.");
        }

        $count = $posts->count();
        $this->info("Total posts auto-approved: {$count}");

        return 0;
    }
}
