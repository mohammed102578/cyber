<?php

namespace Tests\Feature\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Vulnerability;
use App\Models\Admin\Admin as admin;

class VulenrabilityTest extends TestCase
{
    use RefreshDatabase;
    public function login_test(){
        $data = [
            'phone'=>'919977513',
            'email'=>"mohammed123@gmail.com",
            'password'=>"@gaabhhbh",
            'image'=>null,
            'name'=>'mohammed',
            'last_seen_at'=>null,
            'password_changed_at'=>null
        ];
        $admin=Admin::create($data);
       return $this->actingAs($admin,'admin');

    }
    public function test_the_vulenrabilities_returns_a_successful_response(): void
    {
        $this->login_test();
        $response = $this->get('/admin/vulnerabilities');
        $response->assertStatus(200);
    }


    public function test_the_get_vulenrabilities_returns_a_successful_response(): void
    {
        $this->login_test();
        $response = $this->get('/admin/get_vulenrabilities');
        $response->assertStatus(200);
    }


    public function test_can_create_vulnerability()
    {
        $this->login_test();
        $response = $this->post('/admin/create_vulnerability', [
            'vulnerability' => 'Test Vulnerability',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('vulnerabilities', ['vulnerability' => 'Test Vulnerability']);
    }


    public function test_can_edit_vulnerability()
    {
        $this->login_test();
        $vulnerability = Vulnerability::create([
            'vulnerability' => 'Test Vulnerability',
        ]);

        $response = $this->get('/admin/edit_vulnerability', [
            'id' => $vulnerability->id,
        ]);
        $response->assertStatus(200);

        $retriveVulnerability=Vulnerability::find($vulnerability->id);

        $this->assertEquals($vulnerability->id,$retriveVulnerability->id);

    }

    public function test_can_update_vulnerability()
    {
        $this->login_test();

        $vulnerability = Vulnerability::create([
            'vulnerability' => 'Test Vulnerability',
        ]);

        $response = $this->patch('/admin/update_vulnerability/',
        [   'id'=>$vulnerability->id,
            'vulnerability' => 'Updated Test Vulnerability',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('vulnerabilities', ['vulnerability' => 'Updated Test Vulnerability']);
    }

    public function test_can_delete_vulnerability()
    {
        $this->login_test();

        $vulnerability = Vulnerability::create([
            'vulnerability' => 'Test Vulnerability',
        ]);

        $response = $this->delete('/admin/delete_admin_vulnerability',['id'=>$vulnerability->id]);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('vulnerabilities', ['vulnerability' => 'Test Vulnerability']);
    }



}
