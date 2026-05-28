<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use Illuminate\Http\Request;

class DegreeController extends Controller
{
    public function index()
    {
        $degrees = Degree::withCount('students')->paginate(10);
        return view('degrees.index', compact('degrees'));
    }

    public function create()
    {
        return view('degrees.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Degree::create($request->only('name'));
        return redirect()->route('degrees.index')->with('success', 'Degree added successfully.');
    }

    public function show(Degree $degree)
    {
        $degree->load('students', 'courses');
        return view('degrees.show', compact('degree'));
    }

    public function edit(Degree $degree)
    {
        return view('degrees.edit', compact('degree'));
    }

    public function update(Request $request, Degree $degree)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $degree->update($request->only('name'));
        return redirect()->route('degrees.index')->with('success', 'Degree updated successfully.');
    }

    public function destroy(Degree $degree)
    {
        $degree->delete();
        return redirect()->route('degrees.index')->with('success', 'Degree deleted successfully.');
    }
}
