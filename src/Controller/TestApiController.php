<?php
// src/Controller/LuckyController.php
namespace App\Controller;

//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestApiController
{
    #[Route('/api/test')]
    public function number(): JsonResponse
    {
        $numbers = [];
        for ($i=0;$i<9;$i++) {
            $numbers[]=rand(1,100);
        }
        return new JsonResponse(
            $numbers,200
        );
    }
}