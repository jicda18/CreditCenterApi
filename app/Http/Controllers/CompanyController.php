<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Traits\GenericController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    use GenericController;

    private $relations = [];

    /**
     * function name that don't required Authentication
     *
     * @var string[] withoutAuth
     */
    protected $withoutAuth = ['logo', 'ico'];

    public function __construct()
    {
        $this->resource = CompanyResource::class;
        $this->default_key_sort = 'company_id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->includeRelations(Company::query(), $this->relations);

        return $this->idx($query);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return  $this->showOne($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return  $this->showOne($company);
    }

    public function logo(Company $company)
    {
        return response()->file(storage_path('resources' . DIRECTORY_SEPARATOR . $company->logo));
    }

    public function ico(Company $company)
    {
        return response()->file(storage_path('resources' . DIRECTORY_SEPARATOR . $company->icon));
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
