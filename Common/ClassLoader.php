<?php

final class ClassLoader
{
    private static array $classMap = [];
    private static bool $booted = false;

    public static function register(): void
    {
        if (self::$booted) {
            return;
        }

        self::$classMap = self::buildClassMap();
        spl_autoload_register([self::class, 'loadClass']);
        self::$booted = true;
    }

    public static function loadClass(string $className): void
    {
        if (!isset(self::$classMap[$className])) {
            return;
        }

        require_once self::$classMap[$className];
    }

    private static function buildClassMap(): array
    {
        $basePath = dirname(__DIR__);
        $directories = [
            $basePath . '/Domain',
            $basePath . '/Application',
            $basePath . '/Infrastructure/Config',
            $basePath . '/Infrastructure/Mapper',
            $basePath . '/Infrastructure/Repositories',
            $basePath . '/Infrastructure/Entrypoints/Web/Config',
            $basePath . '/Infrastructure/Entrypoints/Web/Controllers',
            $basePath . '/Infrastructure/Entrypoints/Web/Dto',
            $basePath . '/Infrastructure/Entrypoints/Web/Mapper',
            $basePath . '/Infrastructure/Entrypoints/Web/Presentation',
        ];

        $classMap = [];

        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $fileInfo) {
                if (!$fileInfo->isFile() || $fileInfo->getExtension() !== 'php') {
                    continue;
                }

                $filePath = $fileInfo->getRealPath();
                if ($filePath === false) {
                    continue;
                }

                $relativePath = str_replace($basePath . '/', '', str_replace('\\', '/', $filePath));
                if (str_contains($relativePath, '/Presentation/Views/')) {
                    continue;
                }

                $className = pathinfo($fileInfo->getFilename(), PATHINFO_FILENAME);
                $classMap[$className] = $filePath;
            }
        }

        return $classMap;
    }
}
