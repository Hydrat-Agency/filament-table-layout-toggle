@if (isset($persistEnabled) and $persistEnabled)
<div x-data="{
  layoutView: $persist(@entangle('layoutView').live).as('_x_{{ $persistKey }}'),
}"></div>
@endif
