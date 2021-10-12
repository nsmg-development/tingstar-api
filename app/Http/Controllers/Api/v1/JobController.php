<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CommandJob;
use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    protected CommandJob $commandJob;
    protected Keyword $keyword;

    public function __construct(CommandJob $commandJob, Keyword $keyword)
    {
        $this->commandJob = $commandJob;
        $this->keyword = $keyword;
    }

    public function store(Request $request)
    {
        if ($request->type === 'keyword') {

            // scrap:instagram:by:keyword {keywordId} {jobId}
        }

        $commandJob = $this->commandJob->where([
            'command' => $request->command
        ])->first();

        if ($commandJob) {
            $result = collect([
                'statusCode' => '409',
                'message' => '동일한 작업이 아직 실행중입니다.'
            ]);

            return $this->response($result);
        }

        try {
            DB::beginTransaction();

            $result = $this->commandJob->create([
                'type' => $request->type,
                'command' => $request->command
            ]);
            DB::commit();

            return collect($result);
        } catch (\Exception $e) {
            DB::rollBack();

            return collect([
                'statusCode' => 500,
                'message' => '오류가 발생하였습니다.'
            ]);
        }
    }
}
