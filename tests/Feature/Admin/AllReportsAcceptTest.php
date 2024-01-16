<?php

namespace Tests\Feature\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Models\Admin\Admin as admin;
use App\Models\Corporate\Corporate;
use App\Models\Corporate\Program;
use App\Models\Reporter\Report;
use App\Models\Reporter\Reporter;
use Faker\Factory;

class AllReportsAcceptTest extends TestCase
{
    use RefreshDatabase;

/**
* A basic test example.
*/

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
//function create reporter
public function create_reporter(){
    $reporter=[
        'email' => 'noor@gmail.com',
        'phone' => '0919977513',
        'password' =>  '777777733',
        'first_name' =>  'mohammed ahmed' ,
        'job' =>  'programmer' ,
        'hobby' => 'foot ball' ,
        'last_name' => 'musa' ,
        'birthday' =>  '1998-05-11',
        'company' => 'maxmillion' ,
        'city' =>  'khartoum' ,
        'nationality' => 'sudan',

      ];

    return  $reporter = Reporter::create($reporter);

}


//function create program
public function create_corporate(){
    $corporate=[
            'email' => 'corporate_test@gmail.com',
            'username' => 'maxmillion123',
            'password' => '88996688',
            'company_name' => 'maxmillion',
            'website' => 'https://facebook.com',
            'field' => 'information sceurity',
            'section' => 'it solution',
            'city' => 'cairo',
            'nationality' => 'Egypt',
      ];

     return $corporate = Corporate::create($corporate);

}



public function create_program(){
    $program = [
        'corporate_id'=>$this->create_corporate()->id,
        'reporter_quantity' => 1,
        'program_type' => 2,
        'management' => 1,
        'currency' =>'USD',
        'description_ar' => 'Store your passwords, manage sensitive data, fill in forms and
         login to your favorite sites within one click. Locker is here to help.',
        'description_en' => 'Store your passwords, manage sensitive data, fill in forms and
        login to your favorite sites within one click. Locker is here to help.',
        'policy_ar' => 'Store your passwords, manage sensitive data, fill in forms and
        login to your favorite sites within one click. Locker is here to help.',
        'policy_en' => 'Store your passwords, manage sensitive data, fill in forms and
        login to your favorite sites within one click. Locker is here to help.',

    ];
     return $program = Program::create($program);

}

public function create_report(){
    $report = [

        'program_id' => $this->create_program()->id,
        'reporter_id'=> $this->create_reporter()->id,
        'summarize' => 'Store your passwords, manage sensitive data, fill in forms and
        login to your favorite sites within one click. Locker is here to help.',
        'target' => 'TCP Service',
        'vulnerability'=>'Automotive Security Misconfiguration / Battery Management System / Firmware Dump (CRITICAL)',
        'url_vulnerability' => 'www.figma.com',
        'description' => 'Store your passwords, manage sensitive data, fill in forms and
        login to your favorite sites within one click. Locker is here to help.',
        'reproduce' => 'Store your passwords, manage sensitive data, fill in forms and
        login to your favorite sites within one click. Locker is here to help.',
        'impact' => 'Store your passwords, manage sensitive data, fill in forms and
        login to your favorite sites within one click. Locker is here to help.',
        'recommendation' => 'Store your passwords, manage sensitive data, fill in forms and
        login to your favorite sites within one click. Locker is here to help.',

    ];

     return $report = Report::create($report);

}



public function test_the_all_reports_accept_returns_a_successful_response(): void
{
    $this->login_test();
    $response = $this->get('/admin/reporter_accept_reports');

    $response->assertStatus(200);
}


public function test_the_get_accept_reports_returns_a_successful_response(): void
{
    $this->login_test();
    $response = $this->get('/admin/reporter_accept_reports_get');
    $response->assertStatus(200);
}



public function test_the_get_change_paid_reports_status_returns_a_successful_response(): void
{
    $this->login_test();
    $report=$this->create_report()->id;
    $response = $this->post('/admin/paid_report',['id'=>$report]);

     // Assert that the response has a 200 status code
     $this->assertEquals(200, $response->getStatusCode());

      // Check if the report's paid status has been updated
     $this->assertEquals(1, Report::find($report)->paid);


   // Assert that the response contains the expected JSON data
   $response->assertJson(['status' => 'success']);

    $this->assertDatabaseHas('reports',
    ['target' => 'TCP Service',

   ]);
}

}
