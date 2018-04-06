<?php

namespace App\Listeners;

use App\Events\CommentsEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class CommentsEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    use SerializesModels;

    public $comment;

    public function __construct(\App\Comment $comment)
    {
        $this -> comment = $comment;
    }

    /**
     * Handle the event.
     *
     * @param  CommentsEvent  $event
     * @return void
     */
    public function handle(CommentsEvent $event)
    {
        //
    }
}
