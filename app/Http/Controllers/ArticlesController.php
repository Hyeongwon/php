<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ArticlesRequest;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth', ['except' =>['index', 'show']]);
    }

    public function index($slug = null)
    {
        //$articles = \App\Article::with('user')->get(); 즉시로드

        $query = $slug
            ? \App\Tag::whereSlug($slug)->firstorFail()->articles()
            : new \App\Article;
        $articles = \App\Article::latest()->paginate(3);

        return view('articles.index', compact('articles'));
        //return __METHOD__ . '은(는) Article 컬렉션을 조회합니다.';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return __METHOD__. '은(는) Article 컬렉션을 만들기 위한 폼을 담은 뷰를 반환합니다.';
        $article = new \App\Article;

        return view('articles.create', compact('article'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\ArticlesRequest $request)
    {
        //return __METHOD__. '은(는) 사용자의 입력한 폼 데이터로 새로운 Article 컬렉션을 만듭니다.';

       /* $rules = [

            'title' => ['required'],
            'content' => ['required', 'min:10'],
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()) {

            return back()->withErrors($validator)
                ->withInput();
        }

        $article = \App\User::find(1)->articles()
                                ->create($request->all());

        if(! $article) {

            return back()->with('flash_message', '글이 저장되지 않습니다.')
                ->withInput();
        }

        return redirect(route('articles.index'))
            ->with('flash_message', '작성하신 글이 저장되었습니다.');*/

       //$article = \App\User::find(1)->articles()->create($request->all());
        $article = $request->user()->articles()->create($request->all());

       if(! $article) {

           return back() -> with('flash_message', '글이 저장되지 않았습니다.')
               ->withInput();
       }
       $article->tags()->sync($request->input('tags'));
       event(new \App\Events\ArticlesEvent($article));
       /*var_dump('이번트를 던집니다!');
       event(new \App\Events\ArticleCreated($article));
       var_dump('이번트를 던졌습니다.');*/

       if( $request->hasFile('files')) {

           $files = $request->file('files');

           foreach ($files as $file) {
               $filename = str_random().filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);
               $file->move(attachments_path(), $filename);

               $article->attachments()->create([

                   'filename' => $filename,
                   'bytes' => $file -> getSize(),
                   'mime' => $file -> getClientMimeType()
               ]);
           }
       }

       return redirect(route('articles.index'))->with('flash_message', '작성하신 글이 저장 되었씁니다.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Article $article)
    {
        //$article = \App\Article::findOrFail($id);

        //return __METHOD__. '은(는) 다음 기본 키를 가진 Article 모델을 조회합니다.'. $id;
        //debug($article->toArray());

        $comments = $article->comments()->with('replies')->whereNull('parent_id')->
            latest()->get();
        return view('articles.show', compact('article', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Article $article)
    {
        /*return __METHOD__. '은(는) 다음 기본 키를 가진 Article 모델을 수정하기 위한 폼을 담은
                            뷰를 반환합니다.'.$id;*/
        $this->authorize('update', $article);
        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\ArticlesRequest $request, \App\Article $article)
    {
        /*return __METHOD__. '은(는) 사용자의 입력한 폼 데이터로 다음기본 키를 가진
                            Article 모델을 수정합니다.' .$id;*/

        $article->update($request->all());
        $article->tags()->sync($request->input('tags'));
        flash()->success('수정하신 내용을 저장했습니다.');

        return redirect(route('articles.show', $article->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Article $article)
    {
        error_log("!!!");
        /*return __METHOD__. '은(는) 다음 기본 키를 가진 Article 모델을 삭제합니다.' .$id;*/
       // $this->authorize('DELETE', $article);
        $article->delete();

        return response()->json([], 204);
    }
}
