<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\AllMessageRequest;
use App\Http\Requests\Message\SendMessageRequest;
use App\Http\Requests\Message\SystemMessageRequest;
use App\Repositories\LessonRepository;
use App\Repositories\MessageRepository;
use EasyWeChat;
use Illuminate\Http\Request;

/**
 * Class MessageController
 * @package App\Http\Controllers\Backend
 */
class MessageController extends Controller
{

    /**
     * @var LessonRepository
     */
    private $repository;

    /**
     * WeChatController constructor.
     * @param $app
     */
    public function __construct(MessageRepository $repository)
    {

        $this->repository = $repository;
    }


    /**
     * @param SystemMessageRequest $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function sysLists(SystemMessageRequest $request)
    {

        return $this->repository->getSysLists();

    }

    /**
     * 群发消息列表数据
     * @param AllMessageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(AllMessageRequest $request)
    {
        return $this->repository->getLists();

    }

    /**
     *
     * 直接到微信后台发送就可以满足需求
     * @param Request $request
     * @return string
     */
    public function sendMessages( SendMessageRequest $request )
    {
        return $this->repository->send_messages($request->all());


    }
}
