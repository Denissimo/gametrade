<?php

namespace App\Controller;

use App\Entity\Task;
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

class ContentController extends AbstractController
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


    public function buildMain()
    {
        return $this->render('main.html.twig', []);
    }

    public function buildXlsxReport(KernelInterface $kernel)
    {
        $user = $this->tokenStorage->getToken()->getUser();
//        $accurals = $this->buildAccurals($user);

//        $spreadsheet = new Spreadsheet();

        $children = 0;
        $awards = 0;
        $amountUsd = 0;
        $amountBtc = 0;
        $amountEth = 0;

//        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
//        $sheet = $spreadsheet->getActiveSheet();
//        $sheet->setCellValue('A1', 'Level');
//        $sheet->setCellValue('B1', 'Children');
//        $sheet->setCellValue('C1', 'Awards');
//        $sheet->setCellValue('D1', 'Amount USD');
//        $sheet->setCellValue('E1', 'Amount BTC');
//        $sheet->setCellValue('F1', 'Amount ETH');
    }

//    public function buildAccount(string $urlSelf)
    public function buildAccount()
    {
        /** @var ?User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user instanceof User) {
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }

        switch (true) {
            case $user->hasRole(User::ROLE_ADMIN) || $user->hasRole(User::ROLE_SUPER_ADMIN):
                return new RedirectResponse($this->urlGenerator->generate('sonata_admin_dashboard'));

                break;

            case $user->hasRole(User::ROLE_MANAGER):
                return new RedirectResponse($this->urlGenerator->generate('manager'));

                break;

            case $user->hasRole(User::ROLE_OPERATOR):
                return new RedirectResponse($this->urlGenerator->generate('operator'));

                break;

            default:
                return new RedirectResponse($this->urlGenerator->generate('user'));
        }
    }

    public function buildUser()
    {
        return $this->render('user.html.twig', []);
    }

    public function buildOperator()
    {
        return $this->render('operator.html.twig', []);
    }
}