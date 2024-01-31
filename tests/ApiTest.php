<?php 
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;

class ApiTest extends WebTestCase
{
    private $client;
    private $host;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = HttpClient::create();
        #TODO : Make this an argument or part of a config file
        $this->host = "http://localhost:8000";
    }

    public function testStaffEndpoint(): void
    {
        //ADD
        $email = 'xxxx'.date("YmdHis")."@test.com";
        $postData = [
            [
                "id" => 43,
                "email" => $email,
                "firstname" => "Michael",
                "lastname" => "Jackson",
                "password" => "password",
                "squad" => "squad3",
                "status" => "active",
                "notes" => "mynotes",
                "start_date" => "2024-01-01 12:00:00",
            ]
        ];
        // Make a POST request to the API
        $response = $this->client->request('POST', $this->host.'/api/staff', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => $postData,
        ]);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $data = json_decode($response->getContent(),true);
        $this->assertSame('Record Has Been Successfully Added', $data["msg"]);

        //GET
        $response = $this->client->request('GET', $this->host.'/api/staff/');
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $data = json_decode($response->getContent(),true);
        $this->assertIsArray($data);
        
        $this->assertArrayHasKey('email', $data[0]);
        $this->assertArrayHasKey('fullname', $data[0]);
        $this->assertArrayHasKey('start_date', $data[0]);
        $this->assertArrayHasKey('squad', $data[0]);
        $this->assertArrayHasKey('status', $data[0]);
        $this->assertArrayHasKey('notes', $data[0]);
        $this->assertArrayNotHasKey('password', $data[0]);
        $tmpid = $data[0]['id'];
        
        //GET ID
        $response = $this->client->request('GET', $this->host.'/api/staff/'.$tmpid);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $data = json_decode($response->getContent(),true);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('email', $data[0]);
        $this->assertArrayHasKey('fullname', $data[0]);
        $this->assertArrayHasKey('start_date', $data[0]);
        $this->assertArrayHasKey('squad', $data[0]);
        $this->assertArrayHasKey('status', $data[0]);
        $this->assertArrayHasKey('notes', $data[0]);
        $this->assertArrayNotHasKey('password', $data[0]);

        //PUT
        $email = 'xxxx'.date("YmdHis")."@test.com";
        $putData = [
            [
                "email" => $email,
                'password' => 'testpassword',
                'firstname' => 'John',
                'lastname' => 'Doe',
                'status' => 'active',
                'start_date' => '2024-01-01 00:00:00',
                'squad' => 'squad1',
                'notes' => 'Test notes',
            ]
        ];

        // Make a PUT request to the API
        $response = $this->client->request('PUT', $this->host.'/api/staff/'.$tmpid, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => $putData,
        ]);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $data = json_decode($response->getContent(),true);
        $this->assertSame('Record Has Been Successfully Updated', $data["msg"]);

        //DELETE
        $response = $this->client->request('DELETE', $this->host.'/api/staff/'.$tmpid);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $data = json_decode($response->getContent(),true);
        $this->assertSame('Record Has Been Successfully Deleted', $data["msg"]);
    }
}
