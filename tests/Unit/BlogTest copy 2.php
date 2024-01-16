<?php

// namespace Tests\Unit;
// use Illuminate\Http\UploadedFile;
// use Illuminate\Support\Facades\Storage;
// use Tests\TestCase;
// use Illuminate\Http\Request;
// use App\Http\Controllers\admin\BlogController;
// use App\Repository\admin\BlogRepository;
// use App\Http\Requests\Admin\BlogRequest;

// class BlogControllerTest extends TestCase
// {
//     public $blog="";

//     public function testStore()
//     {
//         Storage::fake('public'); // Use a fake disk for testing

//         // Create a mock request with necessary data
//         $request = new BlogRequest();
//         $controller = new BlogController(new BlogRepository()); // Instantiate the controller
//         $response = $controller->store($request); // Call the store method

//         $this->assertDatabaseHas('blogs',['image' => UploadedFile::fake()->image('test-image.jpg'),
//         'tags' => ['tag1', 'tag2'],
//         'title' => 'John Doe',
//         'body' => 'john@example.com',
//         'blog_category_id' => 4,
//         'author' => 'John Doe',
//         'archive' => 1,]);
//         $response->assertRedirect(); // Assert that the response is a redirect
//         $response->assertSessionHas('success'); // Assert that the success message is present in the session
//     }
// }
