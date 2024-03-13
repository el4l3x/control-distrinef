@isset($competitor->products()->find($product->id)->pivot->precio)
    @php
        $gfc_price = $gfcData->products()->find($product->id)->pivot->precio;
        $product_price = $competitor->products()->find($product->id)->pivot->precio;
        $percent = number_format((((($gfc_price - $product_price)/$gfc_price))*100)*-1, 2);
    @endphp
    {{ number_format($product_price, 2, ",", ".") }} € 
    @if (Str::lower($competitor->nombre) != 'gasfriocalor')
        <span @class([
            'badge',
            'badge-success' => $percent > 0,
            'badge-danger' => $percent < 0,
        ])>
            {{ $percent }}%
        </span>
    @endif    
@endisset