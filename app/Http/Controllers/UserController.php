<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;

class UserController extends Controller
{
    protected $repository_user;

    /**
     * UserController constructor.
     * @param $repository_user
     */
    public function __construct(UserRepository $repository_user)
    {
        $this->repository_user = $repository_user;
    }

    /**
     * 用户设置列表
     */
    public function settingList()
    {
        $user = user();
        return view('users.setting')->with(compact('user'));
    }

    /**
     * 更新用户设置
     */
    public function update()
    {
        try {
            $response = user('api')->settings()->update(request()->all());
            if ($response == false) {
                throw new \Exception('更新配置失败');
            }
            $status = 0;
            $msg = '更新配置成功';
            return response()->json(compact('msg', 'status'));
        } catch (\Exception $e) {
            $status = 1478;
            $msg = $e->getMessage();
            return response()->json(compact('msg', 'status'));
        }

    }

    /**
     * 处理用户头像处理界面
     */
    public function avatar()
    {
        return view('users.avatar');
    }

    /**
     * 保存用户头像
     * @return \Illuminate\Http\JsonResponse
     */
    public function avatarUpload()
    {
        try {
            $img_url = $this->repository_user->avatarUpload();

            $msg = '更新上传成功';
            $status = 0;
            return response()->json(compact('status', 'msg', 'img_url'));
        } catch (\Exception $e) {
            $status = 1478;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }
}
