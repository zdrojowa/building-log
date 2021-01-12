@inject('buildingLog', 'Selene\Modules\BuildingLogModule\BuildingLog')

@php
    $order = [
        'Grudzień',
        'Listopad',
        'Październik',
        'Wrzesień',
        'Sierpień',
        'Lipiec',
        'Czerwiec',
        'Maj',
        'Kwiecień',
        'Marzec',
        'Luty',
        'Styczeń'
    ];
    $buildingLog = $buildingLog->all()->groupBy('year')->sortKeysDesc()->map(function($item) use ($order) {
       return $item->sort(function($a, $b) use ($order) {
            $pos_a = array_search($a->month, $order);
            $pos_b = array_search($b->month, $order);

            return $pos_a - $pos_b;
        });
    })->flatten();



@endphp

<div class="construction-gallery">
    <h2 class="futura-bold">Dziennik budowy</h2>
    <p class="futura-bold">Baltic park fort</p>
    <div class="construction-gallery__nav">
        <select name="" id="construction-gallery-select">
            @foreach($buildingLog as $buildingTag)
                @if($buildingTag->photos && $buildingTag->photos->count() > 0)
                    <option value="{{$buildingTag->_id}}">
                        {{$buildingTag->month}} {{$buildingTag->year}}
                    </option>
                @endif
            @endforeach
        </select>
        @foreach($buildingLog as $buildingTag)
            @if($buildingTag->photos && $buildingTag->photos->count() > 0)
                <div class="construction-gallery__nav-item" data-id="{{$buildingTag->_id}}">
                    {{$buildingTag->month}} {{$buildingTag->year}}
                </div>
            @endif
        @endforeach
    </div>
    <div class="construction-gallery__photos-container">
        @foreach($buildingLog as $buildingLog)
            @if($buildingLog->photos && $buildingLog->photos->count() > 0)
                <div class="construction-gallery__carousel" data-id="{{$buildingLog->_id}}">
                    @foreach($buildingLog->photos->sortBy('_sequence') as $photo)
                        <a class="construction-gallery__carousel-item" href="{{Storage::disk('public')->url($photo->file)}}" data-lightbox="lightbox-{{$buildingLog->month}}-{{$buildingLog->year}}">
                            <div class="construction-gallery__photo" style="background: url('{{Storage::disk('public')->url($photo->file)}}')"></div>
                        </a>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
</div>
