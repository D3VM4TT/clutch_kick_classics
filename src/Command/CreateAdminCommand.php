<?php

namespace App\Command;

use App\Entity\User\AdminUser;
use App\Service\OrderCompletionService;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AdminUserExampleFactory;
use Sylius\Bundle\UserBundle\Factory\UserWithEncoderFactory;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAdminCommand extends Command
{

    protected static $defaultDescription = 'Creates a new Admin user.';


    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UserWithEncoderFactory
     */
    private $userFactory;


    /**
     * @var Logger
     */
    private $logger;


    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, UserWithEncoderFactory $userWithEncoderFactory, Logger $logger)
    {
        $this->userFactory = $userWithEncoderFactory;
        $this->em = $em;
        $this->logger = $logger;
        parent::__construct();


    }


    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to create a user...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        /** @var AdminUserInterface $admin */
        $admin = $this->userFactory->createNew();
        $admin->setEmail('admin@admin.com');
        $admin->setPlainPassword('admin');
        $admin->setLocaleCode("en_US");
        $admin->setEnabled(true);
        $this->em->getRepository(AdminUser::class)->add($admin);
        $output->writeln([
            'Creating Admin User',
            '============',
            '',
        ]);

        return Command::SUCCESS;
    }
}
