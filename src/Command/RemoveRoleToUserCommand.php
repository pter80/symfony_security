<?php

// src/Command/RemoveRoleToUserCommand.php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;



// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:remove-role-to-user')]
class RemoveRoleToUserCommand extends Command
{
    protected $userRepository;
    protected $em;
    public function __construct(UserRepository $userRepository,EntityManagerInterFace $em )
    {
        $this->userRepository=$userRepository;
        $this->em=$em;
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            // the command description shown when running "php bin/console list"
            ->setDescription('Retire un role à un utilisateur')
            // the command help shown when running the command with the "--help" option
            ->setHelp("Cette commande permet de supptimer un role à un utilisateur...")
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $output->writeln([
            'Remove Role',
            '============',
            '',
        ]);
    
        // retrieve the argument value using getArgument()
        $question = new Question("Saisir l'Id : ", 'UserId');
        $userId = $helper->ask($input, $output, $question);
        $output->writeln("ID : ".$userId);

        $question = new Question('Saisir le role : ', 'Role');
        $role = $helper->ask($input, $output, $question);
        $output->writeln('Role : '.$role);

        $user=$this->userRepository->find($userId);
        if ($user) {
            $output->writeln('Utilisateur : '.$user->getEmail());
            $roles = $user->getRoles();

            if (in_array($role, $roles)) {
                $key = array_search($role,$roles);
                unset($roles[$key]);
                $user->setRoles($roles);
    
                // Persist changes
                $this->em->persist($user);
                $this->em->flush();
            }
        }
        /*
        if (!$user) {
            return new Response('User not found', Response::HTTP_NOT_FOUND);
        }
        // Get current roles and add the new one if it's not already assigned
        

        return new Response('Role move successfully');
        */
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}