<?php
namespace App\Console\Commands\OneDrive;

use App\Service\OneDrive;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class Direct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'od:direct {clientId : Onedrive Id} {remote : RemotePath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Direct Share Link';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \ErrorException
     */
    public function handle()
    {
        $this->info('Please waiting...');
        $remote = $this->argument('remote');
        $clientId = $this->argument('clientId');
        refresh_token(getOnedriveAccount($clientId));
        $_remote = OneDrive::getInstance(getOnedriveAccount($clientId))->pathToItemId($remote);
        $remote_id = $_remote['errno'] === 0 ? Arr::get($_remote, 'data.id') : exit('Remote Path Abnormal');
        $response = OneDrive::getInstance(getOnedriveAccount($clientId))->createShareLink($remote_id);
        $response['errno'] === 0
            ? $this->info("Success! Direct Link:\n{$response['data']['redirect']}")
            : $this->warn("Failed!\n{$response['msg']} ");
    }
}
