<?php

namespace App\Http\Controllers\Stu;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * 获取当前用户信息
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=Auth::user();
        return view('stu.user.show', compact('user'));
    }


    /**
     * 获取别的id用户的信息
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('stu.user.show',compact('user'));
    }

    /**
     * 编辑用户资料
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        //权限判断policy
        return view('stu.user.edit',compact('user'));
    }

    /**
     * 更换资料
     * @param UserRequest $request
     * @param $id
     * @param ImageUploadHandler $upload
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request,User $user,ImageUploadHandler $upload)
    {
        $datas=$request->only('name','sign');
        $id=$user->id;
        if ($request->avatar) {
            $result=$upload->save($request->avatar, 'avatar', $id, 362);
            if ($result)
                $datas['avatar'] = $result['path'];
        }
        $user->update($datas);
        return redirect()->route('users.show',Auth::id())->with('success', '个人资料更新成功！');
    }

    /**
     * 查询界面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchindex(){
        return view('stu.user.search');
    }

    /**
     * 查询的结果集
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request){
        $users=User::where('email','like','%'.$request->input('search').'%')->paginate(15);
        return view('stu.user.table',compact('users'));
    }

    /**
     * 返回资料小卡片
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function small(User $user){
        $one=$user;
        return view('stu.user.small',compact('one'));
    }

}
