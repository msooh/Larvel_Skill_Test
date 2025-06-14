<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Entry</title>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">

<div class="container">
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Product Entry Form</h4>            
        </div>
        <div class="card-body">
            <form id="productForm">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Product name" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quantity in Stock</label>
                        <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Price per Item</label>
                        <input type="number" name="price" step="0.01" class="form-control" placeholder="Price" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Submitted Products</h5>
            <a href="#" class="btn btn-light btn-sm">Export to CSV</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0" id="productTable">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Submitted At</th>
                        <th>Total Value</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->created_at }}</td>
                            <td>{{ number_format($product->quantity * $product->price, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="editProduct({{ $product->id }}, '{{ $product->name }}', {{ $product->quantity }}, {{ $product->price }})">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="fw-bold table-primary">
                        <td colspan="4">Grand Total</td>
                        <td>{{ number_format($totalValue, 2) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editProductForm">
            @csrf
            <input type="hidden" id="editId">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" id="editName" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" id="editQuantity" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" id="editPrice" class="form-control" required>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="submitEdit()">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $('#productForm').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('products.store') }}",
            method: 'POST',
            data: formData,
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert("Failed to submit. Please try again.");
            }
        });
    });

    function editProduct(id, name, quantity, price) {
        $('#editId').val(id);
        $('#editName').val(name);
        $('#editQuantity').val(quantity);
        $('#editPrice').val(price);
        var modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    }

    function submitEdit() {
        let id = $('#editId').val();
        let name = $('#editName').val();
        let quantity = $('#editQuantity').val();
        let price = $('#editPrice').val();

        $.ajax({
            url: `/products/${id}`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: name,
                quantity: quantity,
                price: price
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert("Update failed. Try again.");
            }
        });
    }
</script>

</body>
</html>
