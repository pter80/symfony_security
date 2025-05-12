<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Entity\User;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/add-role/{id}/{role}', name: 'add_role_to_user')]
    public function addRoleToUser(User $user, string $role, EntityManagerInterface $em): Response
    {
        if (!$user) {
            return new Response('User not found', Response::HTTP_NOT_FOUND);
        }
        // Get current roles and add the new one if it's not already assigned
        $roles = $user->getRoles();

        if (!in_array($role, $roles)) {
            $roles[] = $role;
            $user->setRoles($roles);

            // Persist changes
            $em->persist($user);
            $em->flush();
        }

        return new Response('Role added successfully');
    }
    #[Route('/remove-role/{id}/{role}', name: 'remove_role_to_user')]
    public function removeRoleToUser(User $user, string $role, EntityManagerInterface $em): Response
    {
        if (!$user) {
            return new Response('User not found', Response::HTTP_NOT_FOUND);
        }
        // Get current roles and add the new one if it's not already assigned
        $roles = $user->getRoles();
        
        if (in_array($role, $roles)) {
            $key = array_search($role,$roles);
            unset($roles[$key]);
            $user->setRoles($roles);

            // Persist changes
            $em->persist($user);
            $em->flush();
        }

        return new Response('Role deleted successfully');
    }
}
