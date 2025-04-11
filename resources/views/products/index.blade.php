<x-app-layout>
    <div class="py-12">

        <!-- Create Blog Button -->
        <div class=" max-w-7xl mx-auto flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">📜 Product</h2>
            <button id="createProductBtn" class="btn-create px-4 py-2 bg-blue-600 text-white text-sm border border-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
                ➕ Create New Product
            </button>
        </div>

        <!-- Blog Table -->
        <div class="max-w-7xl mx-auto shadow-lg border border-white rounded-lg overflow-hidden">
            <table class="w-full text-white">
                <thead class="uppercase text-sm">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Price</th>
                        <th class="px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody id="productTableBody" class="divide-y divide-white">

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            <button class="px-4 py-2 bg-white text-gray-700 rounded-lg">Previous</button>
            <button class="px-4 py-2 bg-white text-gray-700 rounded-lg ml-2">Next</button>
        </div>
    </div>

    @include('products.modal')
    @include('products.show')
</x-app-layout>

@vite(['resources/js/products.js'])