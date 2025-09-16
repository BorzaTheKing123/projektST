<?php

namespace App\Domains\StrankeJobs;

class ValidateStrankaJob
{
    protected string $strankaName;
    protected array $strankaData;

    public function __construct(private $request)
    {
    }

    public function handle()
    {   
        $input = $this->request->validate([
        'name' => ['required', 'string', 'alpha_dash', 'min:2', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255'],
        'phone' => ['required', 'string', 'min:5'],
        'dejavnost' => ['required', 'string']
        ]);

        return $input;
        
    }
}