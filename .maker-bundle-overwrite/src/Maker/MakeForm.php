<?php

/*
 * This file is part of the Symfony MakerBundle package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\MakerBundle\Maker;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Renderer\FormTypeRenderer;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassDetails;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Validation;

/**
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Ryan Weaver <weaverryan@gmail.com>
 */
final class MakeForm extends AbstractMaker
{
    public function __construct(private DoctrineHelper $entityHelper, private FormTypeRenderer $formTypeRenderer)
    {
    }

    public static function getCommandName(): string
    {
        return 'make:form';
    }

    public static function getCommandDescription(): string
    {
        return 'Create a new form class';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
//            ->addArgument('module', InputArgument::OPTIONAL, \sprintf('Class module of the entity to create or update (e.g. <fg=yellow>%Form</>)', Str::asClassName(Str::getRandomTerm())))
            ->addArgument('module', InputArgument::OPTIONAL, \sprintf('Class module of the entity to create or update (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm())))
            ->addArgument('name', InputArgument::OPTIONAL, \sprintf('The name of the form class (e.g. <fg=yellow>%Form</>)', Str::asClassName(Str::getRandomTerm())))
            ->addArgument('bound-class', InputArgument::OPTIONAL, 'The name of Entity or fully qualified model class name that the new form will be bound to (empty for none)')
            ->setHelp($this->getHelpFileContents('MakeForm.txt'))
        ;
//        $inputConfig->setArgumentAsNonInteractive('module');
        $inputConfig->setArgumentAsNonInteractive('bound-class');
    }


    private function getProjectDir(): string
    {
        return dirname(__DIR__, 5).'/src'; // MakerBundle'ın konumuna göre src yolunu bulmak için
    }
    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
//        if (null === $input->getArgument('bound-class')) {
//            $argument = $command->getDefinition()->getArgument('bound-class');
//
//            $entities = $this->entityHelper->getEntitiesForAutocomplete();
//
//            $question = new Question($argument->getDescription());
//            $question->setValidator(fn ($answer) => Validator::existsOrNull($answer, $entities));
//            $question->setAutocompleterValues($entities);
//            $question->setMaxAttempts(3);
//
//            $input->setArgument('bound-class', $io->askQuestion($question));
//            $input->setArgument("module",$input->getArgument('module'));
//        }

        $moduleName = $input->getArgument('module');


        if (null === $moduleName) {
            $moduleName = $io->ask('Hangi module için form oluşturmak istiyorsun? (örn: User)');
            $input->setArgument('module', $moduleName);
        }

        // Modülün entity klasörü
        $entityDir = sprintf("src/Modules/{$moduleName}/Entity", $this->getProjectDir(), $moduleName);

        if (!is_dir($entityDir)) {
            throw new \RuntimeException(sprintf('Entity klasörü bulunamadı: %s', $entityDir));
        }

        $files = glob($entityDir.'/*.php');
        $entityNames = array_map(fn($file) => pathinfo($file, PATHINFO_FILENAME), $files);

        if (empty($entityNames)) {
            throw new \RuntimeException(sprintf('%s modülünde hiç entity bulunamadı!', $moduleName));
        }

        $boundClass = $input->getArgument('bound-class');
        if (null === $boundClass) {
            $boundClass = $io->choice('Hangi Entity için form oluşturmak istiyorsun?', $entityNames);
            $input->setArgument('bound-class', "App\\Modules\\$moduleName\\Entity\\$boundClass");
        }

        // Form class ismi yoksa otomatik verelim
        if (null === $input->getArgument('name')) {
            $defaultFormName = $boundClass.'Type';
            $formName = $io->ask('Form class adı', $defaultFormName);
            $input->setArgument('name', $formName);
        }
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $modulesName = $input->getArgument("module");

        $formClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('name'),
            "Modules\\{$modulesName}\\Form\\",
            'Form'
        );

        $formFields = ['field_name' => null];

        $boundClass = $input->getArgument('bound-class');
        $boundClassDetails = null;

        if (null !== $boundClass) {
            $boundClassDetails = $generator->createClassNameDetails(
                $boundClass,
                'Entity\\'
            );

            $doctrineEntityDetails = $this->entityHelper->createDoctrineDetails($boundClassDetails->getFullName());

            if (null !== $doctrineEntityDetails) {
                $formFields = $doctrineEntityDetails->getFormFields();
            } else {
                $classDetails = new ClassDetails($boundClassDetails->getFullName());
                $formFields = $classDetails->getFormFields();
            }
        }

        $this->formTypeRenderer->render(
            $formClassNameDetails,
            $formFields,
            $boundClassDetails
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);

        $io->text([
            'Next: Add fields to your form and start using it.',
            'Find the documentation at <fg=yellow>https://symfony.com/doc/current/forms.html</>',
        ]);
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        $dependencies->addClassDependency(
            AbstractType::class,
            // technically only form is needed, but the user will *probably* also want validation
            'form'
        );

        $dependencies->addClassDependency(
            Validation::class,
            'validator',
            // add as an optional dependency: the user *probably* wants validation
            false
        );

        $dependencies->addClassDependency(
            DoctrineBundle::class,
            'orm',
            false
        );
    }
}
