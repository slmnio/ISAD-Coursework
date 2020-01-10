<div class="item border rounded px-3 py-2 d-flex Basketable" data-id="{{ $item->id }}" style="cursor:pointer">
    <div class="item-image border rounded" style="width: 100px; height: 100px; background:url({{ $item->getImage() }}) center no-repeat;"></div>
    <div class="item-right ml-3 flex-grow-1 d-flex flex-column">
        <div class="item-title h5">{{ $item->name }}</div>
        <div class="item-desc text-secondary">{{ $item->description }}<span class="item-icon float-right"><i class="fas fa-plus"></i></span></div>
        <div class="item-price text-primary text-right flex-grow-1 d-flex justify-content-end align-items-end"><span class="item-price-right">{{ $item->getformattedPrice() }}</span></div>
    </div>
</div>
