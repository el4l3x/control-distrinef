{{ number_format($competidor_price, 2, ",", ".") }} € 
<span @class([
    'badge',
    'badge-success' => $competidor_percent > 0,
    'badge-danger' => $competidor_percent < 0,
])>
    {{ $competidor_percent }}%
</span>