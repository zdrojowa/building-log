<?php

namespace Selene\Modules\BuildingLogModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\BuildingLogModule\BuildingLog;
use Selene\Modules\BuildingLogModule\BuildingLogPhoto;
use Selene\Modules\BuildingLogModule\Http\Requests\BuildingLogRequest;
use Selene\Modules\DashboardModule\ZdrojowaTable;

class BuildingLogController extends Controller
{

    public function index() {
        return view('BuildingLogModule::index');
    }

    public function ajax(Request $request, BuildingLog $buildingLog) {
        return ZdrojowaTable::response($buildingLog->query(), $request);
    }

    public function create() {
        return view('BuildingLogModule::add', [
            'currentMonth' => date('m'),
            'currentYear' => date('Y')
        ]);
    }

    public function store(BuildingLogRequest $request, BuildingLog $buildingLog) {
        $buildingLog->create($request->all());
        $request->session()->flash('alert-success', 'Pomyślnie dodano nową zakładkę!');

        return redirect()->back();
    }

    public function edit(BuildingLog $buildingLog) {
        return view('BuildingLogModule::edit', [
            'buildingLog' => $buildingLog
        ]);
    }

    public function storeImage(Request $request, BuildingLog $buildingLog, BuildingLogPhoto $buildingLogPhoto) {
        $filePath = $request->file('file')->storePublicly('building-log/'.$buildingLog->_id, ['disk' => 'public']);
        $buildingLogPhoto = new BuildingLogPhoto();
        $buildingLogPhoto->fill([
            '_sequence' => $buildingLog->photos()->max('_sequence') + 1 ?? 1,
            'file' => $filePath
        ]);

        $buildingLog->photos()->save($buildingLogPhoto);

        return response()->json($buildingLogPhoto);
    }

    public function updateSequence(Request $request, BuildingLog $buildingLog) {
        $ids = [];
        parse_str($request->input('sequence'), $ids);

        foreach ($ids['buildingLogPhoto_'] as $index => $id) {
            $buildingLog->photos()->where('_id', $id)->first()->update(['_sequence' => $index + 1]);
        }

        $buildingLog->save();
    }

    public function destroyPhoto(BuildingLog $buildingLog, $buildingLogPhoto)
    {
        $buildingLog->photos()->where('_id', $buildingLogPhoto)->first()->delete();

        return response()->json(['status' => 'success']);
    }

    public function destroy(BuildingLog $buildingLog)
    {
        $buildingLog->delete();

        return response()->json(['status' => 'success']);
    }
}
