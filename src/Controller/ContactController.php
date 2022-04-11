<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
class ContactController extends BaseController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function sendEmail(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $data = $form->getData();

            $email = (new Email())
                ->from('info@martin-skills.ch')
                ->to('martin.ivanenko@hotmail.com')
                ->subject('Kontaktformumlar wurde ausgefüllt!')
            ;
            $mailer->send($email, null);

            $this->addFlash('success', 'Your message has been sent');

            return $this->redirectToRoute('contact');
        }
        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
