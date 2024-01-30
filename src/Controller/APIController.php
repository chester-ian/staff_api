<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class APIController extends AbstractController
{
    #[Route('/api/staff', methods: ['GET'], name: 'get_all_staff')]
    public function index(): JsonResponse
    {


      //create a new Response object
      $response = new Response();

      //set the return value
      $response->setContent('Hello World!');

      //make sure we send a 200 OK status
      $response->setStatusCode(Response::HTTP_OK);

      // set the response content type to plain text
      $response->headers->set('Content-Type', 'text/plain');

      // send the response with appropriate headers
      $response->send();

        /*return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/APIController.php',
        ]);*/
    }
}
