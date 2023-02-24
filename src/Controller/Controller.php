<?php

namespace App\Controller;

use App\Security\LoginAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class Controller extends AbstractController
{
    /**
     * @var FormInterface
     */
    protected $lastFormValidation;

    /**
     * @param Request $request
     * @param string $formName
     * @param string $tokenName
     * @return void
     */
    public function checkCsrfToken(Request $request,
                                   string $formName,
                                   string $tokenName = '_token'): void
    {
        $contentType = $request->headers->get('Content-Type');
        $isJson = str_contains($contentType, 'json') // PHP 8+ OR Symfony 5.4+
        or $request->isXmlHttpRequest();

        // Get token
        if ($isJson) {
            try {
                $token = $request->toArray()[$tokenName] ?? null;
            } catch (JsonException $exception) {
                $token = null;
                // To custom error
                throw new BadRequestException('Invalid json.', null, 400);
            }
        } else {
            $token = $request->get($tokenName);
        }

        // Check token
        if (!$this->isCsrfTokenValid($formName, $token)) {
            if ($isJson) {
                // Json response
                $response = new JsonResponse(['error' => 'Invalid token!']);
                $response->send();
                exit();
            } else {
                // Vue response
                throw new BadRequestHttpException('Page expired!', null, 419);
            }
        }
    }

    /**
     * @param Request $request
     * @param string $type
     * @return array
     */
    public function validate(Request $request, string $type, array $options = []): array
    {
        $errors = [];

        $form = $this->createForm(
            $type,
            null,
            array_merge($options, ['allow_extra_fields' => true])
        );
        $form->submit(array_merge($request->query->all(), $request->request->all()));

        if (!$form->isValid()) {
            $symfonyErrors = $form->getErrors(true);

            foreach ($symfonyErrors as $error) {
                $errors[] = $error->getMessage();
            }
        }

        $this->lastFormValidation = $form;

        return $errors;
    }

    /**
     * @return mixed
     */
    public function getLastFormValidation()
    {
        return $this->lastFormValidation;
    }
}
