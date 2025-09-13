<div class="row g-6 mb-6">
  @foreach($cards as $card)
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center">
              <div class="avatar">
                <div class="avatar-initial {{ $card['bg'] ?? 'bg-primary' }} rounded">
                  <div class="ti {{ $card['icon'] ?? 'ti-users' }} ti-md"></div>
                </div>
              </div>
              <div class="ms-3">
                <div class="small mb-1">{{ $card['title'] }}</div>
                <h5 class="mb-0">
                  {{ $card['value'] }} 
                  @if(!empty($card['status']))
                    <span class="text-success">{{ $card['status'] }}</span>
                  @endif
                </h5>
                @if(!empty($card['subtitle']))
                  <div class="small text-muted">{{ $card['subtitle'] }}</div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>
