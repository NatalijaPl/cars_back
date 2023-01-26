<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Models\Car;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    public function index()
    {
        return Car::all();
    }

    public function show($id)
    {
        return Car::findOrFail($id);
    }

    public function store(CarRequest $request)
    {
        return Car::create($request->all());
    }

    public function update(CarRequest $request, $id)
    {
        $car = Car::findOrFail($id);
        $car->update($request->all());
        return $car;
    }

    public function destroy($id)
    {
        Car::destroy($id);
    }
}
