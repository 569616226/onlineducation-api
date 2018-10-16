<?php

namespace Tests\Feature\Login;


use Tests\BaseCase;

class LoginControllerTest extends BaseCase
{

    /** @test */
    public function login_seccuss_website()
    {
        $data = [
            'name' => 'admin',
            'password' => 'admin',
        ];

        $response = $this->json('POST','/api/login',$data);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'expires_in'
            ]);
    }

    /** @test */
    public function login_with_error_password_website()
    {
        $data = [
            'name' => 'admin',
            'password' => '123',
        ];

        $response = $this->json('POST','/api/login',$data);
        
        $response
            ->assertStatus(421)
            ->assertJsonStructure([
                'status',
                'message',
            ])
        ;
    }

    /** @test */
    public function login_user_a_frozen_user_website()
    {
        $data = [
            'name' => 'role',
            'password' => 'role',
        ];

        $response = $this->json('POST','/api/login',$data);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
            ])
        ;
    }


    /*========================================移动端测试===============================================*/

    /** @test */
    public function mobile_login_seccuss_website()
    {
        $data = [
            'openid' => 'oDMF40TjhXnYMy0e5RLPX3ZU-kzw',
        ];

        $response = $this->json('POST','/api/item/login',$data);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'token'
            ])
        ;
    }
}
