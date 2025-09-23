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

use App\Shared\Controller\BaseAbstractApiController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\Common\CanGenerateTestsTrait;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassSource\Model\ClassData;
use Symfony\Bundle\MakerBundle\Util\PhpCompatUtil;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Ryan Weaver <weaverryan@gmail.com>
 */
final class MakeController extends AbstractMaker
{
    use CanGenerateTestsTrait;

    private bool $isInvokable;
    private ClassData $controllerClassData;
    private bool $usesTwigTemplate;
    private string $twigTemplatePath;

    const TARGET_DIR = "Modules";

    public function __construct(private ?PhpCompatUtil $phpCompatUtil = null)
    {
        if (null !== $phpCompatUtil) {
            @trigger_deprecation(
                'symfony/maker-bundle',
                '1.55.0',
                \sprintf('Initializing MakeCommand while providing an instance of "%s" is deprecated. The $phpCompatUtil param will be removed in a future version.', PhpCompatUtil::class)
            );
        }
    }

    public static function getCommandName(): string
    {
        return 'make:controller';
    }

    public static function getCommandDescription(): string
    {
        return 'Create a new controller class';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument('module', InputArgument::OPTIONAL, \sprintf('Choose a module for your controller class (e.g. <fg=yellow>%sController</>)', Str::asClassName(Str::getRandomTerm())))
            ->addArgument('controller-class', InputArgument::OPTIONAL, \sprintf('Choose a name for your controller class (e.g. <fg=yellow>%sController</>)', Str::asClassName(Str::getRandomTerm())))
            ->addOption('no-template', null, InputOption::VALUE_OPTIONAL, 'Use this option to disable template generation',1)
            ->addOption('invokable', 'i', InputOption::VALUE_NONE, 'Use this option to create an invokable controller')
            ->setHelp($this->getHelpFileContents('MakeController.txt'))
        ;

        $this->configureCommandWithTestsOption($command);
    }

//    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
//    {
//        $this->usesTwigTemplate = $this->isTwigInstalled() && !$input->getOption('no-template');
//        $this->isInvokable = (bool) $input->getOption('invokable');
//
//        $moduleName = $input->getArgument('module');
//        $controllerClass = $input->getArgument('controller-class');
//        $controllerClassName = \sprintf(self::TARGET_DIR.'\%s', $controllerClass);
//
//        // If the class name provided is absolute, we do not assume it will live in src/Controller
//        // e.g. src/Custom/Location/For/MyController instead of src/Controller/MyController
//        if ($isAbsoluteNamespace = '\\' === $controllerClass[0]) {
//            $controllerClassName = substr($controllerClass, 1);
//        }
//
//        if (!$moduleName) {
//            $moduleName = $io->ask('Module adını gir (örn: User)');
//            $input->setArgument('module', $moduleName);
//        }
//
//        // Namespace'i modüle göre ayarla
//        $controllerClassName = sprintf(
//            "Modules\\%s\\Controller\\%s",
//            $moduleName,
//            $controllerClass
//        );
//
//
////        dd($controllerClass,$controllerClassName,$this->usesTwigTemplate);
//        $this->controllerClassData = ClassData::create(
//            class: $controllerClassName,
//            suffix: 'Controller',
//            extendsClass: AbstractController::class,
//            useStatements: [
//                $this->usesTwigTemplate ? Response::class : JsonResponse::class,
//                Route::class,
//            ]
//        );
//
//        // Again if the class name is absolute, lets not make assumptions about where the Twig template
//        // should live. E.g. templates/custom/location/for/my_controller.html.twig instead of
//        // templates/my/controller.html.twig. We do however remove the root_namespace prefix in either case
//        // so we don't end up with templates/app/my/controller.html.twig
//        $templateName = $isAbsoluteNamespace ?
//            $this->controllerClassData->getFullClassName(withoutRootNamespace: true, withoutSuffix: true) :
//            $this->controllerClassData->getClassName(relative: true, withoutSuffix: true)
//        ;
//
//        // Convert the Twig template name into a file path where it will be generated.
//        $this->twigTemplatePath = \sprintf('%s%s', Str::asFilePath($templateName), $this->isInvokable ? '.html.twig' : '/index.html.twig');
//
//        $this->interactSetGenerateTests($input, $io);
//    }


    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        $this->usesTwigTemplate = $this->isTwigInstalled() && !$input->getOption('no-template');
        $this->isInvokable = (bool) $input->getOption('invokable');

