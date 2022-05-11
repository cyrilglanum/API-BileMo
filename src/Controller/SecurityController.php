<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends abstractController
{
    /**
     * @Route(name="api_login", path="/api/login_check")
     * @return JsonResponse
     */

    public function api_login(Request $request, ClientRepository $clientRepository): JsonResponse
    {
//        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        $data = json_decode($request->request->get('data'));

        $user = $clientRepository->findOneBy([
            'username' => $data->username,
            'password' => $data->password
        ]);

        if ($user) {
            return new JsonResponse(['email' => $user->getEmail()]);
        } else {
            throw new CustomUserMessageAuthenticationException('Email could not be found.');

        }
    }

}