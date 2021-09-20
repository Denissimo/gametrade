<?php

namespace App\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ContentController extends AbstractController
{
    /**
     * @var  TokenStorageInterface
     */
    private $tokenStorage;


    /**
     * ContentController constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
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

}