<?php

namespace App\Command;

use App\Entity\FinanceAccount;
use App\Entity\TaskType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserGenerator extends Command
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    protected static $defaultName = 'data:users';

    private static $firstNames = [
        'Андрей',
        'Пётр',
        'Иван',
        'Яков',
        'Филипп',
        'Егор',
        'Матвей',
        'Фома',
        'Семён',
        'Илья',
        'Николай',
        'Ной',
        'Абрам',
        'Сергей',
        'Вениамин'
    ];

    private static $lastNames = [
        'Лимонов',
        'Грушин',
        'Смехов',
        'Шилов',
        'Липягин',
        'Уваров',
        'Косицын',
        'Асеевы',
        'Каримов',
        'Ветров',
        'Серов',
        'Кораблёв',
        'Витте',
        'Губанов',
        'Кузнецов'
    ];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct(null);
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function configure()
    {
        $this
            ->setName('data:users')
            ->setDescription('Users generate command')
            ->setHelp('Data generate command')
            ->addOption(
                'user_id_start',
                null,
                InputOption::VALUE_OPTIONAL,
                'User ID start',
                1
            )
            ->addOption(
                'users',
                null,
                InputOption::VALUE_OPTIONAL,
                'Number of users',
                8
            )
            ->addOption(
                'operator_id_start',
                null,
                InputOption::VALUE_OPTIONAL,
                'Operator ID start',
                1
            )
            ->addOption(
                'operators',
                null,
                InputOption::VALUE_OPTIONAL,
                'Number of operators',
                5
            )
            ->addOption(
                'manager_id_start',
                null,
                InputOption::VALUE_OPTIONAL,
                'Manager ID start',
                1
            )
            ->addOption(
                'managers',
                null,
                InputOption::VALUE_OPTIONAL,
                'Number of managers',
                2
            )
            ->addOption(
                'password',
                null,
                InputOption::VALUE_OPTIONAL,
                'Dafault password',
                123456
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //a:1:{i:0;s:12:"ROLE_MANAGER";}
        //a:1:{i:0;s:13:"ROLE_OPERATOR";}

        $userIdStart = $input->getOption('user_id_start');
        $operatorIdStart = $input->getOption('operator_id_start');
        $managerIdStart = $input->getOption('manager_id_start');

        $users = $input->getOption('users');
        $operators = $input->getOption('operators');
        $managers = $input->getOption('managers');

        $password = $input->getOption('password');

        $this->createUserList($output, 'manager', $password, $managers, $managerIdStart, ['ROLE_MANAGER']);
        $output->writeln('');

        $this->createUserList($output, 'operator', $password, $operators, $operatorIdStart, ['ROLE_OPERATOR']);
        $output->writeln('');

        $this->createUserList($output, 'user', $password, $users, $userIdStart);
        $output->writeln('');

        $output->writeln('Done!');

        return 0;
    }

    private function createUserList(
        OutputInterface $output,
        string $prefix,
        string $passwprd,
        int $number,
        int $idStart,
        array $roles = []
    )
    {
        for ($i = 0; $i < $number; $i++) {
            $username = sprintf('%s%d', $prefix, $i + $idStart);
            $user = $this->createUser($username, $passwprd, $roles);
            $output->writeln(
                sprintf(
                    'Created user ID%d: %s (%s %s)',
                    $user->getId(),
                    $user->getUsername(),
                    $user->getFirstname(),
                    $user->getLastname()
                )
            );
        }
    }

    /**
     * @param string $username
     * @param string $password
     * @param array $roles
     *
     * @return User
     */
    private function createUser(
        string $username,
        string $password,
        array $roles = []
    )
    {
        $user = new User();
        $encodedPassword = $this->passwordEncoder->encodePassword(
            $user,
            $password
        );

        $emailDummy = sprintf('%s@test.te', $username);
        $firstName = $this->chooseName(self::$firstNames);
        $lastName = $this->chooseName(self::$lastNames);

        $user->setEmail($emailDummy)
            ->setUsername($username)
            ->setPassword($encodedPassword)
            ->setEmailCanonical($emailDummy)
            ->setFirstname($firstName)
            ->setLastname($lastName)
            ->setRoles($roles)
            ->setEnabled(true); //активация автоматом

        $this->entityManager->persist($user);
        $randomAmount = rand(500000, 8000000);
        $financeAccount = (new FinanceAccount())
            ->setOwner($user)
            ->setAmount($randomAmount)
            ->setActive(true);
        $this->entityManager->persist($financeAccount);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param array $names
     *
     * @return string
     */
    private function chooseName(array $names): string
    {
        $number = mt_rand(0, count($names) - 1);

        return $names[$number];
    }
}