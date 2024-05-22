<tr>
  <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
      @if (trim($slot) === 'Laravel')
      <!-- <img src="{{ url('img/colorcorp-logo.png') }}" class="logo" alt="Colorcorp Logo"> -->
      <!-- <img src="{{ asset('img/colorcorp-logo.png') }}" class="logo" alt="Colorcorp Logo"> -->
      <img src="https://www.colorcorp.com.au/wp-content/uploads/colorcorp-logo-small.png" class="logo" alt="Colorcorp Logo">
      @else
      <!-- {{ $slot }} -->
      <!-- <img src="{{ url('img/colorcorp-logo.png') }}" class="logo" alt="Colorcorp Logo"> -->
      <!-- <img src="{{ asset('img/colorcorp-logo.png') }}" class="logo" alt="Colorcorp Logo"> -->
      <img src="https://www.colorcorp.com.au/wp-content/uploads/colorcorp-logo-small.png" class="logo" alt="Colorcorp Logo">
      @endif
    </a>
  </td>
</tr>