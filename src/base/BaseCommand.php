<?php


namespace huikedev\huike_base\base;


use think\console\Command;
use think\console\Input;
use think\console\Output;

abstract class BaseCommand extends Command
{
    /**
     * @var string
     */
    protected $commandName = 'default';
    /**
     * @var string
     */
    protected $description = 'none';
    /**
     * @var Input
     */
    protected $commandInput;
    /**
     * @var Output
     */
    protected $commandOutput;

    abstract protected function handle();

    protected function configure()
    {
        $this->setName(class_basename(get_called_class()));
        $this->setDescription($this->description);
        parent::configure();
    }

    protected function execute(Input $input, Output $output)
    {
        $this->commandInput = $input;
        $this->commandOutput = $output;
        $this->handle();
    }
}