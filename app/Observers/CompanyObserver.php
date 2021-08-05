<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\OauthClient;
use Illuminate\Support\Str;

class CompanyObserver
{
    /**
     * Handle the Company "created" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function created(Company $company)
    {
        OauthClient::create([
            'company_id' => $company->company_id,
            'name' => "{$company->name} Client",
            'secret' => Str::random(64),
            'provider' => 'users',
            'redirect' => 'http://localhost',
            'personal_access_client' => false,
            'password_client' => true,
            'revoked' => false
        ]);
    }
}
