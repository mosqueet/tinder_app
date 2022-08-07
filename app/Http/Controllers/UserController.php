<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Swipe;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;

class UserController extends Controller
{
    /**
     *  ●index()メソッドを作成する
     *       1.ユーザーを取得してメイン画面を表示させる。
     *       2.ユーザーは自分以外と、未選択のユーザーのみ取得する。
     */
    public function index()
    {
        //ログインユーザーの取得
        $auth = User::find(Auth::id());

        //ログインユーザーがすでに選択したユーザー達のidを配列で取得
        //to_user_id一覧が取得できる。
        $swipedUserIds = $auth->from_users()->get()->pluck('id');

        //idsには含まれていないusersを取得して、その一番最初のuserを取得
        $user = User::where('id', '<>', $auth->id)->whereNotIn('id', $swipedUserIds)->first();

        return view('users.index', compact('user'));
    }

    /**
     *  ●store()メソッドを作成する
     *     1.好きボタンを押して、かつ、相手のis_likeの状態もtrueなら、
     *       マッチしましたというフラッシュメッセージ付きで
     *       route('users.index')にリダイレクトする。
     *     2.falseなら、そのまま、route('users.index')にリダイレクトする。
     *       結果index()で未選択のユーザーが取得されindex.blade.phpで表示されます。
     */
    public function store(Request $request, User $user)
    {

        $swipes = $user->from_users();
        //多対多におけるupdateOrCreate()の変わり。
        $swipes->syncWithoutDetaching([$request->except('_token')]);
        $is_like_auth = $swipes->wherePivot('to_user_id', Auth::id())->wherePivot('is_like', true)->exists();

        //authが好きなら
        if ($request->is_like) {
            //その相手のユーザーのis_likeもtrueなら
            if ($is_like_auth) {
                //true同士なら、フラッシュメッセージをつけてメイン画面にリターン
                return redirect()->route('users.index')->with('flash_message', 'マッチしました');
            }
        }
        //falseなら、そのままメイン画面にリターン
        return redirect()->route('users.index');
    }

    /**
     *マッチした画面一覧へ
     */
    public function matches()
    {
        $auth = User::find(Auth::id());
        //matches()はリレーションのメソッドです。
        $users = $auth->matches()->orderBy('id','asc')->get();

        return view('users.matches', compact('users'));
    }

    // 追記 １人のマッチング相手の詳細ページ
    //　＋その他のマッチング相手はページネーションでリンクしている。
    public function matches_show($num)
    {
        $auth = User::find(Auth::id());
        $match_users = $auth->matches()->orderBy('id', 'asc')->get()->collect();
        $main_user = $match_users[$num];
        $count = $match_users->count();
        $prev = $num - 1 < 0 ? $num : $num - 1;
        $next = $num + 1 > $count - 1 ? $num : $num + 1;

        return view('users.matches_show', compact('match_users', 'main_user', 'prev', 'next', 'num'));
    }

    /**
     * マッチしたユーザーのルーム画面
     */
    public function room(User $user)
    {

        $is_match_user=$user->to_users()->where('from_user_id',Auth::id())->exists();
        //$auth = User::find(Auth::id());
        // $is_match_user = $auth->matches()->get()->pluck('id')->contains($user->id);

        if ($is_match_user) {

            //get_room_messages()はリレーションのメソッドです。
            //loadCountでリレーション先の個数もリレーションで取得できる。
            $user = $user->loadCount('get_room_messages');

            return view('users.room', compact('user'));
        }
        return  redirect()->route('users.matches');
    }

    /**
     * messageを保存する
     */
    public function store_message(Request $request, User $user)
    {
        $message = $request->message;
        Chat::create(['message' => $message, 'from_user_id' => Auth::id(), 'to_user_id' => $user->id]);

        return 'success';
    }

    /**
     * messageを取得
     */
    public function get_messages(User $user)
    {
        $messages = $user->get_room_messages()->get();

        return $messages;
    }
}
