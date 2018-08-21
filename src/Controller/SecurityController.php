<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Form\User\UserType;
use App\Form\User\FirstUserType;
use App\Form\User\UserPasswordType;

use App\Entity\User;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $auth)
    {
        $em = $this->getDoctrine()->getManager();
        
        //show only when no users in db (FirstStart)
        if (count($em->getRepository('App:User')->findAll()) == 0) { 
            return $this->redirectToRoute('addFirstUser', array(
            ));            
        }
        $error = $auth->getLastAuthenticationError();
            
        $lastUsername = $auth->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        
    }
    
    /**
     * @Route("/addFirstUser", name="addFirstUser")
     */
    public function addFirstUser(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        //show only when no users in db (FirstStart)
        if (count($em->getRepository('App:User')->findAll()) === 0) {            
            $user = new User();
            //create a form
            $form = $this->createForm(FirstUserType::class, $user, array());
    
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                //set active user on add
                $user->setActive(1);
                $em->persist($user);
                $em->flush();
    
                $success = 'User added successfully';
                
                return $this->redirectToRoute('login', array(
                    'success' => $success,
                ));
            }
    
            return $this->render('security/addUser.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('login', array(
            ));
        }
        
        
    }
    
    /**
     * @Route("/manageUsers", name="manageUsers")
     */
    public function manageUsers()
    {
        $em = $this->getDoctrine()->getManager();

        $users =  $em->getRepository('App:User')->findAll();
        
        return $this->render('security/manageUsers.html.twig', [
            'users' => $users,
        ]);
    }
        
    /**
     * @Route("/profile", name="profile")
     */
    public function profile()
    {
        $em = $this->getDoctrine()->getManager();
        
        //get Id of logged user
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        return $this->render('security/profile.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/editUser/{id}", name="user.edit")
     * @ParamConverter("user", class="App\Entity\User")
     * @param User $user
     */
    public function editUser(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        //create a form
        $form = $this->createForm(UserType::class, $user, array());

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            $success = 'Changed successfully';

            return $this->redirectToRoute('manageUsers', array(
                'success' => $success,
            ));
        }

        return $this->render('security/editUser.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/addUser", name="user.add")
     */
    public function addUser(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = new User();
        //create a form
        $form = $this->createForm(UserType::class, $user, array());

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //set active user on add
            $user->setActive(1);
            $em->persist($user);
            $em->flush();

            $success = 'User added successfully';

            return $this->redirectToRoute('manageUsers', array(
                'success' => $success,
            ));
        }

        return $this->render('security/addUser.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/changePassword/{id}", name="user.changePassword")
     * @ParamConverter("user", class="App\Entity\User")
     * @param User $user
     */
    public function changePassword(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        //create a form
        $form = $this->createForm(UserPasswordType::class, $user, array());

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            $success = 'Changed successfully';

            return $this->redirectToRoute('mainPage', array(
                'success' => $success,
            ));
        }

        return $this->render('security/changePassword.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/changeStatus/{id}", name="user.changeStatus")
     * @ParamConverter("user", class="App\Entity\User")
     * @param User $user
     */
    public function changeStatus(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $success    = null;
        $error      = null;
      
        if ($user->getActive()) {
            $user->setActive(false);
            $error = 'Account '.$user->getUsername().' Deactivated';
        } else {            
            $user->setActive(true);
            $success = 'Account '.$user->getUsername().' Activated';
        }
        
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('manageUsers', array(
            'success' => $success,
            'error' => $error,
        ));
       
    }
}
