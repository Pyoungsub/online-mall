<?php

namespace App\Livewire\Business\Signup;

use Livewire\Component;

use App\Models\Team;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\AddingTeam;
use Laravel\Jetstream\Jetstream;

class SearchBrn extends Component
{
    public $brn;
    public $remaining_seconds = 0;
    public $brn_error_message = '';
    //사업자 번호 조회
    public function check()
    {
        $validated = $this->validate([ 
            'brn' => 'required|regex:/[0-9]{3}-[0-9]{2}-[0-9]{5}/',
        ]);

        
        if(Auth::user()->ownedTeams)
        {
            dd('Exist');
        }
        else
        {
            $executed = RateLimiter::attempt(
                'search-rbn:'.Auth::user()->id,
                $perMinute = 3,
                function() {
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ])->post('https://api.odcloud.kr/api/nts-businessman/v1/status?serviceKey=WEf6RqCmS4ohO3igNObcEMEywKmIqceTdUxyMs%2BwieeaV5Mmy8o7OS4ZYujiCDA9%2FvV%2BYoHvBxKbIoCBV6SN%2Bw%3D%3D',[
                        'b_no' => [str_replace('-', '', $this->brn)]
                    ]);
                    
                    if($response->ok() && $response->json('match_cnt') == 1)
                    {
                        if($response->json('data')[0]['b_stt_cd'] == 01)
                        {
                            $user = Auth::user();
                            Gate::forUser($user)->authorize('create', Jetstream::newTeamModel());
                            AddingTeam::dispatch($user);
                            $user->switchTeam($team = $user->ownedTeams()->create([
                                'brn' => $response->json('data')[0]['b_no'],
                                'personal_team' => false,
                            ]));
                            return redirect()->route('business.signup.submit-document');
                        }
                        elseif($response->json('data')[0]['b_stt_cd'] == 02)
                        {
                            $this->brn_error_message = '휴업자는 가입할 수 없습니다.';
                            $this->reset('brn');
                        }
                        elseif($response->json('data')[0]['b_stt_cd'] == 03)
                        {
                            $this->brn_error_message = '폐업처리 된 사업장 입니다.';
                            $this->reset('brn');
                        }
                    }
                    else
                    {
                        $this->brn_error_message = '등록되지 않은 사업자등록번호 입니다.';
                        $this->reset('brn');
                    }
                }
            );
            if (! $executed) {
                $this->remaining_seconds = RateLimiter::availableIn('search-rbn:'.Auth::user()->id);
            }
        }
    }

    public function mount()
    {
        $this->remaining_seconds = RateLimiter::availableIn('search-rbn:'.Auth::user()->id);
    }

    public function create(User $user, array $input): Team
    {
        Gate::forUser($user)->authorize('create', Jetstream::newTeamModel());
        AddingTeam::dispatch($user);

        $user->switchTeam($team = $user->ownedTeams()->create([
            'name' => $input['name'],
            'personal_team' => false,
        ]));

        return $team;
    }
    public function render()
    {
        return view('livewire.business.signup.search-brn');
    }
}
