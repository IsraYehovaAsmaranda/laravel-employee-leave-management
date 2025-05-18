<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Interviewee;
use App\Http\Requests\StoreIntervieweeRequest;
use App\Http\Requests\UpdateIntervieweeRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class IntervieweeController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize("viewAny", Interviewee::class);

        $query = Interviewee::query();

        if (request()->has("search")) {
            $query->where("name", "like", "%" . request()->search . "%")
                ->orWhere("email", "like", "%" . request()->search . "%")
                ->orWhere("phone", "like", "%" . request()->search . "%");
        }

        $sortBy = request()->query("sort_by", "created_at");
        if (!in_array($sortBy, ["name", "email", "phone", "created_at"])) {
            $sortBy = "created_at";
        }

        $sortOrder = request()->query("direction", "desc");
        if (!in_array($sortOrder, ["asc", "desc"])) {
            $sortOrder = "desc";
        }

        $query->orderBy($sortBy, $sortOrder);

        $size = request()->query("size", 10);
        $interviewees = $query->paginate($size);
        return ApiResponse::send($interviewees, "Successfully fetch interviewees", 200, true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIntervieweeRequest $request)
    {
        $this->authorize("create", Interviewee::class);

        $data = $request->all();

        if ($request->hasFile("cv")) {
            $path = $request->file("cv")->store("interviewees", "public");
            $data["cv"] = $path;
        }

        $interviewee = Interviewee::create($data);
        return ApiResponse::send($interviewee, "Successfully created interviewee", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Interviewee $interviewee)
    {
        $this->authorize("view", $interviewee);
        return ApiResponse::send($interviewee, "Successfully fetch interviewee");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIntervieweeRequest $request, Interviewee $interviewee)
    {
        $this->authorize("update", $interviewee);

        if ($request->email !== $interviewee->email) {
            $otherInterviewee = Interviewee::where("email", $request->email)->first();
            if ($otherInterviewee !== null) {
                throw ValidationException::withMessages([
                    'email' => ['Email already exists']
                ]);
            }
        }

        if($request->phone !== $interviewee->phone) {
            $otherInterviewee = Interviewee::where("phone", $request->phone)->first();
            if ($otherInterviewee !== null) {
                throw ValidationException::withMessages([
                    'phone' => ['Phone already exists']
                ]);
            }
        }

        $data = $request->all();

        if ($request->hasFile("cv")) {
            $path = $request->file("cv")->store("interviewees", "public");
            $data["cv"] = $path;

            if ($interviewee->attachment && Storage::disk("public")->exists($interviewee->attachment)) {
                Storage::disk("public")->delete($interviewee->attachment);
            }
        }

        $interviewee->update($data);
        return ApiResponse::send($interviewee, "Successfully updated interviewee");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interviewee $interviewee)
    {
        $this->authorize("delete", $interviewee);
        $interviewee->delete();

        $response = [
            "deleted_at" => $interviewee->deleted_at,
        ];
        return ApiResponse::send($response, "Successfully deleted interviewee");
    }
}
