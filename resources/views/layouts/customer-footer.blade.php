@php
  $vendata = Helper::getShopData(Auth::user()->vendor_id);
  $shopslug = $vendata['shop_url_slug'];
@endphp
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="get" action="{{ route('search.results', ['vendor_name' => $shopslug ])}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="search_type" id="search_type" value="" />
        
        <div class="modal-body">
          <h3>{{ __('Search by ') }}<span></span></h3>
          <div class="cc-field">
            <label>{{ __('Enter text') }}</label>
            <input type="text" name="search_key" id="search_key" placeholder="Search" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn comun_btn">{{ __('Search') }}</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closeSearchModal()">{{ __('Close') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/owl.carousel2.thumbs@0.1.8/dist/owl.carousel2.thumbs.min.js"></script>
<script src="{{ url('js/main.js') }}"></script>
<script src="{{ url('js/masonry.pkgd.min.js') }}"></script>
<script src="{{ url('js/jm-animation.js') }}"></script>