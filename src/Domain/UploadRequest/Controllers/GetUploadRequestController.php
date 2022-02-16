<?php
namespace Domain\UploadRequest\Controllers;

class GetUploadRequestController
{
    public function __invoke()
    {
        return [
            'user' => [
                'data' => [
                    'attributes' => [
                        'name'   => 'Jane Doe',
                        'avatar' => [
                            'md' => 'http://192.168.1.112:8000/avatars/md-f45abbe5-962c-4229-aef2-9991e96d54d9.png',
                            'sm' => 'http://192.168.1.112:8000/avatars/md-f45abbe5-962c-4229-aef2-9991e96d54d9.png',
                            'xs' => 'http://192.168.1.112:8000/avatars/md-f45abbe5-962c-4229-aef2-9991e96d54d9.png',
                        ],
                    ],
                    'id'   => '123',
                    'type' => 'user',
                ],
            ],
        ];
    }
}
