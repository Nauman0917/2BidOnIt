<?php

namespace App\Http\Controllers;

use App\Models\AuctionItem;
use App\Models\LotItemImage;
use App\Models\LotItems;
use App\Models\Stones;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LotItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        $title = trans('app.items');
        // $ads = Ad::with('city', 'country', 'state')->whereStatus('1')->orderBy('id', 'desc')->paginate(20);
        $items = LotItems::where('user_id', $user_id)->with('stones', 'images')->orderBy('id', 'asc')->paginate(20);

        return view('lot_items.index', compact('title', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_id = Auth::id();
        $title = trans('app.ad_add_items');

        $last_catelog_number = LotItems::select('internalCatalogNumber', 'id')
            ->orderBy('id', 'desc')
            ->first();

        $new_catalog_number = '';
        $formated_id = str_pad($user_id, 2, '0', STR_PAD_LEFT);

        if ($last_catelog_number && preg_match('/([A-Z]{1,3})(\d+)-\d{2}/', $last_catelog_number->internalCatalogNumber, $matches)) {

            $number_sequence = intval($matches[2]) + 1;
            $sequence_length = strlen($matches[2]);
            $new_sequence = str_pad($number_sequence, $sequence_length, '0', STR_PAD_LEFT);

            $new_catalog_number = $new_sequence . "-" . $formated_id;

        } else {
            $total_records = LotItems::count();
            $formated_records = str_pad($total_records, 3, '0', STR_PAD_LEFT);
            $new_catalog_number = $formated_records . "-" . $formated_id;
        }

        return view('lot_items.create', compact('title', 'new_catalog_number'));
    }

    public function store(Request $request)
    {
        $rules = [
            'item_name' => 'required|string|min:3|max:50',
            'item_type' => 'required|string',
            'item_size' => 'required|string',
            'item_weight' => 'required|numeric',
            'weight_unit' => 'required|string',
            'metal_type' => 'required|string',
            'metal_color' => 'required|string',
            'total_gem_weight' => 'required|numeric',
            'detailed_description' => 'required',
            'reserve_price' => 'required|numeric',
            'appraised_value' => 'required|numeric',
            'startPrice' => 'numeric',
            'minEstimate' => 'numeric',
            'maxEstimate' => 'numeric',
            'postSalePrice' => 'numeric',
            'internalCatalogNumber' => 'required|string',

            'certified_by' => 'array|nullable',
            'certified_by.*' => 'nullable|string',
            'certification_picture' => 'array|nullable',
            'certification_picture.*' => 'nullable|file|mimes:jpg,jpeg,png',
            'images' => 'array|nullable',
            'images.*' => 'nullable|file|mimes:jpg,jpeg,png',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $itemData = [
            'user_id' => Auth::id(),
            'item_name' => $request->item_name,
            'item_type' => $request->item_type,
            'item_size' => $request->item_size,
            'item_weight' => $request->item_weight,
            'weight_unit' => $request->weight_unit,
            'metal_type' => $request->metal_type,
            'metal_color' => $request->metal_color,
            'total_gem_weight' => $request->total_gem_weight,
            'detailed_description' => $request->detailed_description,
            'reserve_price' => $request->reserve_price,
            'appraised_value' => $request->appraised_value,
            'startPrice' => $request->startPrice,
            'minEstimate' => $request->minEstimate,
            'maxEstimate' => $request->maxEstimate,
            'postSalePrice' => $request->postSalePrice,
            'internalCatalogNumber' => $request->internalCatalogNumber,
        ];

        $item = LotItems::create($itemData);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if (!($image instanceof \Illuminate\Http\UploadedFile)) {
                    return redirect()->back()->withInput($request->input())->with('error', 'Invalid image file');
                }

                $valid_extensions = ['jpg', 'jpeg', 'png'];
                if (!in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions)) {
                    return redirect()->back()->withInput($request->input())->with('error', 'Only .jpg, .jpeg and .png extensions are allowed');
                }

                $image_name = strtolower(time() . Str::random(5)) . '.' . $image->getClientOriginalExtension();
                $folderName = 'uploads/items/item_' . $item->id;
                $imageFileName = $folderName . '/' . $image_name;

                try {
                    $disk = 'images';
                    Storage::disk($disk)->put($imageFileName, file_get_contents($image));
                    $imageUrl = Storage::disk($disk)->url($imageFileName);

                    LotItemImage::create([
                        'lot_items_id' => $item->id,
                        'image' => $imageUrl,
                        'disk' => $disk,
                        'path' => $imageFileName,
                    ]);
                } catch (\Exception $e) {
                    return redirect()->back()->withInput($request->input())->with('error', $e->getMessage());
                }
            }
        }

        $bar_code_folder = 'uploads/items/item_' . $item->id;
        $bar_code_data = generate_bar_code(
            $item->name,
            $item->detailed_description,
            $item->id,
            'images',
            $bar_code_folder
        );
        if (!empty($bar_code_data)) {
            $item->update([
                'serial_number' => $bar_code_data['serial_number'],
                'bar_code_image' => $bar_code_data['image'],
                'disk' => 'images',
                'path' => $bar_code_data['path'],
            ]);
        }

        if (isset($request->stone_type[0]) && isset($request->stone_weight[0])) {
            foreach ($request->stone_type as $key => $stoneType) {
                if ($stoneType && isset($request->stone_weight[$key])) {
                    try {
                        $imageUrl = null;

                        if ($request->hasFile('certification_picture.' . $key)) {
                            $image = $request->file('certification_picture.' . $key);

                            $image_name = strtolower(time() . Str::random(5)) . '.' . $image->getClientOriginalExtension();
                            $folderName = 'uploads/items/item_' . $item->id . '/stones';
                            $imageFileName = $folderName . '/' . $image_name;

                            $disk = 'images';
                            Storage::disk($disk)->put($imageFileName, file_get_contents($image));
                            $imageUrl = Storage::disk($disk)->url($imageFileName);
                        }

                        Stones::create([
                            'item_id' => $item->id,
                            'stone_type' => $stoneType,
                            'stone_weight' => $request->stone_weight[$key],
                            'stone_weight_exact' => $request->stone_weight_exact[$key] ?? null,
                            'stone_certified' => $request->stone_certified[$key] ?? null,
                            'stone_shape' => $request->stone_shape[$key] ?? null,
                            'stone_color' => $request->stone_color[$key] ?? null,
                            'stones_quantity' => $request->stones_quantity[$key] ?? null,
                            'stone_clarity' => $request->stone_clarity[$key] ?? null,
                            'certification_number' => $request->certification_number[$key] ?? null,
                            'certified_by' => $request->certified_by[$key] ?? null,
                            'certification_picture' => $imageUrl,
                        ]);
                    } catch (\Exception $e) {
                        return redirect()->back()->withInput($request->input())->with('error', $e->getMessage());
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Item successfully created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = trans('app.view_item');

        $item = LotItems::whereId($id)->with('images', 'stones')->first();

        if (!$item) {
            return view('error_404');
        }

        return view('lot_items.view', compact('title', 'item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $title = trans('app.edit_ad');
        $item = LotItems::find($id);

        $stone_count = count($item->stones);

        if (!$item) {
            return view('admin.error.error_404');
        }

        if ($item->user_id != $user_id) {
            return view('admin.error.error_404');
        }

        return view('lot_items.edit', compact('title', 'item', 'stone_count'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        $item = LotItems::find($id);
        $user_id = Auth::id();

        if ($item->user_id != $user_id) {
            return view('admin.error.error_404');
        }

        $rules = [
            'item_name' => 'required|string|min:3|max:50',
            'item_type' => 'required|string',
            'item_size' => 'required|string',
            'item_weight' => 'required|numeric',
            'weight_unit' => 'required|string',
            'metal_type' => 'required|string',
            'metal_color' => 'required|string',
            'total_gem_weight' => 'required|numeric',
            'detailed_description' => 'required',
            'reserve_price' => 'required|numeric',
            'appraised_value' => 'required|numeric',
            'startPrice' => 'numeric',
            'minEstimate' => 'numeric',
            'maxEstimate' => 'numeric',
            'postSalePrice' => 'numeric',
        ];
        $this->validate($request, $rules);

        $itemData = [
            'user_id' => $user_id,
            'item_name' => $request->item_name,
            'item_type' => $request->item_type,
            'item_size' => $request->item_size,
            'item_weight' => $request->item_weight,
            'weight_unit' => $request->weight_unit,
            'metal_type' => $request->metal_type,
            'metal_color' => $request->metal_color,
            'total_gem_weight' => $request->total_gem_weight,
            'detailed_description' => $request->detailed_description,
            'reserve_price' => $request->reserve_price,
            'appraised_value' => $request->appraised_value,
            'startPrice' => $request->startPrice,
            'minEstimate' => $request->minEstimate,
            'maxEstimate' => $request->maxEstimate,
            'postSalePrice' => $request->postSalePrice,
        ];

        if ($request->item_name !== $item->item_name || $request->detailed_description !== $item->detailed_description) {
            $bar_code_folder = 'uploads/items/item_' . $item->id;
            $bar_code_data = generate_bar_code(
                $request->item_name,
                $request->detailed_description,
                $item->id,
                'images',
                $bar_code_folder
            );

            if (!empty($bar_code_data)) {
                $itemData['serial_number'] = $bar_code_data['serial_number'];
                $itemData['bar_code_image'] = $bar_code_data['image'];
                $itemData['disk'] = 'images';
                $itemData['path'] = $bar_code_data['path'];
            }
        }

        $item->update($itemData);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if (!($image instanceof \Illuminate\Http\UploadedFile)) {
                    return redirect()->back()->withInput($request->input())->with('error', 'Invalid image file');
                }

                $valid_extensions = ['jpg', 'jpeg', 'png'];
                if (!in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions)) {
                    return redirect()->back()->withInput($request->input())->with('error', 'Only .jpg, .jpeg and .png extensions are allowed');
                }

                $image_name = strtolower(time() . Str::random(5)) . '.' . $image->getClientOriginalExtension();
                $folderName = 'uploads/items/item_' . $item->id;
                $imageFileName = $folderName . '/' . $image_name;

                try {
                    $disk = 'images';
                    Storage::disk($disk)->put($imageFileName, file_get_contents($image));
                    $imageUrl = Storage::disk($disk)->url($imageFileName);

                    LotItemImage::create([
                        'lot_items_id' => $item->id,
                        'image' => $imageUrl,
                        'disk' => $disk,
                        'path' => $imageFileName,
                    ]);
                } catch (\Exception $e) {
                    return redirect()->back()->withInput($request->input())->with('error', $e->getMessage());
                }
            }
        }

        if (isset($request->stone_type) && isset($request->stone_weight[0])) {
            foreach ($request->stone_type as $key => $stoneType) {

                if ($stoneType && $request->stone_weight[$key]) {
                    if ($request->stone_id && isset($request->stone_id[$key])) {

                        $stone = Stones::find($request->stone_id[$key]);

                        if ($stone->item_id == $item->id) {
                            $imageUrl = null;

                            if ($request->hasFile('certification_picture.' . $key)) {
                                $image = $request->file('certification_picture.' . $key);

                                $image_name = strtolower(time() . Str::random(5)) . '.' . $image->getClientOriginalExtension();
                                $folderName = 'uploads/items/item_' . $item->id . '/stones';
                                $imageFileName = $folderName . '/' . $image_name;

                                $disk = 'images';
                                Storage::disk($disk)->put($imageFileName, file_get_contents($image));
                                $imageUrl = Storage::disk($disk)->url($imageFileName);
                            }
                            $stone_data = [
                                'item_id' => $item->id,
                                'stone_type' => $stoneType,
                                'stone_weight' => $request->stone_weight[$key],
                                'stone_certified' => $request->stone_certified[$key],
                                'stone_shape' => $request->stone_shape[$key],
                                'stone_color' => $request->stone_color[$key],
                                'stones_quantity' => $request->stones_quantity[$key],
                                'stone_clarity' => $request->stone_clarity[$key],
                                'certification_number' => $request->certification_number[$key] ?? null,
                                'certified_by' => $request->certified_by[$key] ?? null,
                                'certification_picture' => $imageUrl,
                            ];

                            $stone->update($stone_data);
                        }
                    } else {
                        $imageUrl = null;

                        if ($request->hasFile('certification_picture.' . $key)) {
                            $image = $request->file('certification_picture.' . $key);

                            $image_name = strtolower(time() . Str::random(5)) . '.' . $image->getClientOriginalExtension();
                            $folderName = 'uploads/items/item_' . $item->id . '/stones';
                            $imageFileName = $folderName . '/' . $image_name;

                            $disk = 'images';
                            Storage::disk($disk)->put($imageFileName, file_get_contents($image));
                            $imageUrl = Storage::disk($disk)->url($imageFileName);
                        }

                        Stones::create([
                            'item_id' => $item->id,
                            'stone_type' => $stoneType,
                            'stone_weight' => $request->stone_weight[$key],
                            'stone_certified' => $request->stone_certified[$key],
                            'stone_shape' => $request->stone_shape[$key],
                            'stone_color' => $request->stone_color[$key],
                            'stones_quantity' => $request->stones_quantity[$key],
                            'stone_clarity' => $request->stone_clarity[$key],
                            'certification_number' => $request->certification_number[$key] ?? null,
                            'certified_by' => $request->certified_by[$key] ?? null,
                            'certification_picture' => $imageUrl,
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', trans('app.ad_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $item = LotItems::whereId($id)->first();

        if ($item) {
            $media = LotItemImage::where('lot_items_id', $item->id)->get();
            if ($media->count() > 0) {
                foreach ($media as $image) {
                    $storage = Storage::disk("images");
                    if ($storage->has($image->image)) {
                        $storage->delete($image);
                    }
                    $image->delete();
                }
            }

            Stones::where('item_id', $item->id)->delete();
            AuctionItem::where('lot_items_id', $item->id)->delete();

            $item->delete();

            return ['success' => 1, 'msg' => trans('app.media_deleted_msg')];
        }

        return ['success' => 0, 'msg' => trans('app.error_msg')];
    }

    /**
     * @return array
     */
    public function deleteMedia(Request $request)
    {
        $media_id = $request->img_id;
        $item_id = $request->item_id;

        try {

            $media = LotItemImage::find($media_id);

            if ($media->lot_items_id == $item_id) {

                $storage = Storage::disk("images");
                if ($storage->has($media->image)) {
                    $storage->delete($media->media_name);
                }
            }

            $media->delete();

            return ['success' => 1, 'msg' => trans('app.media_deleted_msg')];
        } catch (Exception $error) {
            return ['error' => 0, 'msg' => $error->getMessage()];
        }
    }

    public function deleteStone(Request $request)
    {
        $stone_id = $request->stone_id;
        try {
            $media = Stones::find($stone_id);

            $media->delete();

            return ['success' => 1, 'msg' => trans('app.stone_deleted_msg')];
        } catch (Exception $error) {
            return ['error' => 0, 'msg' => $error->getMessage()];
        }
    }

    public function generateInvoicePdf($id)
    {
        $item = LotItems::find($id);

        $pdf = PDF::loadView('lot_items.item_pdf', compact('item'));

        // $pdf = PDF::loadView('lot_items.item_pdf', compact('item'))
        //   ->setPaper('a4', 'landscape')
        //   ->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->download('item.pdf');
    }

    public function getImageBase64(Request $request)
    {
        $imagePath = $request->input('image_path');

        // Retrieve the image content from S3
        if (Storage::disk('images')->exists($imagePath)) {
            $image = Storage::disk('images')->get($imagePath);
            $mimeType = Storage::disk('images')->mimeType($imagePath);

            $base64 = base64_encode($image);

            return response()->json(['image' => 'data:' . $mimeType . ';base64,' . $base64]);
        } else {
            return response()->json(['error' => 'Image not found.'], 404);
        }
    }

    public function saveCroppedImage(Request $request)
    {
        $user_id = Auth::id();
        $imageData = $request->input('image');
        $image_model = $request->input('imageModel');
        $parent_id = $request->input('parent_id');
        $image_id = $request->input('imageId');

        if ($image_model == "LotItemImage") {
            $image_modal = LotItemImage::where('id', $image_id)->first();
            $item = LotItems::where('id', $parent_id)->first();
        }

        if (strpos($imageData, ';base64,') !== false) {
            list($type, $imageData) = explode(';', $imageData);
            list(, $imageData) = explode(',', $imageData);

            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                return response()->json(['error' => 'Invalid base64 image data.'], 400);
            }
        } else {
            return response()->json(['error' => 'Invalid image format.'], 400);
        }

        $image_name = 'cropped_image_' . time() . '.png';
        $folderName = 'uploads/items/item_' . $item->id;
        $imageFileName = $folderName . '/' . $image_name;

        $disk = 'images';

        // Store the decoded image data
        Storage::disk($disk)->put($imageFileName, $imageData);
        $imageUrl = Storage::disk($disk)->url($imageFileName);

        $image_modal->image = $imageUrl;
        $image_modal->path = $imageFileName;
        $image_modal->disk = $disk;
        $image_modal->update();

        return response()->json(['success' => 'Image saved successfully!', 'image_path' => 'images/' . $imageUrl]);
    }

    public function export()
    {
        $items = LotItems::with('images')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add header row
        $header = [
            'itemIndex',
            'internalCatalogNumber',
            'itemName',
            'itemType',
            'sellerNumber',
            'startPrice',
            'reservedPrice',
            'minEstimate',
            'maxEstimate',
            'postSalePrice',
            'mainLangName',
            'mainLangDesc',
            'mainLangAuthor',
            'mainLangDetails',
            'mainLangConditionReport',
            'mainLangCategory',
            'secondLangName',
            'secondLangDesc',
            'secondLangAuthor',
            'secondLangDetails',
            'secondLangConditionReport',
            'secondLangCategory',
            'condition',
            'width',
            'height',
            'depth',
            'widthWithFrame',
            'heightWithFrame',
            'lengthUnit',
            'weight',
            'weightUnit',
            'numberOfUnits',
            'tag1',
            'tag2',
            'eroticContent',
            'location',
            'extraInfo',
            'largeDisplay',
            'fullPriceVat',
            'notForNormalShipment',
            'postSaleDisabled',
            'hidden',
            'removedFromAuction',
            'picsBase',
            'pics',
            'videoUrl',
            'lotUrl',
            'adminNotes',
            'metalType',
            'metalColor',
            'totalGemWeight',
            'detailedDescription',
            'itemSize',
        ];
        $sheet->fromArray($header, null, 'A1');

        // Add data rows
        foreach ($items as $index => $item) {
            $pics = $item->images->pluck('image')->implode(',');

            $data = [
                $index + 1,
                $item->internalCatalogNumber,
                $item->item_name,
                $item->item_type,
                $item->user_id,
                $item->startPrice,
                $item->reserve_price,
                $item->minEstimate,
                $item->maxEstimate,
                $item->postSalePrice,
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                $item->item_weight,
                $item->weight_unit,
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                $pics,
                "",
                "",
                "",
                $item->metal_type,
                $item->metal_color,
                $item->total_gem_weight,
                $item->detailed_description,
                $item->item_size,
            ];
            $sheet->fromArray($data, null, 'A' . ($index + 2));
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'items.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="items.xlsx"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_items' => 'file|required',
        ]);

        if ($request->delete_old_items == "1") {
            LotItems::truncate();
            LotItemImage::truncate();
            Stones::truncate();
            AuctionItem::truncate();
        }

        $file = $request->file('import_items');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $user_id = Auth::id();

        // Define required columns and their indexes
        $requiredColumns = [
            2 => 'item_name',
            3 => 'item_type',
            5 => 'startPrice',
            6 => 'reserve_price',
            7 => 'minEstimate',
            8 => 'maxEstimate',
            10 => 'postSalePrice',
            30 => 'weight',
            31 => 'weightUnit',
            49 => 'metal_type',
            50 => 'metal_color',
            51 => 'total_gem_weight',
            53 => 'item_size',
        ];

        // Skip the header row
        foreach (array_slice($rows, 1) as $rowIndex => $row) {
            // Check for required columns
            foreach ($requiredColumns as $index => $columnName) {
                if (empty($row[$index])) {
                    return response()->json([
                        'error' => "Row " . ($rowIndex + 2) . " has an empty required field: " . $columnName,
                    ], 400);
                }
            }

            // Create the lot item
            $lotItem = LotItems::create([
                'internalCatalogNumber' => $row[1],
                'item_name' => $row[2],
                'item_type' => $row[3],
                'user_id' => $user_id,
                'startPrice' => $row[5],
                'reserve_price' => $row[6],
                'minEstimate' => $row[7],
                'maxEstimate' => $row[8],
                'postSalePrice' => $row[9],
                'item_weight' => $row[29],
                'weight_unit' => $row[30],
                'metal_type' => $row[48],
                'metal_color' => $row[49],
                'total_gem_weight' => $row[50],
                'detailed_description' => $row[51] ?? '',
                'item_size' => $row[52],
            ]);

            // Handle images
            $picsArray = explode(',', $row[45]);

            foreach ($picsArray as $pic) {
                if ($pic !== "") {
                    LotItemImage::create([
                        'image' => $pic,
                        'lot_items_id' => $lotItem->id,
                        'disk' => 'images',
                        'path' => $pic,
                    ]);
                }
            }
        }

        return response()->json(['success' => 'Items imported successfully.']);
    }

    // public function exportInLocalFormat()
    // {
    //     $items = LotItems::with('images', 'stones')->get();

    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     $header = [
    //         '#', 'User Id', 'Item Name', 'Detailed Description', 'Item Weight', 'Weight Unit', 'Item Type', 'Metal Type', 'Metal Color', 'Item Size', 'Total Gem Weight', 'Appraised Value', 'Reserve Price', 'Internal Catalog Number', 'Start Price', 'Min Estimate', 'Max Estimate', 'Post Sale Price', 'Item Tag', 'Serial Number', 'Disk', 'Path', 'Images', 'Images Disk', 'Images Path', 'Total Stones', 'Gem Type', 'Gem Weight', 'Gem Shape', 'Gem Color', 'Stones Quantity', 'Gem Clarity', 'Gem Certified', 'Certification Number', 'Certified by', 'Certification Picture', 'Stone Disk', 'Stone Path',
    //     ];
    //     $sheet->fromArray($header, null, 'A1');

    //     foreach ($items as $index => $item) {
    //         $pics = $item->images->pluck('image')->map(function($path) {
    //             return str_replace('\\', '/', $path);
    //         })->implode(',');

    //         $image_disk = $item->images->pluck('disk')->implode(',');
    //         $image_path = $item->images->pluck('path')->map(function($path) {
    //             return str_replace('\\', '/', $path);
    //         })->implode(',');

    //         $total_stones_added = $item->stones->count();
    //         $stone_type = $item->stones->pluck('stone_type')->implode(',');
    //         $stone_weight = $item->stones->pluck('stone_weight')->implode(',');
    //         $stone_weight_exact = $item->stones->pluck('stone_weight_exact')->implode(',');
    //         $stone_shape = $item->stones->pluck('stone_shape')->implode(',');
    //         $stone_color = $item->stones->pluck('stone_color')->implode(',');
    //         $stones_quantity = $item->stones->pluck('stones_quantity')->implode(',');
    //         $stone_clarity = $item->stones->pluck('stone_clarity')->implode(',');
    //         $stone_certified = $item->stones->pluck('stone_certified')->implode(',');
    //         $certification_number = $item->stones->pluck('certification_number')->implode(',');
    //         $certified_by = $item->stones->pluck('certified_by')->implode(',');
    //         $certification_picture = $item->stones->pluck('certification_picture')->map(function($path) {
    //             return str_replace('\\', '/', $path);
    //         })->implode(',');

    //         $stone_disk = $item->stones->pluck('disk')->implode(',');
    //         $stone_path = $item->stones->pluck('path')->map(function($path) {
    //             return str_replace('\\', '/', $path);
    //         })->implode(',');

    //         $data = [
    //             $index + 1, $item->user_id, $item->item_name, $item->detailed_description, $item->item_weight, $item->weight_unit, $item->item_type, $item->metal_type, $item->metal_color, $item->item_size, $item->total_gem_weight, $item->appraised_value, $item->reserve_price, $item->internalCatalogNumber, $item->startPrice, $item->minEstimate, $item->maxEstimate, $item->postSalePrice, $item->bar_code_image, $item->serial_number, $item->disk, $item->path, $pics, $image_disk, $image_path, $total_stones_added, $stone_type, $stone_weight . " " . ($stone_weight_exact == 1 ? 'exact' : 'approx'), $stone_shape, $stone_color, $stones_quantity, $stone_clarity, $stone_certified == 1 ? "Yes" : "No", $certification_number, $certified_by, $certification_picture, $stone_disk, $stone_path,
    //         ];
    //         $sheet->fromArray($data, null, 'A' . ($index + 2));
    //     }

    //     $writer = new Xlsx($spreadsheet);
    //     $fileName = 'items.xlsx';

    //     return new StreamedResponse(function () use ($writer) {
    //         $writer->save('php://output');
    //     }, 200, [
    //         'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //         'Content-Disposition' => 'attachment;filename="items.xlsx"',
    //         'Cache-Control' => 'max-age=0',
    //     ]);
    // }

    public function exportInLocalFormat()
    {
        $items = LotItems::with('images', 'stones')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $header = [
            '#', 'User Id', 'Item Name', 'Detailed Description', 'Item Weight', 'Weight Unit',
            'Item Type', 'Metal Type', 'Metal Color', 'Item Size', 'Total Gem Weight',
            'Appraised Value', 'Reserve Price', 'Internal Catalog Number', 'Start Price',
            'Min Estimate', 'Max Estimate', 'Post Sale Price', 'Item Tag', 'Serial Number',
            'Disk', 'Path', 'Images', 'Images Disk', 'Images Path', 'Total Stones',
            'Gem Type', 'Gem Weight', 'Gem Shape', 'Gem Color', 'Stones Quantity',
            'Gem Clarity', 'Gem Certified', 'Certification Number', 'Certified by',
            'Certification Picture', 'Stone Disk', 'Stone Path',
        ];
        $sheet->fromArray($header, null, 'A1');

        foreach ($items as $index => $item) {
            $pics = $item->images->pluck('image')->map(function ($path) {
                return str_replace('\\', '/', $path);
            })->implode(',');

            $image_disk = $item->images->pluck('disk')->implode(',');
            $image_path = $item->images->pluck('path')->map(function ($path) {
                return str_replace('\\', '/', $path);
            })->implode(',');

            $bar_code_image = str_replace('\\', '/', $item->bar_code_image);
            $serial_number = str_replace('\\', '/', $item->serial_number);
            $disk = str_replace('\\', '/', $item->disk);
            $path = str_replace('\\', '/', $item->path);

            $total_stones_added = $item->stones->count();
            $stone_type = $item->stones->pluck('stone_type')->implode(',');
            $stone_weight = $item->stones->pluck('stone_weight')->implode(',');
            $stone_weight_exact = $item->stones->pluck('stone_weight_exact')->implode(',');
            $stone_shape = $item->stones->pluck('stone_shape')->implode(',');
            $stone_color = $item->stones->pluck('stone_color')->implode(',');
            $stones_quantity = $item->stones->pluck('stones_quantity')->implode(',');
            $stone_clarity = $item->stones->pluck('stone_clarity')->implode(',');
            $stone_certified = $item->stones->pluck('stone_certified')->implode(',');
            $certification_number = $item->stones->pluck('certification_number')->implode(',');
            $certified_by = $item->stones->pluck('certified_by')->implode(',');
            $certification_picture = $item->stones->pluck('certification_picture')->map(function ($path) {
                return str_replace('\\', '/', $path);
            })->implode(',');

            $stone_disk = $item->stones->pluck('disk')->implode(',');
            $stone_path = $item->stones->pluck('path')->map(function ($path) {
                return str_replace('\\', '/', $path);
            })->implode(',');

            $data = [
                $index + 1, $item->user_id, $item->item_name, $item->detailed_description,
                $item->item_weight, $item->weight_unit, $item->item_type, $item->metal_type,
                $item->metal_color, $item->item_size, $item->total_gem_weight, $item->appraised_value,
                $item->reserve_price, $item->internalCatalogNumber, $item->startPrice, $item->minEstimate,
                $item->maxEstimate, $item->postSalePrice, $bar_code_image, $serial_number,
                $disk, $path, $pics, $image_disk, $image_path, $total_stones_added,
                $stone_type, $stone_weight . " " . ($stone_weight_exact == 1 ? 'exact' : 'approx'),
                $stone_shape, $stone_color, $stones_quantity, $stone_clarity,
                $stone_certified == 1 ? "Yes" : "No", $certification_number, $certified_by,
                $certification_picture, $stone_disk, $stone_path,
            ];
            $sheet->fromArray($data, null, 'A' . ($index + 2));
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'items.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="items.xlsx"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function importFromLocalFormat(Request $request)
    {
        $request->validate([
            'import_items' => 'file|required',
        ]);

        if ($request->delete_old_items == "1") {
            LotItems::truncate();
            LotItemImage::truncate();
            Stones::truncate();
            AuctionItem::truncate();
        }

        $file = $request->file('import_items');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue;
            }

            $lotItem = new LotItems();
            $lotItem->user_id = $row[1];
            $lotItem->item_name = $row[2];
            $lotItem->detailed_description = $row[3];
            $lotItem->item_weight = $row[4];
            $lotItem->weight_unit = $row[5];
            $lotItem->item_type = $row[6];
            $lotItem->metal_type = $row[7];
            $lotItem->metal_color = $row[8];
            $lotItem->item_size = $row[9];
            $lotItem->total_gem_weight = $row[10];
            $lotItem->appraised_value = $row[11];
            $lotItem->reserve_price = $row[12];
            $lotItem->internalCatalogNumber = $row[13];
            $lotItem->startPrice = $row[14];
            $lotItem->minEstimate = $row[15];
            $lotItem->maxEstimate = $row[16];
            $lotItem->postSalePrice = $row[17];
            $lotItem->bar_code_image = $row[18];
            $lotItem->serial_number = $row[19];
            $lotItem->disk = $row[20] ?? null;
            $lotItem->path = $row[21] ?? null;

            $lotItem->save();

            // Import Images
            $images = explode(',', $row[22]);
            $disks = explode(',', $row[23]);
            $paths = explode(',', $row[24]);
            foreach ($images as $key => $image) {
                $itemImage = new LotItemImage();
                $itemImage->lot_items_id = $lotItem->id;
                $itemImage->image = $image;
                $itemImage->disk = $disks[$key] ?? null;
                $itemImage->path = $paths[$key] ?? null;
                $itemImage->save();
            }

            // Import Stones
            $total_stones = (int) $row[25];
            $stone_types = explode(',', $row[26]);
            $stone_weights = explode(',', $row[27]);
            $stone_shapes = explode(',', $row[28]);
            $stone_colors = explode(',', $row[29]);
            $stones_quantities = explode(',', $row[30]);
            $stone_clarities = explode(',', $row[31]);
            $stone_certified = explode(',', $row[32]);
            $certification_numbers = explode(',', $row[33]);
            $certified_by = explode(',', $row[34]);
            $certification_pictures = explode(',', $row[35]);
            $stone_disk = explode(',', $row[36]);
            $stone_path = explode(',', $row[37]);

            for ($i = 0; $i < $total_stones; $i++) {
                $stone = new Stones();
                $stone->item_id = $lotItem->id;
                $stone->stone_type = $stone_types[$i];

                $stone_weight = preg_replace('/[^0-9.]/', '', $stone_weights[$i]);
                $is_approximate = strpos($stone_weights[$i], 'approx') !== false ? 0 : 1;

                $stone->stone_weight = $stone_weight;
                $stone->stone_weight_exact = $is_approximate;

                $stone->stone_shape = $stone_shapes[$i];
                $stone->stone_color = $stone_colors[$i];
                $stone->stones_quantity = $stones_quantities[$i];
                $stone->stone_clarity = $stone_clarities[$i];
                $stone->stone_certified = $stone_certified[$i] === 'Yes' ? 1 : 0;
                $stone->certification_number = $certification_numbers[$i];
                $stone->certified_by = $certified_by[$i];
                $stone->certification_picture = $certification_pictures[$i];
                $stone->disk = $stone_disk[$i] ?? null;
                $stone->path = $stone_path[$i] ?? null;
                $stone->save();
            }
        }

        return back()->with('success', 'Items imported successfully!');
    }
}
