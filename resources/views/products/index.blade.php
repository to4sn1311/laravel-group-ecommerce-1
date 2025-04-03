<x-app-layout>
    <div class="py-12">

        <!-- Create Blog Button -->
        <div class=" max-w-7xl mx-auto flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">📜 Product</h2>
            <a href="{{route('products.create')}}" class="px-4 py-2 bg-blue-600 text-sm font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
                ➕ Create New Product
            </a>
        </div>

        <!-- Blog Table -->
        <div class="max-w-7xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="w-full border-collapse">
                <thead class="bg-gray-200 text-gray-700 uppercase text-sm">
                <tr>
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Price</th>
                    <th class="px-4 py-3 text-left">Action</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @foreach($products as $product)
                    <tr class="hover:bg-gray-100 transition">
                        <td class="px-4 py-3">{{$product->id}}</td>
                        <td class="px-4 py-3 font-semibold text-gray-900">{{$product->name}}</td>
                        <td class="px-4 py-3 text-gray-700">{{$product->price}}</td>
                        <td class="px-4 py-3 flex space-x-2">
                            <a href="{{route('products.show', $product->id)}}" class="px-3 py-1 bg-blue-500  text-xs font-semibold rounded shadow-md hover:bg-blue-600 transition">
                                👁 View
                            </a>
                            <a href="{{route('products.edit', $product->id)}}" class="px-3 py-1 bg-yellow-500 text-xs font-semibold rounded shadow-md hover:bg-yellow-600 transition">
                                ✏️ Edit
                            </a>
                            <button onclick="return confirm('Are you sure you want to delete this blog?')"
                                    class="px-3 py-1 bg-red-500 text-xs font-semibold rounded shadow-md hover:bg-red-600 transition">
                                🗑 Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg">Previous</button>
            <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg ml-2">Next</button>
        </div>
    </div>
</x-app-layout>
