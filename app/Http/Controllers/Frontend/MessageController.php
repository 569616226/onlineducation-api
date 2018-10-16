<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\MessageRepository;
use EasyWeChat;
use Illuminate\Http\Request;

/**
 * Class MessageController
 * @package App\Http\Controllers\Frontend
 */
class MessageController extends Controller
{

    /**
     * @var MessageRepository
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
     * @return \App\Http\Resources\Mobile\MessageCollection
     */
    public function lists()
    {

        return $this->repository->get_mobile_list();

    }

    /**
     * @param $id
     * @return \App\Http\Resources\Mobile\Message
     */
    public function show($id )
    {

        return $this->repository->show($id);

    }


    /**
     * 客服消息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function staffMessage( Request $request )
    {

        $content = $request->get( 'content' );
        $content = '【反馈建议】' . strip_tags( str_replace( '</div>', "\n", $content ), "<a>" ) . "\n ---反馈者:" . guest_user()->nickname;

        return $this->repository->staff_message($content);

    }
}
