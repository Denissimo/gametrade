<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\ManagerTaskAddFormType;
use App\Form\ManagerTaskDismissFormType;
use App\Form\ManagerTaskEditFormType;
use App\Form\ManagerTaskOfferFormType;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use DateInterval;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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

    public function buildTaskList(Request $request)
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
        $formTaskAdd = $this->createForm(ManagerTaskAddFormType::class);
        $formTaskAdd->handleRequest($request);
        if ($formTaskAdd->isSubmitted() && $formTaskAdd->isValid()) {
            /** @var Task $newTask */
            $newTask = $formTaskAdd->getData();
            $hours = (int) $newTask->getType()
                ->getDefaultDuration();
            $deadLine = (new DateTime())->add(
                new DateInterval(
                    sprintf('PT%dH', $hours)
                )
            );
            $newTask->setStatus(Task::STATUS_UNASSIGNED)
                ->setHead($user)
                ->setDeadLine($deadLine)
            ;
            $entityManager = $this->getDoctrine()
                ->getManager();
            $entityManager->persist($newTask);
            $entityManager->flush();

            return $this->redirectToRoute('manager');
        }

        return $this->render('manager.html.twig', [
            'tasks' => $tasks,
            'form_task_add' => $formTaskAdd->createView(),
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
                Task::STATUS_IN_WORK,
                Task::STATUS_OFFERED,
                Task::STATUS_REJECTED
            ]):
                $formTaskStatus = $this->createForm(ManagerTaskDismissFormType::class, $task);
            break;

            case in_array($task->getStatus(), [
                Task::STATUS_UNASSIGNED,
                Task::STATUS_REJECTED
            ]):
                $formTaskStatus = $this->createForm(ManagerTaskOfferFormType::class, $task);
            break;

            default:
                $formTaskStatus = $this->createForm(ManagerTaskOfferFormType::class, $task);
        }
        $formTaskEdit->handleRequest($request);
        $formTaskStatus->handleRequest($request);

        if ($formTaskStatus->isSubmitted() && $formTaskStatus->isValid()) {
                $this->getDoctrine()
                  ->getManager()
                    ->flush();


            return $this->redirect($request->headers->get('referer'));
//            return $this->redirectToRoute('manager');
        }

        if ($formTaskEdit->isSubmitted() && $formTaskEdit->isValid()) {
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('manager');
        }


        return $this->render('manager_task_edit.html.twig', [
            'task' => $task,
            'form_task_edit' => $formTaskEdit->createView(),
            'form_task_status' => $formTaskStatus->createView(),
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