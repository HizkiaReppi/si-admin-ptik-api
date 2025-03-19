<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use App\Http\Requests\StoreRequirementRequest;
use App\Http\Requests\UpdateRequirementRequest;

class RequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequirementRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Requirement $requirement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequirementRequest $request, Requirement $requirement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Requirement $requirement)
    {
        //
    }
}
