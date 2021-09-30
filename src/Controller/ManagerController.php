<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\ManagerTaskDismissFormType;
use App\Form\ManagerTaskEditFormType;
use App\Form\ManagerTaskOfferFormType;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Entity\User;

class ManagerController extends AbstractController
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

    public function buildManager(Request $request)
    {
        /** @var ?User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findByManager($user);

        $operators = $this->getDoctrine()
            ->getRepository(User::class)
            ->loadByRole(User::ROLE_OPERATOR);


        $formTaskOffer = $this->createForm(ManagerTaskOfferFormType::class);
        $all = $request->request->all();
//        if ($formTaskOffer->isSubmitted() && $formTaskOffer->isValid()) {
//
//        }
//        $formTaskOffer->handleRequest($request);

        return $this->render('manager.html.twig', [
            'tasks' => $tasks,
//            'form_task_offer' => $formTaskOffer->createView(),
            'operators' => $operators
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
        if ($user !== $task->getHead()) {
            throw new AccessDeniedException('You are not head of this task');
        }
        $formTaskEdit = $this->createForm(ManagerTaskEditFormType::class, $task, [
//            'action' => $this->generateUrl('task_save')
        ]);
        switch (true) {
            case in_array($task->getStatus(), [
                Task::STATUS_ACCEPTED,
                Task::STATUS_IN_WORK,
                Task::STATUS_OFFERED,
                Task::STATUS_REJECTED
            ]):
                $formTaskStatus = $this->createForm(ManagerTaskDismissFormType::class, $task);
            break;

            case in_array($task->getStatus(), [
                Task::STATUS_NEW,
                Task::STATUS_REJECTED
            ]):
                $formTaskStatus = $this->createForm(ManagerTaskOfferFormType::class, $task);
            break;

            default:
                $formTaskStatus = $this->createForm(ManagerTaskOfferFormType::class, $task);
        }
        $formTaskEdit->handleRequest($request);
        $formTaskStatus->handleRequest($request);

        if (
        ($formTaskEdit->isSubmitted() && $formTaskEdit->isValid()) ||
        ($formTaskStatus->isSubmitted() && $formTaskStatus->isValid())
        ) {
            $this->getDoctrine()
                ->getManager()
                ->flush();

            $data = $formTaskStatus->getData();

            return $this->redirectToRoute('manager');
        }
        $operators = $this->getDoctrine()
            ->getRepository(User::class)
            ->loadByRole(User::ROLE_OPERATOR);

        return $this->render('task_edit.html.twig', [
            'task' => $task,
            'form_task_edit' => $formTaskEdit->createView(),
            'form_task_status' => $formTaskStatus->createView(),
            'operators' => $operators
        ]);
    }

    public function saveTask(Request $request)
    {
        $formTaskEdit = $this->createForm(ManagerTaskEditFormType::class);

        $formTaskEdit->handleRequest($request);
        $data = $formTaskEdit->getData();
        $this->getDoctrine()
            ->getManager()
            ->flush();

        return $this->redirect($request->headers->get('referer'));
    }

}