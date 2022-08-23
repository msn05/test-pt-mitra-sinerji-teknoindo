<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use DataTables;
use App\Exceptions\ErrorException;
use App\Helpers\GenerateCode;
use App\Http\Requests\Customer\AddRequest as CustomerAddRequest;
use App\Http\Requests\Product\AddRequest;
use App\Http\Requests\Transaction\AddRequest as TransactionAddRequest;
use App\Models\Customer;
use App\Models\SaleDescription;
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $table = ['product', 'customer', 'transaction'];
        $form  =  [
            'product' => self::FormColumns([
                ['code', 'text', '', '', 'Code Product'],
                ['name', 'text', '', '', 'Name Product'],
                ['price', 'number', '', '', 'Price Product']
            ]),
            'customer' => self::FormColumns([
                ['name', 'text', '', '', 'Name Customer'],
                ['phone', 'number', '', '', 'Number phone']
            ]),
            'productOptionsStatus' =>
            self::FormColumns([
                ['codeTransacton', 'text', '', '', 'Code Transaction'],
                ['status', 'select', 'fa fa-text-width', '',  ['0' => 'Pending', '1' => 'Done', '2' => 'Delete'], '', 'md', 'status transaction'],
                ['reason', 'textarea', '', '', 'Reason']
            ]),
        ];
        // ddd(SaleDescription::with('product')->get());
        return view('transaction.index')->with(['table' => $table, 'forms' => $form]);
    }


    public function transactionData(Request $request)
    {
        if ($request->ajax()) {
            $model = Transaction::with([
                'customer' => function ($query) {
                    $query->select('id', 'name');
                }, 'saleDesc', 'saleDesc.product'
            ])->latest()->get()->toArray();
            $model = json_decode(json_encode($model), true);
            return DataTables::of($model)
                ->addIndexColumn()
                ->setRowClass(function ($row) {
                    return ($row['status']  == 0 ? 'alert-warning' : ($row['status'] == 1 ? 'alert-success' : 'alert-warning'));
                })
                ->editColumn('codeTransaction', function ($row) {
                    return $row['code'];
                })
                ->addColumn('CustomerName', function ($row) {
                    // ddd($row['customer']['name']);
                    return $row['customer']['name'];
                })
                ->editColumn('dateTransaction', function ($row) {
                    return date('d M Y', strtotime($row['date_of_sale']));
                })
                ->editColumn('totalQty', function ($row) {
                    $qty = collect($row['sale_desc'])->map(function ($val) {
                        return $val['qty'];
                    });
                    return array_sum($qty->toArray());
                })
                ->editColumn('subTotal', function ($row) {
                    $price = collect($row['sale_desc'])->map(function ($val) {
                        return (int)$val['grand_total'];
                    });
                    return array_sum($price->toArray());
                })
                ->editColumn('totaDiscountSale', function ($row) {
                    $discount = collect($row['sale_desc'])->map(function ($val) use ($row) {
                        if ($val['discount_pcs'] > 0)
                            return (int)$val['discount_pcs'] + (int)($row['discount']);
                    });
                    return array_sum($discount->toArray());
                })
                ->editColumn('shippingCost', function ($row) {
                    return (int)$row['shipping'];
                })
                ->escapeColumns(['codeTransaction', 'CustomerName', 'dateTransaction', 'totalQty', 'subTotal', 'totaDiscountSale', 'shippingCost'])
                ->removeColumn(['customer', 'product', 'sale_desc', 'id'])
                ->addColumn('action', function ($row) {
                    if ($row['status'] == 0) {
                        $btn =  '<button class="btn btn-warning btn-sm mr-2" id="edit" data-id="' . $row['code'] . '" data-route="transaction-update" data-name="options-transaction" ><i class="fa fa-pen"></i></button>';
                        $btn = $btn . '<button class=" btn btn-danger btn-sm"  id="delete" data-route="transaction-delete" data-id="' . $row['code'] . '" ><i class="fa fa-trash"></i></button>';
                        return $btn;
                    }
                })
                ->rawColumns(['action'])

                ->toJson();
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = GenerateCode::create('transaction', date('Ymd') . '-');
        $form  = [
            'transaction' =>
            self::FormColumns([
                ['codeTransaction', 'text', '', $code, 'Code Product'],
                ['date_of_sale', 'date', '', '', 'Date Sale'],
            ]),
            'customer' =>
            self::FormColumns([
                ['customer', 'select', 'fa fa-text-width', 'test-jp/customer-options', '', '', 'md', 'customer']
            ]),
            'dependencyCustomer' =>
            self::FormColumns([
                ['code', 'text', '', '', 'Code Customer'],
                ['name', 'text', '', '', 'Name Customer'],
                ['phone', 'number', '', '', 'Phone Customer'],
            ]),
            'product' =>
            self::FormColumns([
                ['product', 'select', 'fa fa-text-width', 'test-jp/product-options', '', '', 'md', 'product']
            ]),
            'dependencyProduct' =>
            self::FormColumns([
                ['codeProduct', 'text', '', '', 'Code Product'],
                ['name', 'text', '', '', 'Name Product'],
                ['price', 'number', '', '', 'Price Product'],
                ['discount', 'number', '', '', 'Discount(%)']
            ]),
            'transactionPrduct' =>
            self::FormColumns([
                ['qty', 'number', '', '', 'Qty Product'],
                ['total', 'text', '', '', 'Total Price'],
            ]),
        ];

        return view('transaction.add', compact('form'));
    }


    public function store(TransactionAddRequest $request)
    {
        $validate   = $request->validated();

        if (count($validate['CodeProduct']) != count(array_unique($validate['CodeProduct'])))
            throw new ErrorException("There product are the same in data.!");
        $Sale = new Transaction;
        $codeTransaction = $Sale::select('code')->where(['code' => $validate['codeTransaction']])->first();
        if (!is_null($codeTransaction)) throw new ErrorException("Same this code transaction.!");
        $dataTransaction = [
            'date_of_sale'  => date('Y-m-d H:i:s', strtotime($validate['dateTransaction'])),
            'code'          => $validate['codeTransaction'],
            'customer_id'   => Customer::select('id')->where(['code' => $validate['codeCustomer']])->first()->id,
            'sub_total'     => $validate['SubTotal'],
            'discount'      => $validate['TotalDiscount'],
            'shipping'      => $validate['shippingCost'],
            'grand_total'   => $validate['GrandTotal'],
        ];
        try {
            $codeTransaction = Transaction::create($dataTransaction);
            try {
                $SaleId = $Sale->select('id')->latest()->first();
                $SaleId = is_null($SaleId) ? 1 : $codeTransaction;
                $saleDesc = [];
                $product = Product::select('id', 'price')->whereIn('code', $validate['CodeProduct'])->get()->toArray();
                foreach ($validate['CodeProduct'] as $key => $value) {
                    $saleDesc[] = ['sale_id' => $codeTransaction->id, 'product_id' => $product[$key]['id'], 'discount_pcs' => $validate['DiscountProduct'][$key], 'qty' => $validate['QtyProduct'][$key], 'grand_total' => ((int)$product[$key]['price'] - ((int)$product[$key]['price'] * $validate['DiscountProduct'][$key] / 100)) * $validate['QtyProduct'][$key], 'price_before' => (int)$product[$key]['price']];
                }
                SaleDescription::insert($saleDesc);
                DB::commit();
                return response()->json(['message' => 'Successfully insert data.!'], 200);
            } catch (QueryException $th) {
                $Sale->delete($codeTransaction->id);
                DB::rollBack();
                throw new ErrorException("Failed add data sale.!");
            }
        } catch (QueryException $th) {
            DB::rollBack();
            throw new ErrorException("Failed add data sale.!");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    public function update(TransactionAddRequest $request, Transaction $transaction)
    {
        $validate = $request->validated();
        $transaction->status = $validate['status'];
        $transaction->reason = $validate['reason'];
        try {
            DB::beginTransaction();
            $transaction = $transaction->save();
            if (!$transaction) throw new ErrorException("Not update status transaction.!");
            return response()->json(['message' => 'Update status succesfully.!', 'table' => 'table-transaction'], 200);
        } catch (QueryException $th) {
            throw new ErrorException("Updated failed.!");
        }
    }


    public function destroy(Request $request)
    {
        try {
            $Transaction = Transaction::where(['code' => $request->id])->delete();
            if (!$Transaction) throw new ErrorException("Data not delete.!");
            return response()->json(['message' => 'Deleted data successfully.!', 'table' => 'table-transaction'], 200);
        } catch (QueryException $th) {
            throw new ErrorException("Failed deleted this data.!");
        }
    }

    // func product
    public function productData(Request $request)
    {
        if ($request->ajax()) {
            $model = Product::latest();
            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->escapeColumns(['name', 'code', 'price'])
                ->skipTotalRecords()
                ->addColumn('action', function ($row) {
                    $btn =  '<button class="btn btn-warning btn-sm mr-2" id="edit" data-id="' . $row->id . '" data-route="product-show" data-update="product-update" data-name="product"><i class="fa fa-pen"></i></button>';
                    $btn = $btn . '<button class=" btn btn-danger btn-sm"  id="delete" data-route="product-delete" data-id="' . $row->id . '" ><i class="fa fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])

                ->toJson();
        }
    }

    public function productAdd(AddRequest $request)
    {
        $validate = $request->validated();
        $product = new Product;
        $validDB = $product->where(['code' => $validate['code']])->first();
        if (!is_null($validDB)) throw new ErrorException('Data already where code.!');
        $product->name = $validate['name'];
        $product->code = Str::upper($validate['code']);
        $product->price = $validate['price'];
        try {
            $product->save();
            return response()->json(['message' => 'Successfully insert data.!', 'table' => 'table-product'], 200);
        } catch (QueryException $th) {
            throw new ErrorException("Failed insert data.!");
        }
    }

    public function productShow($id)
    {
        $product = Product::where(['id' => $id])->orWhere(['code' => $id])->first();
        $message = [
            'text'  => [
                'code'       => $product->code,
                'codeProduct' => $product->code,
                'name'       => $product->name,
            ],
            'number'  => [
                'price'      => (int)$product->price
            ]
        ];
        return response()->json(['message' => $message], 200);
    }

    public function productUpdate(Product $product, AddRequest $request)
    {
        $validate = $request->validated();
        if ($validate['code'] !== $product->code) {
            $productFilter = Product::where(['code' => $validate['code']])->first();
            if (!is_null($productFilter))
                throw new ErrorException("This code already. If you change check code product in table.!");
            $product->update($validate);
            return response()->json(['message' => 'Updated data successfully.!', 'table' => 'table-product'], 200);
        }
        if ($validate['code'] === $product->code)
            try {
                $product = $product->update($validate);
                if (!$product) throw new ErrorException("Failed updated data.!");
                return response()->json(['message' => 'Updated data successfully.!', 'table' => 'table-product'], 200);
            } catch (QueryException $th) {
                throw new ErrorException("Failed updated data.!");
            }
    }

    public function productDestroy(Request $request)
    {
        $product = Product::find($request->id);
        if (!$product) throw new ErrorException("Data not found.!");
        try {
            if ($product->salesDecs->count() > 1) throw new ErrorException("Data not deleted because this data use sale.!");
            $product->delete();
            return response()->json(['message' => 'Deleted this data successfully.!', 'table' => 'table-product']);
        } catch (QueryException $th) {
            throw new ErrorException("Failed deleted this data.!");
        }
    }

    public function productOptions(Request $request)
    {
        $term  = trim($request->search);
        if (empty($term)) return response()->json([]);
        if (!empty($term)) {
            $data = [];
            $model = Product::where('name', 'like', '%' . $term . '%')->orWhere(['id' => $term])->get();
            foreach ($model as $values) {
                $data[] = ['id' => $values->id,  'name' => $values->name];
            }
            return response()->json($data);
        }
    }

    // customer
    public function customerData(Request $request)
    {
        if ($request->ajax()) {
            $model = Customer::latest();
            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->escapeColumns(['name', 'code', 'phone'])
                ->skipTotalRecords()
                ->addColumn('action', function ($row) {
                    $btn =  '<button class="btn btn-warning btn-sm mr-2" id="edit" data-id="' . $row->id . '" data-route="customer-show" data-update="customer-update" data-name="customer"><i class="fa fa-pen"></i></button>';
                    $btn = $btn . '<button class=" btn btn-danger btn-sm"  id="delete" data-route="customer-delete" data-id="' . $row->id . '" ><i class="fa fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])

                ->toJson();
        }
    }

    public function customerAdd(CustomerAddRequest $request)
    {

        $validate   = $request->validated();
        $customer   = new Customer;
        $validDB    = $customer->where(['phone' => $validate['phone']])->first();
        if (!is_null($validDB)) throw new ErrorException('Data already where code.!');
        $customer->name     = Str::ucfirst($validate['name']);
        $customer->phone    = $validate['phone'];
        $customer->code     = GenerateCode::create('customer', 'C');
        try {
            $customer->save();
            return response()->json(['message' => 'Successfully insert data.!', 'table' => 'table-customer'], 200);
        } catch (QueryException $th) {
            throw new ErrorException("Failed insert data.!");
        }
    }

    public function customerShow(Customer $customer)
    {
        $message = [
            'text'  => [
                'name'       => $customer->name,
                'code'       => $customer->code,
            ],
            'number'  => [
                'phone'      => (int)'0' . $customer->phone
            ]
        ];
        return response()->json(['message' => $message], 200);
    }

    public function customerUpdate(Customer $customer, CustomerAddRequest $request)
    {
        $validate = $request->validated();
        $customer->name = $validate['name'];
        if ((int)$validate['phone'] !== $customer->phone) {
            $customertFilter = Customer::where(['phone' => $validate['phone']])->first();
            $customer->phone = $validate['phone'];
            if (!is_null($customertFilter))
                throw new ErrorException("This phone number already. If you change check phone customer in table.!");
            $customer = $customer->save();
            return response()->json(['message' => 'Updated data successfully.!', 'table' => 'table-customer'], 200);
        }
        if ((int)$validate['phone'] === $customer->phone)
            try {
                $customer = $customer->save();
                if (!$customer) throw new ErrorException("Failed updated data.!");
                return response()->json(['message' => 'Updated data successfully.!', 'table' => 'table-customer'], 200);
            } catch (QueryException $th) {
                throw new ErrorException("Failed updated data.!");
            }
    }

    public function customerDestroy(Request $request)
    {
        $customer = Customer::find($request->id);
        if (!$customer) throw new ErrorException("Data not found.!");
        try {
            if ($customer->salesDecs->count() > 1) throw new ErrorException("Data not deleted because this data use sale.!");
            $customer->delete();
            return response()->json(['message' => 'Deleted this data successfully.!', 'table' => 'table-customer']);
        } catch (QueryException $th) {
            throw new ErrorException("Failed deleted this data.!");
        }
    }

    public function customerOptions(Request $request)
    {
        $term  = trim($request->search);
        if (empty($term)) return response()->json([]);
        if (!empty($term)) {
            $data = [];
            $model = Customer::where('name', 'like', '%' . $term . '%')->orWhere(['id' => $term])->get();
            foreach ($model as $values) {
                $data[] = ['id' => $values->id, 'code' => $values->code, 'name' => $values->name, 'phone' => $values->phone];
            }
            return response()->json($data);
        }
    }
}
