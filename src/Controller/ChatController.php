<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\ChatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{

    /**
     * @Route("/chat", name="app_chat")
     * @param PublisherInterface $publisher
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function index(PublisherInterface $publisher, Request $request, EntityManagerInterface $manager)
    {

        $message = new Message();

        $form = $this->createForm(ChatType::class, $message);
        $form->handleRequest($request);
        $user = $this->getUser()->getPassword();
/*        $user = 'test';*/

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setUser($user);
            $update = new Update(
                'http://monsite.com/ping', //topic (URL) sur laquelle s'abonner
                json_encode(['message' => $message->getMessage()])

            );

            // The Publisher service is an invokable object
            $publisher($update);

            $manager->persist($message);
            $manager->flush();
        }

        return $this->render('chat.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

}
