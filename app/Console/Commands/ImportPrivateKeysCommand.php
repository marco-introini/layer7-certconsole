<?php

namespace App\Console\Commands;

use App\Http\Integrations\Layer7\Layer7RestmanConnector;
use App\Http\Integrations\Layer7\Requests\PrivateKeys;
use App\Models\Gateway;
use Illuminate\Console\Command;
use Saloon\XmlWrangler\Exceptions\XmlReaderException;
use Saloon\XmlWrangler\XmlReader;

class ImportPrivateKeysCommand extends Command
{
    protected $signature = 'import:private-keys';

    protected $description = 'Command description';

    public function handle(): void
    {
        $gateway = Gateway::first();
        $connector = new Layer7RestmanConnector($gateway);
        $response = $connector->send(new PrivateKeys());

        if ($response->status() !== 200) {
            // errore
        }

        try {
            $reader = XmlReader::fromSaloonResponse($response);
        } catch (XmlReaderException $e) {

        }
        $keys = $reader->value('l7:Encoded')->get();

        dd($response);

    }
}
