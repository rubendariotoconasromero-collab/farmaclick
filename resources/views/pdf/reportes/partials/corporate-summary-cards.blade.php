@if(!empty($items))
<table class="fc-summary" role="presentation">
    <tr>
        @foreach($items as $item)
        <td>
            <span class="fc-summary__label">{{ $item['label'] }}</span>
            <span class="fc-summary__value">{{ $item['value'] }}</span>
        </td>
        @endforeach
    </tr>
</table>
@endif