        $moduleName = $input->getArgument('module');
        if (!$moduleName) {
            $moduleName = $io->ask('Module adını gir (örn: User)');
            $input->setArgument('module', $moduleName);
        }

        $controllerClass = $input->getArgument('controller-class');
        if (!$controllerClass) {
            $controllerClass = $io->ask(sprintf('Controller sınıf adını gir (örn: %sController)', Str::asClassName(Str::getRandomTerm())));
            $input->setArgument('controller-class', $controllerClass);
        }

        // controller FQCN (App kökü otomatik eklenecek ClassData tarafından)
        $controllerClassName = sprintf('Modules\\%s\\Controller\\%s', Str::asClassName($moduleName), Str::asClassName($controllerClass));

        // Burada extendsClass bırakıyoruz null — generate() içinde shared parent ile yeniden oluşturacağız.
        $this->controllerClassData = ClassData::create(
            class: $controllerClassName,
            suffix: 'Controller',
            extendsClass: null,
            isEntity: false
        );

        // Use statements (Response veya JsonResponse ve Route) ekle
        $this->controllerClassData->addUseStatement($this->usesTwigTemplate ? Response::class : JsonResponse::class);
        $this->controllerClassData->addUseStatement(Route::class);

        $templateName = $this->controllerClassData->getClassName(relative: true, withoutSuffix: true);
        $this->twigTemplatePath = sprintf('%s%s', Str::asFilePath($templateName), $this->isInvokable ? '.html.twig' : '/index.html.twig');

        $this->interactSetGenerateTests($input, $io);
    }

