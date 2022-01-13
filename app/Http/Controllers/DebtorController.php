<?php

namespace App\Http\Controllers;

use App\Http\Resources\DebtorResource;
use App\Models\Bank;
use App\Models\Debtor;
use App\Models\Frequency;
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

    private $relations = ['province'];

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
        return $this->forSelect(Debtor::all(), function (Debtor $debtor) {
            $nick = $debtor->nickname == null ? "" : " ({$debtor->nickname})";

            return [
                'value' => $debtor->uuid,
                'label' => "{$debtor->fullname}{$nick}",
            ];
        });
    }

    public function loanForSelectList()
    {
        return $this->forSelect(Debtor::all()->filter(function (Debtor $debtor) {
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
            'guarantor' => ['nullable', 'string'],
            'business' => ['nullable', 'string'],
            'fullname' => ['required', 'string'],
            'nickname' => ['nullable', 'string'],
            'personal_id' => ['required', 'string', 'unique:' . Debtor::class . ',personal_id'],
            'gender' => ['required', 'in:female,male'],
            'province_id' => ['required', 'exists:' . Province::class],
            'address' => ['required', 'string'],
            'date_of_birth' => ['nullable', 'date'],
            'phone' => ['nullable', 'digits:10'],
            'email' => ['nullable', 'email'],
            'marital_status' => ['required', 'in:single,married,divorced,widowed'],
            'occupation' => ['nullable', 'string'],
            'salary' => ['required', 'numeric'],
            // Loan Configuration
            'fees' => ['requiredIf:type,interest_capital', 'numeric'],
            'start_on' => ['required', 'date'],
            'thanks_days' => ['nullable', 'integer'],
            'frequency_id' => ['required', 'exists:' . Frequency::class],
            'bank_id' => ['required', 'exists:' . Bank::class],
            'type' => ['required', 'in:fixed_interest,interest_capital'],
            'interest' => ['required', 'numeric', 'min:0'],
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
            'guarantor' => ['nullable', 'string'],
            'business' => ['nullable', 'string'],
            'fullname' => ['string'],
            'nickname' => ['string'],
            'personal_id' => ['string', 'unique:' . Debtor::class . ',personal_id,' . $debtor->debtor_id . ',debtor_id'],
            'gender' => ['in:female,male'],
            'province_id' => ['exists:' . Province::class],
            'address' => ['string'],
            'date_of_birth' => ['date'],
            'phone' => ['digits:10'],
            'email' => ['email'],
            'marital_status' => ['in:single,married,divorced,widowed'],
            'occupation' => ['string'],
            'salary' => ['numeric'],
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

    protected function requestToArray(Request $request, bool $store): array
    {
        if ($store) {
            return [
                'guarantor' => $request->guarantor,
                'business' => $request->business,
                'fullname' => $request->fullname,
                'nickname' => $request->nickname ?? '',
                'personal_id' => $request->personal_id,
                'gender' => $request->gender,
                'province_id' => $request->province_id,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'phone' => $request->phone ?? '',
                'email' => $request->email ?? '',
                'marital_status' => $request->marital_status ?? '',
                'occupation' => $request->occupation,
                'salary' => $request->salary,
            ];
        }

        return $request->only(['guarantor', 'business', 'fullname', 'nickname', 'personal_id', 'gender', 'province_id', 'address', 'date_of_birth', 'phone', 'email', 'marital_status', 'occupation', 'salary']);
    }

    protected function afterToSave(Request $request, Model $model, bool $store): Model
    {
        if ($store) {
            $bank = Bank::find($request->bank_id);
            $currency_id = $bank->currency_id;

            $fee_amount = get_fee_amount($request->type, $request->balance, $request->interest, $request->fees ?? 1);

            $loan = Loan::create([
                'debtor_id' => $model->getKey(),
                'currency_id' => $currency_id,
                'fee_amount' => $fee_amount,
                'start_on' => $request->start_on,
                'thanks_days' => $request->thanks_days ?? 0,
                'next_on' => $request->start_on,
                'frequency_id' => $request->frequency_id,
                'status' => 'active',
                'amount' => $request->balance,
                'type' => $request->type,
                'interest' => $request->interest,
            ]);

            $transaction = Transaction::create([
                'loan_id' => $loan->loan_id,
                'bank_id' => $request->bank_id,
                'register_user_id' => Auth::user()->user_id,
                'amount' => $request->balance,
                'currency_id' => $currency_id,
                'comment' => 'Nuevo prestamo para ' . $model->fullname,
            ]);
        }

        return $model;
    }
}
