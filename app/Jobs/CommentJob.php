<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\CommentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CommentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $comment;
    protected $article_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $comment, $article_id)
    {
        //
        $this->user = $user;
        $this->comment = $comment;
        $this->article_id = $article_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $this->user->notify(new CommentNotification($this->user, $this->comment, $this->article_id));

    }
}
