<?php
namespace App\ExoRoutes\Controller;

use Symfony\Component\HttpFoundation\Response;

class WelcomeController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return new Response('welcome !');
    }

    /**
     * @return Response
     */
    public function list(): Response
    {
        return new Response('list ! ');
    }

    /**
     * @param int|string $id
     * @return Response
     */
    public function show($id): Response
    {
        return new Response('show: '.$id);
    }

    /**
     * @return Response
     */
    public function store(): Response
    {
        return new Response('store !');
    }

    /**
     * @param int|string $id
     * @return Response
     */
    public function update($id): Response
    {
        return new Response('update: '.$id);
    }

    /**
     * @param string $id
     * @return Response
     */
    public function delete(string $id): Response
    {
        return new Response('delete: '.$id);
    }
}
