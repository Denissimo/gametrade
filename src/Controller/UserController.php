<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Credential;
use App\Entity\Game;
use App\Entity\Task;
use App\Entity\Transaction;
use App\Form\ManagerTaskEditFormType;
use App\Form\OperatorAccountAddFormType;
use App\Form\OperatorCredentialAddFormType;
use App\Form\OperatorTaskAcceptFormType;
use App\Form\OperatorTaskDoneFormType;
use App\Form\OperatorTaskEditFormType;
use App\Form\OperatorTaskRejectFormType;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @var  TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * ContentController constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(TokenStorageInterface $tokenStorage, UrlGeneratorInterface $urlGenerator)
    {
        $this->tokenStorage = $tokenStorage;
        $this->urlGenerator = $urlGenerator;
    }

    public function buildUser(Request $request)
    {
        /** @var ?User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $games = $this->getDoctrine()
            ->getRepository(Game::class)
            ->findAll();

        $accounts = $this->getDoctrine()
            ->getRepository(Account::class)
            ->findByOperator($user);

        return $this->render('user.html.twig', [
            'games' => $games,
//            'accounts' => $accounts,
//            'form_task_offer' => $formTaskOffer->createView(),

        ]);
    }

}