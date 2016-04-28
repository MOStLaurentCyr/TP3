<?php

namespace Services;

use Swift_Mailer;
use Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Environment;
use Twig_Template;

class TwigMailGenerator
{
    private $twig;
    private $mailer;
    private $container;

    public function __construct(Twig_Environment $twig, Swift_Mailer $mailer, ContainerInterface $container)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->container = $container;
    }

    public function sendMessage($twigTemplate, $sendTo, $parameters = array())
    {
        /** @var Twig_Template $template */
        $template = $this->twig->loadTemplate($twigTemplate);

        $subject =  $template->renderBlock('subject', $parameters);
        $bodyHtml = $template->renderBlock('body_html', $parameters);
        $bodyText = $template->renderBlock('body_text', $parameters);

        /** @var Swift_Message $message */
        $message = Swift_Message::newInstance();
        $message->setSubject($subject);
        $message->setBody($bodyText, 'text/plain');
        $message->addPart($bodyHtml, 'text/html');
        $message->setTo($sendTo);
        $message->setFrom($this->container->getParameter('mailer_user'));

        $this->mailer->send($message);
    }
}