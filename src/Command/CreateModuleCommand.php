<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Inflector\EnglishInflector;

#[AsCommand(
    name: 'app:create-module',
    description: 'Yeni bir modül için klasör yapısı oluşturur (Controller, Entity, Repository, Service, Form, Infrastructure)',
)]
class CreateModuleCommand extends Command
{
    private Filesystem $filesystem;
    private EnglishInflector $inflector;

    // Yapılandırılabilir sabitler
    private const BASE_NAMESPACE = 'App';
    private const MODULE_DIRECTORY = 'Modules'; // Bu değeri Domain, Features, vb. olarak değiştirebilirsiniz
    private const SRC_DIRECTORY = 'src';
    private const CONTROLLER_SUBDIR = 'Controller';
    private const ENTITY_SUBDIR = 'Entity';
    private const REPOSITORY_SUBDIR = 'Repository';
    private const SERVICE_SUBDIR = 'Service';
    private const FORM_SUBDIR = 'Form';
    private const INFRASTRUCTURE_SUBDIR = 'Infrastructure';
    private const ATTRIBUTE_SUBDIR = 'Attribute';
    private const SHARED = 'Shared';

    public function __construct()
    {
        parent::__construct();
        $this->filesystem = new Filesystem();
        $this->inflector = new EnglishInflector();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        // Modül adını sor
        $question = new Question('Modül adını girin (örn: Siparis, Urun, Musteri): ');
        $moduleName = $questionHelper->ask($input, $output, $question);


        if (empty($moduleName)) {
            $io->error('Modül adı boş olamaz!');
            return Command::FAILURE;
        }

        // Modül adını düzenle
        $moduleName = ucfirst(strtolower($moduleName));
        $moduleNameLower = strtolower($moduleName);

        $io->section("Modül klasör yapısı oluşturuluyor: {$moduleName}");

        try {
            // Modül dizinini oluştur
            $modulePath = self::SRC_DIRECTORY . "/" . self::MODULE_DIRECTORY . "/{$moduleName}";
            $this->filesystem->mkdir($modulePath);

            // Alt dizinleri oluştur
            $subDirectories = [
                self::CONTROLLER_SUBDIR,
                self::ENTITY_SUBDIR,
                self::REPOSITORY_SUBDIR,
                self::SERVICE_SUBDIR,
                self::FORM_SUBDIR,
                self::INFRASTRUCTURE_SUBDIR,
                self::ATTRIBUTE_SUBDIR,
                self::SHARED,
            ];

            foreach ($subDirectories as $dir) {
                $this->filesystem->mkdir("{$modulePath}/{$dir}");
            }

            // Attribute route class'ı oluştur
            $this->createAttributeRouteClass($moduleName, $moduleNameLower);

            // Config/routes/modules.yaml dosyasını kontrol et ve oluştur
            $modulesYamlPath = "config/routes/modules.yaml";

            // Eğer dosya yoksa oluştur
            if (!$this->filesystem->exists($modulesYamlPath)) {
                $this->filesystem->dumpFile($modulesYamlPath, "# Modül routing yapılandırmaları\n");
            }

            // Mevcut içeriği oku
            $currentContent = $this->filesystem->exists($modulesYamlPath) ? file_get_contents($modulesYamlPath) : "# Modül routing yapılandırmaları\n";

            // Yeni routing içeriğini oluştur
//            $routingContent = $this->generateRoutingContent($moduleName, $moduleNameLower);

            // İçeriği dosyaya ekle
//            $newContent = $currentContent . "\n" . $routingContent;
//            $this->filesystem->dumpFile($modulesYamlPath, $newContent);

            $io->success([
                "Modül '{$moduleName}' klasör yapısı başarıyla oluşturuldu!",
                "Oluşturulan klasörler:",
                "- {$modulePath}/" . self::CONTROLLER_SUBDIR . "/",
                "- {$modulePath}/" . self::ENTITY_SUBDIR . "/",
                "- {$modulePath}/" . self::REPOSITORY_SUBDIR . "/",
                "- {$modulePath}/" . self::SERVICE_SUBDIR . "/",
                "- {$modulePath}/" . self::FORM_SUBDIR . "/",
                "- {$modulePath}/" . self::INFRASTRUCTURE_SUBDIR . "/",
                "- {$modulePath}/" . self::ATTRIBUTE_SUBDIR . "/",
                "- {$modulePath}/" . self::SHARED . "/",
                "",
                "Oluşturulan dosyalar:",
                "- {$modulePath}/" . self::ATTRIBUTE_SUBDIR . "/{$moduleName}RoutePrefix.php",
                "",
                "Routing yapılandırması:",
                "- {$modulesYamlPath} dosyasına eklendi"
            ]);

            $io->note([
                "Şimdi Symfony'nin kendi komutlarını kullanarak dosyaları oluşturabilirsiniz:",
                "",
                "Entity oluşturmak için:",
                "  php bin/console make:entity {$moduleName}",
                "",
                "Controller oluşturmak için:",
                "  php bin/console make:controller {$moduleName}Controller",
                "",
                "Form oluşturmak için:",
                "  php bin/console make:form {$moduleName}Form {$moduleName}",
                "",
                "Repository oluşturmak için:",
                "  php bin/console make:repository {$moduleName}",
                "",
                "CRUD oluşturmak için:",
                "  php bin/console make:crud {$moduleName}",
                "",
                "Migration oluşturmak için:",
                "  php bin/console make:migration",
                "",
                "Migration'ı çalıştırmak için:",
                "  php bin/console doctrine:migrations:migrate"
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error("Hata oluştu: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function generateRoutingContent(string $moduleName, string $moduleNameLower): string
    {
        return <<<YAML
{$moduleNameLower}_controller:
    resource:
        path: ../../src/{$this->getModuleDirectory()}/{$moduleName}/Controller
        namespace: {$this->getBaseNamespace()}\\{$this->getModuleDirectory()}\\{$moduleName}\\Controller
    type: attribute
YAML;
    }

    private function getBaseNamespace(): string
    {
        return self::BASE_NAMESPACE;
    }

    private function getModuleDirectory(): string
    {
        return self::MODULE_DIRECTORY;
    }

    private function createAttributeRouteClass(string $moduleName, string $moduleNameLower): void
    {
        $attributePath = self::SRC_DIRECTORY . "/" . self::MODULE_DIRECTORY . "/{$moduleName}/" . self::ATTRIBUTE_SUBDIR;
        $routeClassName = "{$moduleName}Route";
        $routeFilePath = "{$attributePath}/{$routeClassName}.php";

        $routeClassContent = $this->generateRouteClassContent($moduleName, $moduleNameLower, $routeClassName);
        $this->filesystem->dumpFile($routeFilePath, $routeClassContent);
    }

    private function generateRouteClassContent(string $moduleName, string $moduleNameLower, string $routeClassName): string
    {
        return <<<PHP
<?php

namespace {$this->getBaseNamespace()}\\{$this->getModuleDirectory()}\\{$moduleName}\\{$this->getAttributeSubdir()};

use Symfony\Component\Routing\Attribute\DeprecatedAlias;
use Symfony\Component\Routing\Attribute\Route;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class {$routeClassName} extends Route
{

    public function __construct(array|string|null \$path = null, ?string \$name = null, array \$requirements = [], array \$options = [], array \$defaults = [], ?string \$host = null, array|string \$methods = [], array|string \$schemes = [], ?string \$condition = null, ?int \$priority = null, ?string \$locale = null, ?string \$format = null, ?bool \$utf8 = null, ?bool \$stateless = null, ?string \$env = null, array|DeprecatedAlias|string \$alias = [])
    {

        if(!\$path){
            \$path = \$this->getRoutePath();
        }
        parent::__construct(\$path, \$name, \$requirements, \$options, \$defaults, \$host, \$methods, \$schemes, \$condition, \$priority, \$locale, \$format, \$utf8, \$stateless, \$env, \$alias);
    }

    private function getRoutePath(): string
    {
        return "/{$moduleNameLower}";
    }

}
PHP;
    }

    private function getAttributeSubdir(): string
    {
        return self::ATTRIBUTE_SUBDIR;
    }
}
