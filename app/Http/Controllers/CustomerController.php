<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/** 
 * @OA\Schema(
 *     schema="Customer",
 *     type="object",
 *     title="Customer",
 *     description="Customer model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Customer ID"
 *     ),
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         description="Customer's first name"
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         description="Customer's last name"
 *     ),
 *     @OA\Property(
 *         property="age",
 *         type="integer",
 *         description="Customer's age"
 *     ),
 *     @OA\Property(
 *         property="dob",
 *         type="date",
 *         description="Customer's date"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="Customer's email"
 *     ),
 *     @OA\Property(
 *         property="creation_date",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp when the customer was created"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp when the customer was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp when the customer was last updated"
 *     )
 * )
 */
class CustomerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/customers",
     *     summary="Get all customers",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="A list of customers",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Customer")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return Customer::all();
    }

    /**
     * @OA\Get(
     *     path="/api/customers/{id}",
     *     summary="Get a single customer",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer details",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found"
     *     )
     * )
     */
    public function show($id)
    {
        return Customer::find($id);
    }

    /**
     * @OA\Post(
     *     path="/api/customers",
     *     summary="Create a new customer",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $customer = Customer::create($request->all());
        return response()->json($customer, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/customers/{id}",
     *     summary="Update an existing customer",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($request->all());
        return response()->json($customer, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/customers/{id}",
     *     summary="Delete a customer",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="response_code", type="string", example="200"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Customer deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        if (!$customer) {
            return response()->json([
                'response_code' => '404',
                'status' => 'error',
                'message' => 'Customer not found.',
            ], 404);
        }

        $customer->delete();

        return response()->json([
            'response_code' => '200',
            'status' => 'success',
            'message' => 'Customer deleted successfully.',
        ], 200);
    }
}
