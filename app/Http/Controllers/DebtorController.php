<?php

namespace App\Http\Controllers;

use App\Http\Resources\DebtorResource;
use App\Models\Bank;
use App\Models\Business;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Debtor;
use App\Models\Frequency;
use App\Models\Guarantor;
use App\Models\Loan;
use App\Models\Province;
use App\Models\Transaction;
use App\Rules\NotPresent;
use App\Traits\GenericController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebtorController extends Controller
{
    use GenericController;

    private $relations = ['guarantor', 'business', 'province'];

    public function __construct()
    {
        $this->resource = DebtorResource::class;
        $this->default_key_sort = 'debtor_id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->includeRelations(Debtor::query(), $this->relations);

        return $this->idx($query);
    }

    public function forSelectList()
    {
        return $this->forSelect(Debtor::where('company_id', '=', null_prevent_property(auth_company(), 'company_id'))->get(), function (Debtor $debtor) {
            $nick = $debtor->nickname == null ? "" : " ({$debtor->nickname})";

            return [
                'value' => $debtor->uuid,
                'label' => "{$debtor->fullname}{$nick}",
            ];
        });
    }

    public function loanForSelectList()
    {
        return $this->forSelect(Debtor::where('company_id', '=', null_prevent_property(auth_company(), 'company_id'))->get()->filter(function (Debtor $debtor) {
            $loans = $debtor->loans;

            return $loans->count() > 0;
        })->values(), function (Debtor $debtor) {
            $nick = $debtor->nickname == null ? "" : " ({$debtor->nickname})";
            $loan = $debtor->loans->first();

            return [
                'value' => null_prevent_property($loan, 'uuid'),
                'label' => "{$debtor->fullname}{$nick}",
            ];
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'company_id' => [new NotPresent()],
            'guarantor_id' => ['required', 'exists:' . Guarantor::class],
            'business_id' => ['exists:' . Business::class],
            'fullname' => ['required', 'string'],
            'nickname' => ['string'],
            'personal_id' => ['required', 'string', 'unique:' . Debtor::class . ',personal_id'],
            'gender' => ['required', 'in:female,male'],
            'province_id' => ['required', 'exists:' . Province::class],
            'address' => ['required', 'string'],
            'date_of_birth' => ['date'],
            'phone' => ['digits:10'],
            'marital_status' => ['required', 'in:single,married,divorced,widowed'],
            'occupation' => ['required', 'string'],
            'salary' => ['required', 'numeric'],
            'other_income' => ['required', 'numeric'],
            // Loan Configuration
            'currency_id' => ['required', 'exists:' . Currency::class],
            'fee_amount' => ['required', 'numeric'],
            'start_on' => ['required', 'date'],
            'thanks_days' => ['integer'],
            'frequency_id' => ['required', 'exists:' . Frequency::class],
            'bank_id' => ['required', 'exists:' . Bank::class],
            'balance' => ['required', 'numeric', 'min:0'],
        ];

        return $this->change($request, $rules, new Debtor(), true, $this->relations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Debtor  $debtor
     * @return \Illuminate\Http\Response
     */
    public function show(Debtor $debtor)
    {
        return  $this->showOne($debtor, $this->relations);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Debtor  $debtor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Debtor $debtor)
    {
        $rules = [
            'company_id' => [new NotPresent()],
            'guarantor_id' => ['exists:' . Guarantor::class],
            'business_id' => ['exists:' . Business::class],
            'fullname' => ['string'],
            'nickname' => ['string'],
            'personal_id' => ['string', 'unique:' . Debtor::class . ',personal_id,' . $debtor->debtor_id . ',debtor_id'],
            'gender' => ['in:female,male'],
            'province_id' => ['exists:' . Province::class],
            'address' => ['string'],
            'date_of_birth' => ['date'],
            'phone' => ['digits:10'],
            'marital_status' => ['in:single,married,divorced,widowed'],
            'occupation' => ['string'],
            'salary' => ['numeric'],
            'other_income' => ['numeric'],
        ];

        return $this->change($request, $rules, $debtor, false, $this->relations);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Debtor  $debtor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Debtor $debtor)
    {
        $debtor->delete();

        return  $this->showOne($debtor, []);
    }

    public function companiesDebtorsIndex(Company $company)
    {
        return $this->modelRelationIndex($company, 'debtors', $this->relations);
    }

    public function guarantorsDebtorsIndex(Guarantor $guarantor)
    {
        return $this->modelRelationIndex($guarantor, 'debtors', $this->relations);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        if ($store) {
            return [
                'company_id' => auth_company()->company_id,
                'guarantor_id' => $request->guarantor_id,
                'business_id' => $request->business_id,
                'fullname' => $request->fullname,
                'nickname' => $request->nickname ?? '',
                'personal_id' => $request->personal_id,
                'gender' => $request->gender,
                'province_id' => $request->province_id,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'phone' => $request->phone ?? '',
                'marital_status' => $request->marital_status ?? '',
                'occupation' => $request->occupation,
                'salary' => $request->salary,
                'other_income' => $request->other_income,
            ];
        }

        return $request->only(['guarantor_id', 'business_id', 'fullname', 'nickname', 'personal_id', 'gender', 'province_id', 'address', 'date_of_birth', 'phone', 'marital_status', 'occupation', 'salary', 'other_income']);
    }

    protected function afterToSave(Request $request, Model $model, bool $store): Model
    {
        if ($store) {
            $loan = Loan::create([
                'debtor_id' => $model->getKey(),
                'currency_id' => $request->currency_id,
                'fee_amount' => $request->fee_amount,
                'start_on' => $request->start_on,
                'thanks_days' => $request->thanks_days ?? 0,
                'next_on' => $request->start_on,
                'frequency_id' => $request->frequency_id,
                'status' => 'active',
            ]);

            $transaction = Transaction::create([
                'loan_id' => $loan->loan_id,
                'bank_id' => $request->bank_id,
                'register_user_id' => Auth::user()->user_id,
                'amount' => $request->balance,
                'currency_id' => $request->currency_id,
                'comment' => 'Nuevo prestamo para ' . $model->fullname,
            ]);
        }

        return $model;
    }
}
