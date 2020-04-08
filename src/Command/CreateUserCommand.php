<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\SodiumPasswordEncoder;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CreateUserCommand
 * @package App\Command
 */
class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    protected EntityManagerInterface $em;
    protected ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;

        parent::__construct();
    }

    /**
     *
     */
    protected function configure(): void
    {
        $this->setDescription('Creates a new user.');
        $this->setHelp('This command allows you to create a user from terminal.');
        $this->addOption(
            'role', null,
            InputOption::VALUE_OPTIONAL,
            'User\s role',
            'ROLE_USER'
        );
        $this->addOption(
            'username', null,
            InputOption::VALUE_OPTIONAL,
            'User\'s nickname'
        );
        $this->addOption(
            'email', null,
            InputOption::VALUE_OPTIONAL,
            'User\'s email address'
        );
        $this->addOption(
            'password', null,
            InputOption::VALUE_OPTIONAL,
            'User\'s password (you should prefer interactive mode for this option)'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $user = new User();

        // Handling role
        $role = $input->getOption('role');
        $allowedRoles = ['ROLE_USER', 'ROLE_GM', 'ROLE_ADMIN'];
        if (is_string($role) && !in_array($role, $allowedRoles, true)) {
            throw new InvalidOptionException('Invalid role ' . $role . '. Possible values are: ' . implode(', ', $allowedRoles) . '.');
        } else if (!$role) {
            $question = new ChoiceQuestion(
                'Role: (defaults to ROLE_USER)',
                ['ROLE_USER', 'ROLE_GM', 'ROLE_ADMIN'],
                0
            );
            $question->setErrorMessage('Role %s is invalid.');

            $role = $helper->ask($input, $output, $question);
        }
        $user->addRole('ROLE_USER');
        switch ($role) {
            case 'ROLE_GM':
                $user->addRole('ROLE_GM');
                break;
            case 'ROLE_ADMIN':
                $user->addRole('ROLE_GM');
                $user->addRole('ROLE_ADMIN');
                break;
        }
        // ____

        // Handling username
        $username = $input->getOption('username');
        if (!$username) {
            $question = new Question('Username:');
            $question->setAutocompleterValues(['admin', 'user']);
            $username = $helper->ask($input, $output, $question);
        }
        $user->setUsername($username);
        // ____

        // Handling email
        $email = $input->getOption('email');
        if (!$email) {
            $question = new Question('Email address:');
            $question->setAutocompleterValues(['admin@akisroc.dev', 'user@akisroc.dev']);
            $email = $helper->ask($input, $output, $question);
        }
        $user->setEmail($email);
        // ____

        // Handling password
        $user->setSalt($user->generateSalt());
        $encoder = new SodiumPasswordEncoder();
        $plainPassword = $input->getOption('password');
        if (!$plainPassword) {
            $question = new Question('Password:');
            $question->setHidden(true);
            $plainPassword1 = $helper->ask($input, $output, $question);

            $question = new Question('Confirm password:');
            $question->setHidden(true);
            $plainPassword2 = $helper->ask($input, $output, $question);

            if ($plainPassword1 !== $plainPassword2) {
                throw new InvalidOptionException('Password and password confirmation did not match.');
            }

            $plainPassword = $plainPassword1;
        }
        $user->setPlainPassword($plainPassword);
        $user->setPassword(
            $encoder->encodePassword(
                $user->getPlainPassword(),
                $user->getSalt()
            )
        );
        // ____

        $errors = $this->validator->validate($user);
        if ($errors->count() > 0) {
            throw new InvalidOptionException($errors->get(0)->getMessage());
        }

        $user->eraseCredentials();
        $this->em->persist($user);
        $this->em->flush();

        return 0;
    }
}
