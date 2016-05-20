<?php

namespace Mauchede\PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;
use PHPCI\Model\BuildError;
use PHPCI\Plugin\Util\TapParser;

class Atoum implements \PHPCI\Plugin
{
    /**
     * @var Build
     */
    private $build;

    /**
     * @var string
     */
    private $configFile;

    /**
     * @var string
     */
    private $executable;

    /**
     * @var Builder
     */
    private $phpci;

    /**
     * @param Builder $phpci
     * @param Build   $build
     * @param array   $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->build = $build;
        $this->phpci = $phpci;
        $this->executable = $this->phpci->findBinary('atoum');

        $this->configFile = 'atoum.php';
        if (isset($options['config'])) {
            $this->configFile = $options['config'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        chdir($this->phpci->buildPath);

        if (!is_file($this->configFile)) {
            $this->phpci->logFailure(sprintf('The Atoum config file "%s" is missing.', $this->configFile));

            return false;
        }

        $this->phpci->logExecOutput(false);
        $status = $this->phpci->executeCommand(
            'php %s -c %s -ft -utr',
            $this->executable,
            $this->configFile
        );
        $this->phpci->logExecOutput(true);

        try {
            $parser = new TapParser(
                mb_convert_encoding('TAP version 13'.PHP_EOL.$this->phpci->getLastOutput(), 'UTF-8', 'ISO-8859-1')
            );
            $data = $parser->parse();

            $this->reportErrors($data);
        } catch (\Exception $exception) {
            $status = false;
            $this->phpci->logFailure('Impossible to parse the Atoum output.', $exception);
        }

        return $status;
    }

    /**
     * @param array $data
     */
    private function reportErrors(array $data)
    {
        foreach ($data as $test) {
            if ($test['pass']) {
                continue;
            }

            $message = explode('::', $test['message']);

            $this->build->reportError(
                $this->phpci,
                'stage_test',
                sprintf('Test "%s" failed.', $message[1]),
                BuildError::SEVERITY_CRITICAL,
                $message[0]
            );
        }
    }
}
