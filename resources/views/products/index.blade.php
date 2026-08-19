<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Etalio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Product Management</h1>

        <!-- Alert Message -->
        <div id="alert" class="hidden mb-4 p-4 rounded-lg"></div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 id="formTitle" class="text-xl font-semibold text-gray-700 mb-4">Add New Product</h2>
            <form id="productForm" class="space-y-4">
                <input type="hidden" id="productId" value="">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                    <input type="text" id="name" name="name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    <p id="nameError" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (Rp)</label>
                        <input type="number" id="price" name="price" min="0" step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        <p id="priceError" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                        <input type="number" id="stock" name="stock" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        <p id="stockError" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit" id="submitBtn"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                        Save Product
                    </button>
                    <button type="button" id="cancelBtn" onclick="resetForm()"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-lg transition hidden">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-700">Product List</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productTable" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const API_URL = '/api/products';
        let products = [];

        // Format price to Rupiah
        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(price);
        }

        // Show alert message
        function showAlert(message, type = 'success') {
            const alert = document.getElementById('alert');
            alert.textContent = message;
            alert.className = `mb-4 p-4 rounded-lg ${type === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200'}`;
            alert.classList.remove('hidden');
            setTimeout(() => alert.classList.add('hidden'), 3000);
        }

        // Clear validation errors
        function clearErrors() {
            ['name', 'price', 'stock'].forEach(field => {
                document.getElementById(`${field}Error`).classList.add('hidden');
                document.getElementById(field).classList.remove('border-red-500');
            });
        }

        // Show validation errors
        function showErrors(errors) {
            Object.keys(errors).forEach(field => {
                const errorEl = document.getElementById(`${field}Error`);
                const inputEl = document.getElementById(field);
                if (errorEl && inputEl) {
                    errorEl.textContent = errors[field][0];
                    errorEl.classList.remove('hidden');
                    inputEl.classList.add('border-red-500');
                }
            });
        }

        // Reset form
        function resetForm() {
            document.getElementById('productForm').reset();
            document.getElementById('productId').value = '';
            document.getElementById('formTitle').textContent = 'Add New Product';
            document.getElementById('cancelBtn').classList.add('hidden');
            clearErrors();
        }

        // Load all products
        async function loadProducts() {
            try {
                const response = await fetch(API_URL);
                const result = await response.json();
                products = result.data;
                renderTable();
            } catch (error) {
                showAlert('Failed to load products', 'error');
            }
        }

        // Render products table
        function renderTable() {
            const tbody = document.getElementById('productTable');
            if (products.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No products found</td>
                    </tr>
                `;
                return;
            }
            tbody.innerHTML = products.map(product => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${product.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${product.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatPrice(product.price)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${product.stock}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="editProduct(${product.id})" class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                        <button onclick="deleteProduct(${product.id})" class="text-red-600 hover:text-red-800">Delete</button>
                    </td>
                </tr>
            `).join('');
        }

        // Form submit handler
        document.getElementById('productForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            clearErrors();

            const id = document.getElementById('productId').value;
            const data = {
                name: document.getElementById('name').value,
                price: parseFloat(document.getElementById('price').value),
                stock: parseInt(document.getElementById('stock').value)
            };

            try {
                const url = id ? `${API_URL}/${id}` : API_URL;
                const method = id ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    showAlert(result.message);
                    resetForm();
                    loadProducts();
                } else if (response.status === 422) {
                    showErrors(result.errors);
                } else {
                    showAlert(result.message || 'Something went wrong', 'error');
                }
            } catch (error) {
                showAlert('Network error. Please try again.', 'error');
            }
        });

        // Edit product
        async function editProduct(id) {
            try {
                const response = await fetch(`${API_URL}/${id}`);
                const result = await response.json();

                if (response.ok) {
                    document.getElementById('productId').value = result.data.id;
                    document.getElementById('name').value = result.data.name;
                    document.getElementById('price').value = result.data.price;
                    document.getElementById('stock').value = result.data.stock;
                    document.getElementById('formTitle').textContent = 'Edit Product';
                    document.getElementById('cancelBtn').classList.remove('hidden');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            } catch (error) {
                showAlert('Failed to load product', 'error');
            }
        }

        // Delete product
        async function deleteProduct(id) {
            if (!confirm('Are you sure you want to delete this product?')) return;

            try {
                const response = await fetch(`${API_URL}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    showAlert(result.message);
                    loadProducts();
                } else {
                    showAlert(result.message || 'Failed to delete product', 'error');
                }
            } catch (error) {
                showAlert('Network error. Please try again.', 'error');
            }
        }

        // Load products on page load
        loadProducts();
    </script>
</body>
</html>
