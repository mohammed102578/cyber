<?php

namespace Tests\Feature\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\Admin\Admin as admin;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class AccountTest extends TestCase
{
    use RefreshDatabase;

/**
* A basic test example.
*/

public function login_test(){
    $data = [
        'phone'=>'0919977513',
        'email'=>"mohammed123@gmail.com",
        'password'=>"22222222",
        'image'=>null,
        'name'=>'mohammed',
        'last_seen_at'=>null,
        'password_changed_at'=>null
    ];
    $admin=Admin::create($data);
   return $this->actingAs($admin,'admin');

}
public function test_the_account_returns_a_successful_response(): void
{
    $this->login_test();
    $response = $this->get('/admin/account');
    $response->assertStatus(200);
}


public function test_the_security_returns_a_successful_response(): void
{
    $this->login_test();
    $response = $this->get('/admin/security');
    $response->assertStatus(200);
}



public function test_can_update_account()
{
    $this->login_test();

    $admin=Admin::first();

    $response = $this->Patch('/admin/update_account/',
    [
         'id'=>$admin->id,
         'name' => 'musa',
         //'image'=>UploadedFile::fake()->image('test-image.jpg')
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('admins', ['name' => 'musa']);
}



public function test_can_update_password()
{
    $this->login_test();

    $admin=Admin::first();

    $response = $this->Patch('/admin/change_password/',
    [
         'id'=>$admin->id,
         'new_password' => '11111111',
         'old_password' => '22222222',
         'confirm_password'=>'11111111'
    ]);

    $this->assertTrue(Hash::check('11111111',$admin->fresh()->password));

    $response->assertRedirect();
}



public function test_can_update_email()
{
    $this->login_test();

    $admin=Admin::first();

    $response = $this->Patch('/admin/change_email/',
    [
         'id'=>$admin->id,
         'new_email' => 'Test@gmail.com',
         'old_email' => $admin->email,
         'password' =>  '22222222'
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('admins', ['email' => 'Test@gmail.com']);
}


public function test_can_update_phone()
{
    $this->login_test();

    $admin=Admin::first();

    $response = $this->Patch('/admin/change_phone/',
    [
         'id'=>$admin->id,
         'new_phone' => '0911036308',
         'old_phone' => $admin->phone,
         'password'  => '22222222'
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('admins', ['phone' => '0911036308']);
}


}
