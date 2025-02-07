<?php

declare(strict_types=1);

namespace Crunz\Tests\Functional;

use Crunz\Application;
use Crunz\Path\Path;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class TaskGeneratorTest extends TestCase
{
    private string $fileName;
    private string $taskFilePath;
    private string $outputDirectory;

    public function setUp(): void
    {
        $this->outputDirectory = \sys_get_temp_dir();
        $this->fileName = 'CrunzTest';
        $taskFilePath = Path::create(
            [
                $this->outputDirectory,
                "{$this->fileName}Tasks.php",
            ]
        );
        $this->taskFilePath = $taskFilePath->toString();
        $this->clearTask();
    }

    public function tearDown(): void
    {
        $this->clearTask();
    }

    /** @test */
    public function generate_task_file(): void
    {
        $application = new Application('Crunz', '0.1.0-test.1');
        $command = $application->get('make:task');

        $commandTester = new CommandTester($command);
        $this->provideAnswer(
            "{$this->outputDirectory}\n",
            $commandTester,
            $command
        );
        $returnCode = $commandTester->execute(
            [
                'taskfile' => $this->fileName,
            ]
        );

        self::assertSame(0, $returnCode);
        self::assertFileExists($this->taskFilePath);
    }

    /** @return resource */
    private function getInputStream(string $input)
    {
        $stream = \fopen('php://memory', 'rb+', false);

        if (false === $stream) {
            throw new \RuntimeException("Unable to open 'php://memory' stream.");
        }

        \fwrite($stream, $input);
        \rewind($stream);

        return $stream;
    }

    private function clearTask(): void
    {
        if (\file_exists($this->taskFilePath)) {
            \unlink($this->taskFilePath);
        }
    }

    private function provideAnswer(
        string $answer,
        CommandTester $commandTester,
        Command $command,
    ): void {
        if (\method_exists($commandTester, 'setInputs')) {
            $commandTester->setInputs([$answer]);

            return;
        }

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream($answer));
    }
}
