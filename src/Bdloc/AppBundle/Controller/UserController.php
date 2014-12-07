<?php

namespace Bdloc\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Response;

use Bdloc\AppBundle\Form\UserType;
use Bdloc\AppBundle\Form\InscriptionType;
use Bdloc\AppBundle\Form\LostPasswordType;
use Bdloc\AppBundle\Form\NewPasswordType;
use Bdloc\AppBundle\Form\ChangePasswordType;

use Bdloc\AppBundle\Entity\User;
use Bdloc\AppBundle\Entity\LostPassword;
use Bdloc\AppBundle\Entity\NewPassword;
use Bdloc\AppBundle\Entity\ChangePassword;

use Bdloc\AppBundle\Util\StringHelper;

class UserController extends Controller
{
    /**
     * @Route("/inscription")
     */
    public function registerAction(Request $request)
    {
    	$params = array();
    	$user = new User();

    	$inscriptionForm = $this->createForm(new InscriptionType(), $user);

        $inscriptionForm->handleRequest($request);

        if ($inscriptionForm->isValid()) {
            // Gestion du salt
            $stringHelper = new StringHelper();
            $user->setSalt($stringHelper->getSaltToken());

            // Gestion du token
            $user->setToken($stringHelper->getSaltToken());

            // Crypté les passwords
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);

            // On indique le rôle 
            $user->setRoles(array("ROLE_USER"));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            if($user) {
                $token = $user->getToken();

                $message = \Swift_Message::newInstance()
                    ->setSubject('Confirmation d\'inscription')
                    ->setFrom('olivier.andre@outlook.com')
                    ->setTo($user->getEmail())
                    ->setContentType('text/html')
                    ->setBody($this->generateUrl('bdloc_app_user_confirmaccount', array(
                        'token' => $token,
                        'email' => $user->getEmail())
                    , true));
                $send = $this->get('mailer')->send($message);

            }

            // Auto-login (Attention au nom du firewall : dans ce cas, c'est secured_area cf: security.yml)
            $token = new UsernamePasswordToken($user, $user->getPassword(), "secured_area", $user->getRoles());
            $this->get("security.context")->setToken($token);

            // Fire the login event
            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

            // On redirige vers une page d'information
            return $this->redirect($this->generateUrl('bdloc_app_default_home', $params));

        }

    	$params['inscriptionForm'] = $inscriptionForm->createView();

