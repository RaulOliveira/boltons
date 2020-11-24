<?php 

namespace App\Infrastructure\Arquivei\Console;

use App\Domain\Arquivei\Service\NfeServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncNfesCommand extends Command 
{
    protected static $defaultName = 'arquivei:sync-nfes';
    private $nfeService;

    public function __construct(NfeServiceInterface $nfeService)
    {
        parent::__construct();
        $this->nfeService = $nfeService;
    }

    protected function configure()
    {
        $this->setDescription('Fetch XMLs of received NFes and update database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->nfeService->sync();
        return Command::SUCCESS;        
        // return Command::FAILURE;
    }
}