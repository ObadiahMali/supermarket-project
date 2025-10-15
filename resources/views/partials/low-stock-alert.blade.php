@if($lowStockProducts->count())
<div class="alert alert-warning border border-warning-subtle shadow-sm p-4">
    <h5 class="mb-3 text-warning">
        <i class="fas fa-exclamation-triangle me-2"></i> Low Stock Alerts
    </h5>
    <ul class="list-group">
        @foreach ($lowStockProducts as $product)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $product->name }}</strong>
                    <span class="text-danger ms-2">Only {{ $product->stock }} left</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-warning ms-2" data-bs-toggle="modal" data-bs-target="#restockModal-{{ $product->id }}">
                    <i class="fas fa-sync-alt"></i> Restock
                </button>
            </li>
        @endforeach
    </ul>
</div>

@foreach ($lowStockProducts as $product)
<div class="modal fade" id="restockModal-{{ $product->id }}" tabindex="-1" aria-labelledby="restockModalLabel-{{ $product->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('products.restock', $product->id) }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="restockModalLabel-{{ $product->id }}">
                    Restock: {{ $product->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="quantity" class="form-label">Quantity to Add</label>
                <input type="number" name="quantity" class="form-control" min="1" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-warning">Confirm Restock</button>
            </div>
        </div>
    </form>
  </div>
</div>
@endforeach
@endif