<?php

namespace Cimmi\AuthBundle\Services;

use Cimmi\AuthBundle\Entity\User;
use Services\TwigMailGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AuthenticationMailer
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /** @var User $user */
    public function sendRegistrationConfirmationWithPasswordReset($user)
    {
        $router = $this->container->get('router');
        $userManager = $this->container->get('fos_user.user_manager');
        $tokenGenerator = $this->container->get('fos_user.util.token_generator');
        $twigMailGenerator = $this->container->get('twig_mail_generator');
    
        $template = '@CimmiAuth/Emails/registration_confirmation_reset_password.html.twig';

        $user->setEnabled(false);
        $user->setPasswordRequestedAt(new \DateTime());
        $user->setConfirmationToken($tokenGenerator->generateToken());

        $url = $router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);
        
        $twigMailGenerator->sendMessage($template, $user->getEmail(), array('user' => $user, 'url' => $url));

        //Update database lastly in case something went wrong before
        $userManager->updateUser($user);
    }

}