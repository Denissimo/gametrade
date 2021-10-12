<?php

namespace App\Command;

use App\Entity\FinanceAccount;
use App\Entity\Game;
use App\Entity\Tarif;
use App\Entity\TaskType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DataGenerator extends Command
{
    protected static $defaultName = 'data:fill';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct(null);
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('data:fill')
            ->setDescription('Data generate command')
            ->setHelp('Data generate command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->createTaskType('Buy','покупка с необходимыми параметрами',10000,4000,5000,5);
        $this->createTaskType('Clean','очистка/получение лимитов',8000,3000,4000,4);
        $this->createTaskType('Check','проверка работоспособности аккаунта (возможность входа, ресурсы)',7000,2000,3000,3);


        $this->createTarif('uno','Уно',1,100000,10000);
        $this->createTarif('duo','Дуо',2,80000,6000);
        $this->createTarif('tres','Трес',3,60000,0);
        $this->createTarif('quinto','Квинто',5,50000,0);

        $this->createGame('GTA','Grand Theft Auto',120000,'https://www.rockstargames.com/','серия мультиплатформенных компьютерных игр в жанре action-adventure, созданных и разрабатываемых главным образом британской компанией-разработчиком Rockstar North (бывшая DMA Design) и выпускаемых компанией Rockstar Games. В разработке ряда игр серии ключевые роли сыграли братья Дэн и Сэм Хаузеры, а также геймдизайнер Лесли Бензис. Игры серии, начиная с Grand Theft Auto III, принадлежат к числу самых высоко оценённых критиками и самых продаваемых игр в истории; серия является одной из самых коммерчески успешных медиафраншиз в индустрии компьютерных игр. На 2020 год в серии насчитывается одиннадцать игр на различных платформах.');
        $this->createGame('witcher','Ведьмак',140000,'https://en.cdprojektred.com/',' компьютерная ролевая игра, разработанная польской компанией CD Projekt RED по мотивам одноимённой серии романов польского писателя Анджея Сапковского. Релиз игры на платформе Windows состоялся 24 октября 2007 года — в России, 26 октября — в Европе и 30 октября 2007 года — в США[7]. В 2012 году вышла версия для OS X. Игрок управляет главным героем литературного мира Сапковского — ведьмаком Геральтом из Ривии. Действие игры разворачивается после событий саги о ведьмаке — еле оставшись в живых, Геральт впадает в амнезию и вынужден заново учить своё ремесло. Игрок восстанавливает потерянный опыт главного героя, сражаясь с человеческими противниками и монстрами. В то же время перед ним ставится моральный выбор, от которого зависит дальнейшая судьба игрового мира. Игра достоверно отражает мрачную атмосферу вселенной романов, однако её сюжет не получил официальной поддержки Сапковского.');

        $output->writeln('Done!');

        return 0;
    }

    /**
     * @param string $code
     * @param string $name
     * @param int $award
     * @param int $overduePenalty
     * @param int $rejectPenalty
     * @param int $defaultDuration
     *
     * @return TaskType
     */
    private function createTaskType(
        string $code,
        string $name,
        int $award,
        int $overduePenalty,
        int $rejectPenalty,
        int $defaultDuration
    )
    {
        $taskType = (new TaskType())->setCode($code)
            ->setName($name)
            ->setAward($award)
            ->setOverduePenalty($overduePenalty)
            ->setRejectPenalty($rejectPenalty)
            ->setDefaultDuration($defaultDuration);

        $this->entityManager->persist($taskType);
        $this->entityManager->flush();

        return $taskType;
    }

    private function createTarif(
        string $code,
        string $name,
        int $numberOfAccount,
        int $priceAcoount,
        int $priceChangeAccount
    )
    {
        $tarif = (new Tarif())->setCode($code)
            ->setName($name)
            ->setNumberOfAccounts($numberOfAccount)
            ->setPriceAccount($priceAcoount)
            ->setPriceChangeAccount($priceChangeAccount);

        $this->entityManager->persist($tarif);
        $this->entityManager->flush();

        return $tarif;
    }

    private function createGame(
        string $code,
        string $name,
        int $price,
        string $url,
        string $description
    )
    {
        $game = (new Game())->setCode($code)
            ->setName($name)
            ->setPrice($price)
            ->setUrl($url)
            ->setDescription($description);

        $this->entityManager->persist($game);
        $this->entityManager->flush();

        return $game;
    }
}