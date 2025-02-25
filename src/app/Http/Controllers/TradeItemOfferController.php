<?php

namespace App\Http\Controllers;

use App\Http\Requests\TradeItemOfferRequest;
use App\Http\Requests\TradeItemOfferUpdateRequest;
use App\Http\Resources\QueueJob as QueueJobResource;
use App\Http\Resources\TradeItemOffer as TradeItemOfferResource;
use App\Jobs\TradeItemOfferDelete as TradeItemOfferDeleteJob;
use App\Jobs\TradeItemOfferStore;
use App\Jobs\TradeItemOfferUpdate as TradeItemOfferUpdateJob;
use App\Models\TradeItemOffer;
use App\Repositories\TradeItemOfferRepository;
use App\Services\Filters\TradeItemOfferFilter;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TradeItemOfferController
 * @package App\Http\Controllers
 */
class TradeItemOfferController extends Controller
{
    private TradeItemOfferRepository $tradeItemOfferRepository;

    /**
     * TradeItemOfferController constructor.
     * @param TradeItemOfferRepository $tradeItemOfferRepository
     */
    public function __construct(TradeItemOfferRepository $tradeItemOfferRepository)
    {
        $this->tradeItemOfferRepository = $tradeItemOfferRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/product/terms",
     *      tags={"Product Terms"},
     *      summary="List all Trade Item Offers",
     *      @OA\Parameter(
     *          name="filter[id]",
     *          description="Selected Id",
     *          required=false,
     *           in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter[trade_item_id]",
     *          description="Selected Trade Item Id",
     *          required=false,
     *           in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter[is_active]",
     *          description="Is Active",
     *          required=false,
     *           in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter[is_warehouse_item]",
     *          description="Is Warehouse Item",
     *          required=false,
     *           in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter[customer_group_id]",
     *          description="Selected Customer Group Id",
     *          required=false,
     *           in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter[import_status]",
     *          description="Selected Import Status",
     *          required=false,
     *           in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter[stock_keeping_unit]",
     *          description="Selected Stock Keeping Unit",
     *          required=false,
     *           in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter[old_stock_keeping_unit]",
     *          description="Old Stock Keeping Unit",
     *          required=false,
     *           in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter[supplier_id]",
     *          description="Selected Supplier Id",
     *          required=false,
     *           in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter[supplier_trade_item_number]",
     *          description="Selected Supplier Trade Item Number",
     *          required=false,
     *           in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter[created_after]",
     *          description="Created After",
     *          required=false,
     *           in="query",
     *          @OA\Schema(
     *              type="date"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter[created_before]",
     *          description="Created Before",
     *          required=false,
     *           in="query",
     *          @OA\Schema(
     *              type="date"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter[updated_after]",
     *          description="Updated After",
     *          required=false,
     *           in="query",
     *          @OA\Schema(
     *              type="date"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter[updated_before]",
     *          description="Updated Before",
     *          required=false,
     *           in="query",
     *          @OA\Schema(
     *              type="date"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *       ),
     *      security= {{"basic": {}} },
     * )
     * @param TradeItemOfferFilter $filters
     * @return \Illuminate\Http\JsonResponse|AnonymousResourceCollection
     */
    public function index(Request $request, TradeItemOfferFilter $filters)
    {
        $validator = Validator::make($request->request->all(), [
            'filter' => ['array','keys_in_columns:trade_item_offer'],
            'page' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->get('errors'), 400);
        } else {
            if ($filters->getFilter('limit')) {
                return TradeItemOfferResource::collection(TradeItemOffer::applyFilters($filters)->get());
            } else {
                return TradeItemOfferResource::collection(TradeItemOffer::applyFilters($filters)->paginate());
            }
        }
    }

    /**
     * @OA\Get(
     *      path="/api/v1/product/terms/{id}",
     *      tags={"Product Terms"},
     *      summary="Show Trade Item Offer",
     *      @OA\Parameter(
     *          name="id",
     *          description="Selected Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      security= {{"basic": {}} },
     * )
     * @param TradeItemOffer $id
     * @return TradeItemOfferResource
     */
    public function show(TradeItemOffer $id)
    {
        return new TradeItemOfferResource($id);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/product/terms",
     *     tags={"Product Terms"},
     *     summary="Add a new Trade Item Offer",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="Internal Name",
     *                     property="internal_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="Trade Item Id",
     *                     property="trade_item_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Supplier Id",
     *                     property="supplier_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Supplier Trade Item Number",
     *                     property="supplier_trade_item_number",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="Customer Group Id",
     *                     property="customer_group_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Net Price",
     *                     property="net_price",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     description="Currency",
     *                     property="currency",
     *                     type="string",
     *                     maxLength=3
     *                 ),
     *                 @OA\Property(
     *                     description="Sales Unit",
     *                     property="sales_unit",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     description="Old Stock Keeping Unit",
     *                     property="old_stock_keeping_unit",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="Maximum Delivery Time",
     *                     property="maximum_delivery_time",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Minimum Delivery Time",
     *                     property="minimum_delivery_time",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Delivery Time Unit",
     *                     property="delivery_time_unit",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Maximum Order Quantity",
     *                     property="maximum_order_quantity",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Minimum Order Quantity",
     *                     property="minimum_order_quantity",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Is Active",
     *                     property="is_active",
     *                     type="boolean"
     *                 ),
     *                 @OA\Property(
     *                     description="Is Warehouse Item",
     *                     property="is_warehouse_item",
     *                     type="boolean"
     *                 ),
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=202,
     *          description="Accepted",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      ),
     *      security= {{"basic": {}} },
     * )
     *
     * @param TradeItemOfferRequest $request
     * @return QueueJobResource
     * @throws Exception
     */
    public function store(TradeItemOfferRequest $request)
    {
        $requestData = $request->all();
        unset($requestData['import_status'], $requestData['stock_keeping_unit']);

        $tradeItemOfferStore = new TradeItemOfferStore($requestData);
        $createJobQueue = $tradeItemOfferStore->onQueue(TradeItemOfferStore::IMPORT_QUEUE_NAME);

        return new QueueJobResource($this->getJob($createJobQueue));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/product/terms/{id}",
     *     tags={"Product Terms"},
     *     summary="Update an existing Trade Item Offer",
     *      @OA\Parameter(
     *         description="ID Of Trade Item Offer To Update",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *           format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="Trade Item Id",
     *                     property="trade_item_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Internal Name",
     *                     property="internal_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="Trade Item Id",
     *                     property="trade_item_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Supplier Id",
     *                     property="supplier_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Supplier Trade Item Number",
     *                     property="supplier_trade_item_number",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="Customer Group Id",
     *                     property="customer_group_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Net Price",
     *                     property="net_price",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     description="Currency",
     *                     property="currency",
     *                     type="string",
     *                     maxLength=3
     *                 ),
     *                 @OA\Property(
     *                     description="Sales Unit",
     *                     property="sales_unit",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     description="Old Stock Keeping Unit",
     *                     property="old_stock_keeping_unit",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="Maximum Delivery Time",
     *                     property="maximum_delivery_time",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Minimum Delivery Time",
     *                     property="minimum_delivery_time",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Delivery Time Unit",
     *                     property="delivery_time_unit",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Maximum Order Quantity",
     *                     property="maximum_order_quantity",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Minimum Order Quantity",
     *                     property="minimum_order_quantity",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Is Active",
     *                     property="is_active",
     *                     type="boolean"
     *                 ),
     *                 @OA\Property(
     *                     description="Is Warehouse Item",
     *                     property="is_warehouse_item",
     *                     type="boolean"
     *                 ),
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=202,
     *          description="Accepted",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      ),
     *      security= {{"basic": {}} },
     * )
     *
     * @param $id
     * @param TradeItemOfferUpdateRequest $request
     * @return QueueJobResource
     * @throws Exception
     */
    public function update(string $id, TradeItemOfferUpdateRequest $request)
    {
        $tradeItemOffer = $this->tradeItemOfferRepository->find($id);
        $requestData = $request->all();

        unset($requestData['import_status'], $requestData['stock_keeping_unit']);

        $tradeItemOfferUpdateJob = new TradeItemOfferUpdateJob($tradeItemOffer, $requestData);
        $updateJobQueue = $tradeItemOfferUpdateJob->onQueue(TradeItemOfferUpdateJob::IMPORT_QUEUE_NAME);

        return new QueueJobResource($this->getJob($updateJobQueue));
    }

    /**
     * /**
     * @OA\Delete(
     *      path="/api/v1/product/terms/{id}",
     *      operationId="deleteTradeItemoffer",
     *      tags={"Product Terms"},
     *      summary="Delete Trade Item Offer",
     *      description="Deletes trade item offer",
     *      @OA\Parameter(
     *          name="id",
     *          description="Offer Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Accepted",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      ),
     *      security= {{"basic": {}} },
     * )
     *
     * @param $id
     * @return QueueJobResource
     * @throws Exception
     */
    public function destroy($id)
    {
        $tradeItemOfferDeleteJob = new TradeItemOfferDeleteJob($this->tradeItemOfferRepository->find($id));
        $deleteJobQueue = $tradeItemOfferDeleteJob->onQueue(TradeItemOfferDeleteJob::DELETE_QUEUE_NAME);

        return new QueueJobResource($this->getJob($deleteJobQueue));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/product/terms/update-import-status/{id}",
     *     tags={"Product Terms"},
     *     summary="Update an existing Trade Item Offer",
     *      @OA\Parameter(
     *         description="ID Of Trade Item Offer To Update",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *           format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
      *                 @OA\Property(
     *                     description="Import status",
     *                     property="import_status",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=202,
     *          description="Accepted",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      ),
     *      security= {{"basic": {}} },
     * )
     *
     * @param $id
     * @param Request $request
     * @return QueueJobResource
     * @throws Exception
     */
    public function updateImportStatus(string $id, Request $request)
    {
        $tradeItemOfferData = [];
        $requestArray = json_decode($request->getContent(), true);
        $allowed = ['import_status'];

        $importStatus = array_filter(
            $requestArray,
            function ($key) use ($allowed) {
                return in_array($key, $allowed);
            },
            ARRAY_FILTER_USE_KEY
        );
        $tradeItemOffer = $this->tradeItemOfferRepository->find($id);
        if ($tradeItemOffer) {
            $tradeItemOfferData = array_merge($tradeItemOffer->attributesToArray(), $importStatus);
        }

        $tradeItemOfferUpdateJob = new TradeItemOfferUpdateJob($tradeItemOffer, $tradeItemOfferData);
        $updateJobQueue = $tradeItemOfferUpdateJob->onQueue(TradeItemOfferUpdateJob::IMPORT_QUEUE_NAME);

        return new QueueJobResource($this->getJob($updateJobQueue));
    }

    /**
     * @param $jobQueue
     * @return object
     */
    private function getJob($jobQueue)
    {
        return (object) [
            'id'    => $this->dispatch($jobQueue),
            'queue' => $jobQueue->queue
        ];
    }
}
