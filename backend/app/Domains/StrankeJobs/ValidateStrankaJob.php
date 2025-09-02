<?php

namespace App\Domains\StrankeJobs;

use Illuminate\Support\Facades\Validator;


class ValidateStrankaJob
{
    protected string $strankaName;
    protected array $strankaData;

    public function __construct(private $request, private $stranka) {}

public function handle()
{
    $validator = Validator::make($this->request->all(), [
        'name' => ['required', 'string', 'alpha_dash', 'min:2', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255'],// ,'unique:stranke,email,' . $this->stranka->id . ',id'
        'phone' => ['required', 'string', 'min:5'],
        'dejavnost' => ['required', 'string']
    ]);

    if ($validator->fails()) {
        $errors = $validator->errors()->all(); // 🔥 seznam vseh napak kot array
        \Log::info('Napake pri validaciji:', $errors);
        return response()->json([
            'errors' => $errors,
            'message' => 'Ne veljavni podatki.'
        ], 422); // ali karkoli želiš
    }

    return $validator->validated(); // ✅ če je vse OK, vrne validirane podatke
}

}