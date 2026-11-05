<?php

return [
    'mode' => env('BLOCKCHAIN_MODE', 'local'),
    'web3_provider' => env('WEB3_PROVIDER', 'http://127.0.0.1:8545'),
    'contract_address' => env('CONTRACT_ADDRESS'),
    'private_key' => env('PRIVATE_KEY'),
];