//    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
//    {
//        $controllerPath = $generator->generateClassFromClassData($this->controllerClassData, 'controller/Controller.tpl.php', [
//            'route_path' => Str::asRoutePath($this->controllerClassData->getClassName(relative: true, withoutSuffix: true)),
//            'route_name' => Str::AsRouteName($this->controllerClassData->getClassName(relative: true, withoutSuffix: true)),
//            'method_name' => $this->isInvokable ? '__invoke' : 'index',
//            'with_template' => $this->usesTwigTemplate,
//            'template_name' => $this->twigTemplatePath,
//        ], true);
//        $this->controllerClassData->setIsFinal(false);
//
//        if ($this->usesTwigTemplate) {
//            $generator->generateTemplate(
//                $this->twigTemplatePath,
//                'controller/twig_template.tpl.php',
//                [
//                    'controller_path' => $controllerPath,
//                    'root_directory' => $generator->getRootDirectory(),
//                    'class_name' => $this->controllerClassData->getClassName(),
//                ]
//            );
//        }
//
//        if ($this->shouldGenerateTests()) {
//            $testClassData = ClassData::create(
//                class: \sprintf('Tests\Controller\%s', $this->controllerClassData->getClassName(relative: true, withoutSuffix: true)),
//                suffix: 'ControllerTest',
//                extendsClass: WebTestCase::class,
//            );
//
//            $generator->generateClassFromClassData($testClassData, 'controller/test/Test.tpl.php', [
//                'route_path' => Str::asRoutePath($this->controllerClassData->getClassName(relative: true, withoutSuffix: true)),
//            ]);
//
//            if (!class_exists(WebTestCase::class)) {
//                $io->caution('You\'ll need to install the `symfony/test-pack` to execute the tests for your new controller.');
//            }
//        }
//
//        $generator->writeChanges();
//
//        $this->writeSuccessMessage($io);
//        $io->text('Next: Open your new controller class and add some pages!');
//    }
//    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
//    {
////        En iyisi bu
//        $moduleName      = $input->getArgument('module');
//        $controllerClass = $input->getArgument('controller-class');
//
//        // Shared klasörü için path & namespace
//        $sharedNamespace = sprintf("Modules\\%s\\Shared", $moduleName);
//        $sharedClassName = sprintf("Abstract%sController", $moduleName);
//
//
//        $sharedFqcn      = sprintf("%s\\%s", $sharedNamespace, $sharedClassName);
//        $sharedFilePath  = sprintf(
//            "%s/src/Modules/%s/Shared/%s.php",
//            $generator->getRootDirectory(),
//            $moduleName,
//            $sharedClassName
//        );
//
//        /**
//         * ✅ Shared Abstract Controller sadece yoksa oluştur
//         */
//        if (!file_exists($sharedFilePath)) {
//            $sharedClassData = ClassData::create(
//                class: $sharedFqcn,
//                suffix: null,
//                extendsClass: AbstractController::class,
//                isEntity: false
//            );
//
////            $sharedClassData->addUseStatement(BaseAbstractApiController::class);
//            $sharedClassData->addUseStatement(AbstractController::class);
//            $sharedClassData->addUseStatement(JsonResponse::class);
//            $sharedClassData->addUseStatement(Route::class);
//
//            $generator->generateClassFromClassData(
//                $sharedClassData,
//                'controller/SharedController.tpl.php'
//            );
//            $sharedClassData->setIsFinal(false);
//            $io->success(sprintf('Created shared abstract controller: %s', $sharedFilePath));
//        } else {
//            $io->writeln(sprintf(
//                '<comment>Shared base controller already exists, skipping: %s</comment>',
//                $sharedFilePath
//            ));
//        }
//
//        /**
//         * ✅ Normal controller
//         * Burada parent_class olarak Shared controller veriyoruz.
//         */
//        $controllerPath = $generator->generateClassFromClassData(
//            $this->controllerClassData,
//            'controller/Controller.tpl.php',
//            [
//                'route_path'    => Str::asRoutePath(
//                    $this->controllerClassData->getClassName(relative: true, withoutSuffix: true)
//                ),
//                'route_name'    => Str::AsRouteName(
//                    $this->controllerClassData->getClassName(relative: true, withoutSuffix: true)
//                ),
//                'method_name'   => $this->isInvokable ? '__invoke' : 'index',
//                'with_template' => $this->usesTwigTemplate,
//                'template_name' => $this->twigTemplatePath,
//                'parent_class'  => $sharedClassName, // extend edilecek base
//            ],
//            true
//        );
//
//        $this->controllerClassData->setIsFinal(false);
//
//        /**
//         * ✅ Twig template
//         */
//        if ($this->usesTwigTemplate) {
//            $generator->generateTemplate(
//                $this->twigTemplatePath,
//                'controller/twig_template.tpl.php',
//                [
//                    'controller_path' => $controllerPath,
//                    'root_directory'  => $generator->getRootDirectory(),
//                    'class_name'      => $this->controllerClassData->getClassName(),
//                ]
//            );
//        }
//
//        /**
//         * ✅ Test class
//         */
//        if ($this->shouldGenerateTests()) {
//            $testClassData = ClassData::create(
//                class: sprintf(
//                    'Tests\Controller\%s',
//                    $this->controllerClassData->getClassName(relative: true, withoutSuffix: true)
//                ),
//                suffix: 'ControllerTest',
//                extendsClass: WebTestCase::class,
//            );
//
//            $generator->generateClassFromClassData(
//                $testClassData,
//                'controller/test/Test.tpl.php',
//                [
//                    'route_path' => Str::asRoutePath(
//                        $this->controllerClassData->getClassName(relative: true, withoutSuffix: true)
//                    ),
//                ]
//            );
//
//            if (!class_exists(WebTestCase::class)) {
//                $io->caution(
//                    'You\'ll need to install the `symfony/test-pack` to execute the tests for your new controller.'
//                );
//            }
//        }
//
//        $generator->writeChanges();
//
//        $this->writeSuccessMessage($io);
//        $io->text('Next: Open your new controller class and add some pages!');
//    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        // normalize module & controller isimleri (isteğe bağlı, güvenlik için)
        $moduleNameRaw      = $input->getArgument('module');
        $controllerRaw      = $input->getArgument('controller-class');

        $moduleName = Str::asClassName($moduleNameRaw);
        $controllerName = Str::asClassName($controllerRaw);

        // Shared abstract sınıfın FQCN ve beklenen dosya yolu
        $sharedClassName = 'Abstract' . $moduleName . 'Controller';
        $sharedFqcn = "App\\Modules\\{$moduleName}\\Shared\\{$sharedClassName}";
        $projectRoot = rtrim($generator->getRootDirectory(), '/'); // örn: /app
        $sharedFilePath = $projectRoot . '/src/Modules/' . $moduleName . '/Shared/' . $sharedClassName . '.php';

        // 1) Shared abstract controller yoksa oluştur (varsa atla)
        if (!file_exists($sharedFilePath)) {
            $sharedClassData = ClassData::create(
                class: $sharedFqcn,
                suffix: null,
//                extendsClass: AbstractController::class,
                extendsClass: BaseAbstractApiController::class,
                isEntity: false
            );

            $baseSharedClassName = "BaseAbstractApiController";
            $baseSharedAbstractController = $projectRoot . '/Shared/Controller/' . $baseSharedClassName . '.php';

            // gerekli use'ları ekle (AbstractController zaten extendsClass ile gelir ama ekstra yardımcılar)
//            $sharedClassData->addUseStatement(AbstractController::class);
            $sharedClassData->addUseStatement(BaseAbstractApiController::class);
            $sharedClassData->addUseStatement(JsonResponse::class);
            $sharedClassData->addUseStatement(Route::class);



            $generator->generateClassFromClassData($sharedClassData, 'controller/SharedController.tpl.php',
                [
                    'parent_class' => $baseSharedAbstractController, // şablon bu kısa adı kullanacak (ve use var)
                ],
                true
            );

            // shared abstract kesin final olmasın
            $sharedClassData->setIsFinal(false);

            $io->success('Created shared abstract controller: ' . $sharedFilePath);
        } else {
            $io->writeln(sprintf('<comment>Shared abstract controller exists, skipping: %s</comment>', $sharedFilePath));
        }

        // 2) Controller ClassData - parent için use ekle ve final'i kapat
        $controllerFqcn = "Modules\\{$moduleName}\\Controller\\{$controllerName}";
        $controllerClassData = ClassData::create(
            class: $controllerFqcn,
            suffix: 'Controller',
            extendsClass: null, // şablonda parent'i manuel ekleyeceğiz (use ile import ettik)
            isEntity: false
        );

        // controller final olmasın
        $controllerClassData->setIsFinal(false);

        // parent sınıfın FQCN'ini use listesine ekle, böylece şablonda kısa adı (AbstractDenemeController) kullanılabilir
        $controllerClassData->addUseStatement($sharedFqcn);

        // ek use'lar (Response vs JsonResponse, Route) — interactivete set ettiğin değerlere göre ekle
        $controllerClassData->addUseStatement($this->usesTwigTemplate ? Response::class : JsonResponse::class);
        $controllerClassData->addUseStatement(Route::class);

        // 3) Controller'ı oluştur (şablona parent kısa adını veriyoruz)
        $controllerPath = $generator->generateClassFromClassData(
            $controllerClassData,
            'controller/Controller.tpl.php',
            [
                'route_path' => Str::asRoutePath($controllerClassData->getClassName(relative: true, withoutSuffix: true)),
                'route_name' => Str::AsRouteName($controllerClassData->getClassName(relative: true, withoutSuffix: true)),
                'method_name' => $this->isInvokable ? '__invoke' : 'index',
                'with_template' => $this->usesTwigTemplate,
                'template_name' => $this->twigTemplatePath,
                'parent_class' => $sharedClassName, // şablon bu kısa adı kullanacak (ve use var)
            ],
            true
        );