        return $this->render("user/register.html.twig", $params);
    }

     /**
     * @Route("/confirm/inscription/{token}/{email}")
     */
    public function confirmAccountAction(Request $request, $token, $email) {
        $params = array();
        $user = new User();
        $doctrine = $this->getDoctrine();

        $userRepository = $doctrine->getRepository('BdlocAppBundle:User');

        $user = $userRepository->findOneByEmail($email);

        $tokenUser = $user->getToken();

        if($token === $tokenUser) {
            $stringHelper = new StringHelper();

            $user->setIsEnabled(true);
            // Nouveau token
            $user->setToken($stringHelper->getSaltToken());

            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            $message = "Votre compte bien activé. Merci !";
            $params['confirmInscription'] = $this->get('session')->getFlashBag()->add(
                'message',
                $message
            );
            $params['titleMessage'] = "Félicitations ! Compte activé";
            $url = 'message/success.html.twig';           

        } else {
            $message = "La demande d'inscription a bien été effectuée.";
            $params['error'] = $this->get('session')->getFlashBag()->add(
                'message',
                $message
            );

            $url = 'message/danger.html.twig';
        }

        return $this->render($url, $params);
    }

    /**
     * @Route("/mot-de-passe-perdu")
     */
    public function lostPasswordAction(Request $request) {
        $params = array();
        $url = 'security/lost_password.html.twig';

        $lostPassword = new LostPassword();

        $lostForm = $this->createForm(new LostPasswordType(), $lostPassword);
        $lostForm->handleRequest($request);

        if ($lostForm->isValid()) {
            $repoUser = $this->getDoctrine()->getRepository('BdlocAppBundle:User');
            $user = new User();

            $user = $repoUser->findOneByEmail($lostPassword->getEmail());

            if($user) {

               $message = \Swift_Message::newInstance()
                    ->setSubject('Bdloc - Mot de passe perdu')
                    ->setFrom('olivier.andre@outlook.com')
                    ->setTo($user->getEmail())
                    ->setContentType('text/html')
                    ->setBody($this->generateUrl('bdloc_app_user_newpassword', array(
                        'token' => $user->getToken(),
                        'email' => $user->getEmail())
                    , true));
                $send = $this->get('mailer')->send($message);

                $message = "Un nouveau lien pour réinitialiser votre mot de passe vient de vous être transmis :-)";
                $params['error'] = $this->get('session')->getFlashBag()->add(
                'message',
                $message
            );
                $params['titleMessage'] = "Demande de réinitialisation de mot de passe";
                $url = 'messages/success.html.twig';
                
            } else {
                $message = "Votre email est inexistant de notre base !!!";
                $params['error'] =  $this->get('session')->getFlashBag()->add('message', $message);
            }

        } 

        $params['lostForm'] = $lostForm->createView();

        return $this->render($url, $params);
    }

    /**
     * @Route("/reinitialiser/{token}/{email}")
     */
    public function newPasswordAction(Request $request, $token, $email) {
        $params = array();
        $url = 'security/new_password.html.twig';
        $new = new NewPassword();

        $repository = $this->getDoctrine()->getRepository('BdlocAppBundle:User');
        $user = new User();
        $user = $repository->findOneByToken($token);      

        if($user && $user->getEmail() === $email) {
            $newForm = $this->createForm(new NewPasswordType(), $new);
            $newForm->handleRequest($request);

            if($newForm->isValid()) {
                // Gestion du salt
                $stringHelper = new StringHelper();
                $user->setSalt($stringHelper->getSaltToken());

                // Gestion du token
                $user->setToken($stringHelper->getSaltToken());

                // Crypté les passwords
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($new->getPassword(), $user->getSalt());
                $user->setPassword($password);

                // On indique le rôle 
                $user->setRoles(array("ROLE_USER"));

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $message = \Swift_Message::newInstance()
                    ->setSubject('Bdloc - Mot de passe réinitialisé')
                    ->setFrom('olivier.andre@outlook.com')
                    ->setTo($user->getEmail())
                    ->setContentType('text/html')
                    ->setBody('Votre mot de passe a bien été réinitialisé');
                $send = $this->get('mailer')->send($message);

                // Auto-login (Attention au nom du firewall : dans ce cas, c'est secured_area cf: security.yml)
                $token = new UsernamePasswordToken($user, $user->getPassword(), "secured_area", $user->getRoles());
                $this->get("security.context")->setToken($token);

                // Fire the login event
                $event = new InteractiveLoginEvent($request, $token);
                $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

                $message = "Félicitations !!! Votre mot de passe a été modifié :-)";
                $params['error'] = $this->get('session')->getFlashBag()->add('message', $message);
                $params['titleMessage'] = "Félicitations :-))";
                $url = 'messages/success.html.twig';
            }

            $params['newPasswordForm'] = $newForm->createView();

        } else {
            $message = "Un problème est intervenue. Une modification est déjà intervenue sur votre compte ou votre email est incorrect.";
            $params['error'] = $this->get('session')->getFlashBag()->add('message', $message);
            $url = 'messages/danger.html.twig';
        }

        return $this->render($url, $params);
    }

    /**
     * @Route("/compte")
     */
    public function accountAction(Request $request) {
        $params = array();

        $user = new User();
        $user = $this->getUser();

        $userForm = $this->createForm(new UserType(), $user);
        $userForm->handleRequest($request);

        if($userForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $message = "Votre compte est bien à jour";
            $params['error'] = $this->get('session')->getFlashBag()->add(
                'message',
                $message
            );
        }

        $params['userForm'] = $userForm->createView();

        return $this->render('user/account.html.twig', $params);

    }

    /**
     * @Route("/compte/change-mot-de-passe")
     */
    public function updatePasswordAction(Request $request) {
        $params = array();

        $change = new ChangePassword();
        $user = $this->getUser();

        $changePasswordForm = $this->createForm(new ChangePasswordType(), $change);
        $changePasswordForm->handleRequest($request);

            
        if($changePasswordForm->isValid()) {
                
            
        }

        $errors = $changePasswordForm->getErrors();
        $params['errors'] = $errors;

        $params['changePasswordForm'] = $changePasswordForm->createView();

        return $this->render('user/update_password.html.twig', $params);


    }

}