<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\MemoTag;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // ここでメモを取得

        $tags = Tag::where('user_id', '=', \Auth::id())->whereNull('deleted_at')->orderBy('id', 'DESC')
            ->get();

        return view('create', compact('tags'));
    }

    // --------------------------------------------------------------------------------------

    public function store(Request $request)
    {
        $posts = $request->all();
        $request->validate(['content' => 'required']);
        // dd($)
        // dump dieの略数→メソッド引数の取った値を展開して止める→　データ確認

        //----- ここからトランザクション開始 ----------
        DB::transaction(function () use ($posts) {
            // メモIDをインサートして取得
            $memo_id = Memo::insertGetId(['content' => $posts['content'], 'user_id' => \Auth::id()]);
            $tag_exists = Tag::where('user_id', '=', \Auth::id())->where('name', '=', $posts['new_tag'])
                ->exists();
            // 新規タグが入力されているかチェック
            // 新規タグが既にtag_tableに存在していないかチェック tag_exists←laravel特有
            if (!empty($posts['new_tag']) || $posts['new_tag'] === "0"  && !$tag_exists) {
                // 新規タグが既に存在していなければtag_tableについか
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                // memo_tagsにインサートして,メモとタグを紐付ける
                MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag_id]);
            }

            // 既存タグが紐付けられた場合memo→memo_tagsにインサート
            if (!empty($posts['tags'][0])) {
                foreach ($posts['tags'] as $tag) {
                    MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag]);
                }
            }
        });



        return redirect(route('home'));
    }

    // ----------------------------------------------------------------------------------

    public function edit($id)
    {
        // ここでメモを取得
        $edit_memo = Memo::select('memos.*', 'tags.id AS tag_id')
            ->leftjoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
            ->leftjoin('tags', 'memo_tags.tag_id', '=', 'tags.id')
            ->where('memos.user_id', '=', \Auth::id())
            ->where('memos.id', '=', $id)
            ->whereNull('memos.deleted_at')
            ->get();
        // dd($edit_memo);

        $include_tags = [];
        foreach ($edit_memo as $memo) {
            array_push($include_tags, $memo['tag_id']);
        }
        $tags = Tag::where('user_id', '=', \Auth::id())->whereNull('deleted_at')->orderBy('id', 'DESC')
            ->get();

        // find($id);

        return view('edit', compact('edit_memo', 'include_tags', 'tags'));
    }

    // ----------------------------------------------------------------------------------

    public function update(Request $request)
    {
        $posts = $request->all();
        $request->validate(['content' => 'required']);

        // トランザクション開始
        DB::transaction(function () use ($posts) {
            Memo::where('id', $posts['memo_id'])->update(['content' => $posts['content']]);
            MemoTag::where('memo_id', '=', $posts['memo_id'])->delete();

            if (!empty($posts['tags'][0])) {
                foreach ($posts['tags'] as $tag) {
                    MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag]);
                }
            }
        });

        $tag_exists = Tag::where('user_id', '=', \Auth::id())->where('name', '=', $posts['new_tag'])
            ->exists();
        // 新規タグが入力されているかチェック
        // 新規タグが既にtag_tableに存在していないかチェック tag_exists←laravel特有
        if (!empty($posts['new_tag']) || $posts['new_tag'] === "0"  && !$tag_exists) {
            // 新規タグが既に存在していなければtag_tableについか
            $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
            // memo_tagsにインサートして,メモとタグを紐付ける
            MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag_id]);
        }




        return redirect(route('home'));
    }

    // -------------------------------------------------------------------------------------

    public function destroy(Request $request)
    {
        $posts = $request->all();

        // 論理削除を使用.復活が可能のため
        Memo::where('id', $posts['memo_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);
        // delete()　NG← 物理削除 データベースの一列ごと消える

        return redirect(route('home'));
    }
}
