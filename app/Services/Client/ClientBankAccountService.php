<?php

namespace App\Services\Client;

use App\Models\Client\ClientBankAccount;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ClientBankAccountService{

    public function allClientBankAccounts(array $filters){

        $clientBankAccounts = QueryBuilder::for(ClientBankAccount::class)
        ->allowedFilters([
            //AllowedFilter::custom('search', new FilterClientBankAccount()), // Add a custom search filter
            //AllowedFilter::exact('clientBankAccountType', 'clientBankAccount_type'),
        ])
        ->when(
            $filters['clientId'] ?? null,
            fn ($query) => $query->where('client_id', $filters['clientId'])
        )
        ->get();
        return $clientBankAccounts;

    }

    public function createClientBankAccount(array $clientBankAccountData){

        $clientBankAccount = ClientBankAccount::create([
            'iban' => $clientBankAccountData['iban'],
            'abi' => $clientBankAccountData['abi'],
            'cab' => $clientBankAccountData['cab'],
            'is_main' => $clientBankAccountData['isMain'],
            'client_id' => $clientBankAccountData['clientId'],
        ]);

        ClientBankAccount::whereNot('id', $clientBankAccount->id)->where('client_id', $clientBankAccountData['clientId'])->update(['is_main' => false]);

        return $clientBankAccount;

    }

    public function editClientBankAccount(string $clientBankAccountId){
        $clientBankAccount = ClientBankAccount::find($clientBankAccountId);

        return $clientBankAccount;

    }

    public function updateClientBankAccount(array $clientBankAccountData){

        $clientBankAccount = ClientBankAccount::find($clientBankAccountData['clientBankAccountId']);

        $clientBankAccount->fill([
            'iban' => $clientBankAccountData['iban'],
            'abi' => $clientBankAccountData['abi'],
            'cab' => $clientBankAccountData['cab'],
            'is_main' => $clientBankAccountData['isMain'],
            'client_id' => $clientBankAccountData['clientId'],
        ]);

        $clientBankAccount->save();

        ClientBankAccount::whereNot('id', $clientBankAccount->id)->where('client_id', $clientBankAccountData['clientId'])->update(['is_main' => false]);


        return $clientBankAccount;

    }

    public function deleteClientBankAccount(string $clientBankAccountId){
        $clientBankAccount = ClientBankAccount::find($clientBankAccountId);
        $clientBankAccount->delete();
    }

}
