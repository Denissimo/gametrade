<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Task;
use App\Entity\Transaction;
use App\Form\ManagerTaskEditFormType;
use App\Form\OperatorAccountAddFormType;
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

class OperatorController extends AbstractController
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

    public function buildTaskList(Request $request)
    {
        /** @var ?User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findByOperator($user);

        $accounts = $this->getDoctrine()
            ->getRepository(Account::class)
            ->findByOperator($user);

        $formAccountAdd = $this->createForm(OperatorAccountAddFormType::class);
        $formAccountAdd->handleRequest($request);
        if ($formAccountAdd->isSubmitted() && $formAccountAdd->isValid()) {
            /** @var Task $newAccaunt */
            $newAccaunt = $formAccountAdd->getData();
            $newAccaunt->setStatus(Account::STATUS_NEW)
                ->setOperator($user)
            ;
            $entityManager = $this->getDoctrine()
                ->getManager();
            $entityManager->persist($newAccaunt);
            $entityManager->flush();

            return $this->redirectToRoute('operator');
        }


        return $this->render('operator.html.twig', [
            'tasks' => $tasks,
            'accounts' => $accounts,
            'form_account_add' => $formAccountAdd->createView()
//            'form_task_offer' => $formTaskOffer->createView(),

        ]);
    }

    public function buildTaskEdit(Request $request)
    {
        /** @var ?User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $id = $request->get('id');
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);
        if ($user !== $task->getOperator()) {
            throw new AccessDeniedException('You are not operator of this task');
        }

        $formTaskEdit = $this->createForm(OperatorTaskEditFormType::class, $task, [
//            'action' => $this->generateUrl('task_save')
        ]);
        $formTaskAccept = $this->createForm(OperatorTaskAcceptFormType::class, $task);
        $formTaskReject = $this->createForm(OperatorTaskRejectFormType::class, $task);
        $formTaskDone = $this->createForm(OperatorTaskDoneFormType::class, $task);

        $formTaskAccept->handleRequest($request);
        $formTaskReject->handleRequest($request);
        $formTaskDone->handleRequest($request);

        if ($formTaskReject->isSubmitted() && $formTaskReject->isValid()) {
            $financeAccount = $task->getOperator()->getFinanceAccount();
            $penaltyAmount = $task->getType()->
                getRejectPenalty();

            $penalty = (new Transaction())->setFinance($financeAccount)
                ->setAmount($penaltyAmount)
                ->setType(Transaction::TYPE_DEBIT)
                ->setStatus(Transaction::STATUS_NEW)
            ;

            $task->setStatus(Task::STATUS_UNASSIGNED)
                ->setOperator(null);

            $entityManager  = $this->getDoctrine()
                ->getManager();
            $entityManager->persist($penalty);

            $entityManager->flush();


            return $this->redirectToRoute('operator');
        }

        if ($formTaskAccept->isSubmitted() && $formTaskAccept->isValid()) {
            $task->setStatus(Task::STATUS_IN_WORK);

            $entityManager  = $this->getDoctrine()
                ->getManager();

            $entityManager->flush();


            return $this->redirect($request->headers->get('referer'));
        }

        if ($formTaskDone->isSubmitted() && $formTaskDone->isValid()) {
                $task->setStatus(Task::STATUS_DONE);

                $this->getDoctrine()
                  ->getManager()
                    ->flush();

            return $this->redirectToRoute('operator');
        }

        if ($formTaskEdit->isSubmitted() && $formTaskEdit->isValid()) {
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('operator');
        }


        return $this->render('operator_task_edit.html.twig', [
            'task' => $task,
            'form_task_accept' => $formTaskAccept->createView(),
            'form_task_reject' => $formTaskReject->createView(),
            'form_task_done' => $formTaskDone->createView()
        ]);
    }

    public function buildAccountEdit(Request $request)
    {
        /** @var ?User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $id = $request->get('id');
        $account = $this->getDoctrine()
            ->getRepository(Account::class)
            ->find($id);
        if ($user !== $account->getOperator()) {
            throw new AccessDeniedException('You are not operator of this account');
        }

        $formAccountEdit = $this->createForm(OperatorAccountAddFormType::class, $account);

        return $this->render('operator_account_edit.html.twig', [
            'account' => $account,
            'form_account_edit' => $formAccountEdit->createView()
        ]);
    }
}