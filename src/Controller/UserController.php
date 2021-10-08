<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Basket;
use App\Entity\Credential;
use App\Entity\Game;
use App\Entity\Order;
use App\Entity\Tarif;
use App\Entity\Task;
use App\Entity\Transaction;
use App\Form\ManagerTaskEditFormType;
use App\Form\OperatorAccountAddFormType;
use App\Form\OperatorCredentialAddFormType;
use App\Form\OperatorTaskAcceptFormType;
use App\Form\OperatorTaskDoneFormType;
use App\Form\OperatorTaskEditFormType;
use App\Form\OperatorTaskRejectFormType;
use App\Form\UserAccountAddToBasketFormType;
use Ramsey\Uuid\UuidInterface;
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

        $order = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findByOwnerAndStatus($user);

        return $this->render('user.html.twig', [
            'games' => $games,
            'order' => $order,

        ]);
    }

    public function buildGameDetail(Request $request)
    {
        /** @var ?User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $id = $request->get('id');
        $game = $this->getDoctrine()
            ->getRepository(Game::class)
            ->find($id);

        $accounts = $this->getDoctrine()
            ->getRepository(Account::class)
            ->findByGameAndStatus($game);

        $order = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findByOwnerAndStatus($user);

        $accountForms = [];

        foreach ($accounts as $account) {
            $formBasket = $this->createForm(
                UserAccountAddToBasketFormType::class,
                $account);

            $formBasket->handleRequest($request);
            if ($formBasket->isSubmitted() && $formBasket->isValid()) {
                $this->addToBasket($account);

                return $this->redirect($request->headers->get('referer'));
            }
            $accountForms[$account->getStringId()] = $formBasket;
        }

        return $this->render('user_game_detail.html.twig', [
            'game' => $game,
            'order' => $order,
            'accounts' => $accounts,
            'forms' => $accountForms
        ]);
    }

    private function addToBasket(Account $account)
    {
        $entityManager = $this->getDoctrine()
            ->getManager();
        /** @var ?User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $order = $entityManager->getRepository(Order::class)
            ->findByOwnerAndStatus($user);
        if (!$order instanceof Order) {
            $order = (new Order())->setOwner($user)
                ->setStatus(Order::STATUS_NEW);
            $entityManager->persist($order);
        }

        $basket = (new Basket())->setOrder($order)
            ->setPrice($account->getPrice());
        $entityManager->persist($basket);
        $basketList = $order->getBaskets();
        $basketNumber = $basketList->count() + 1;
        $tarif = $entityManager->getRepository(Tarif::class)
            ->findByQuantity($basketNumber);
        $orderAmount = (int)$order->getAmount();
        $orderAmount += $account->getPrice();
        if ($tarif instanceof Tarif) {
            $orderAmount = 0;
            $basket->setPrice($tarif->getPriceAccount());
            $orderAmount += $tarif->getPriceAccount();
            foreach ($basketList as $basket) {
                $basket->setPrice($tarif->getPriceAccount());
                $orderAmount += $tarif->getPriceAccount();
            }
        }

        $order->setAmount($orderAmount)
            ->setTarif($tarif);
        $account->setBasket($basket)
            ->setStatus(Account::STATUS_RESERVED);

        $entityManager->flush();
    }

    public function buildBasket(Request $request)
    {
        $entityManager = $this->getDoctrine()
            ->getManager();
        /** @var ?User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $order = $entityManager->getRepository(Order::class)
            ->findByOwnerAndStatus($user);

        return $this->render('user_basket.html.twig', [
            'order' => $order
        ]);
    }
}