//        // 4) Twig template varsa oluştur
//        if ($this->usesTwigTemplate) {
//            $generator->generateTemplate(
//                $this->twigTemplatePath,
//                'controller/twig_template.tpl.php',
//                [
//                    'controller_path' => $controllerPath,
//                    'root_directory' => $generator->getRootDirectory(),
//                    'class_name' => $controllerClassData->getClassName(),
//                ]
//            );
//        }

//        // 5) Test isteğe bağlı
//        if ($this->shouldGenerateTests()) {
//            $testClassData = ClassData::create(
//                class: sprintf('Tests\Controller\%s', $controllerClassData->getClassName(relative: true, withoutSuffix: true)),
//                suffix: 'ControllerTest',
//                extendsClass: WebTestCase::class,
//            )->setIsFinal(false);
//
//            $generator->generateClassFromClassData($testClassData, 'controller/test/Test.tpl.php', [
//                'route_path' => Str::asRoutePath($controllerClassData->getClassName(relative: true, withoutSuffix: true)),
//            ]);
//        }

        // yaz
        $generator->writeChanges();

        $this->writeSuccessMessage($io);
        $io->text('Next: Open your new controller class and add some pages!');
    }



    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }

    private function isTwigInstalled(): bool
    {
        return class_exists(TwigBundle::class);
    }
}
