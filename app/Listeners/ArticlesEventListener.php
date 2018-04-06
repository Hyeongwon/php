<?php

namespace App\Listeners;


use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticlesEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  article.created  $event
     * @return void
     */
    public function handle(\App\Events\ArticlesEvent $event)
    {
        /* var_dump('이벤트를 받습니다. 받은 데이터(상태).');
         var_dump($event->article->toArray());*/

        $comment = $event->comment;
        $comment->load('commentable');
        $to = $this -> recipients($comment);


       if (!$to) {

           return;
       }

       \Mail::send('emails.comments.created', compact('comment'), function ($message)
       use ($to) {

           $message->to($to);
           $message->subject(
               sprintf('[%s] 새로운 댓글이 등록되었습니다.', config('app,name'))
           );
       });

       if($event->action === 'created') {

           \Log::info(sprintf(
               '새로운 포럼 글이 등록되었습니다.: %s',
               $event->article->title
           ));
       }
    }

    private function recipients(\App\Comment $comment) {

        static $to = [];

        if($comment -> parent()) {

            $to[] = $comment->parent->user->email;

            $this->recipients($comment->parent());
        }

        if($comment->commentable()->notification) {

            $to[] = $comment->commentable->user->email;
        }

        return array_unique($to);
    }
}
