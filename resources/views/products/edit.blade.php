<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Page Heading -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Create New Blog</h2>
            <a href="#"
               class="px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-gray-700 transition">
                ← Back to Products
            </a>
        </div>

        <!-- Form Container -->
        <div class="bg-white shadow-lg rounded-lg p-6 max-w-2xl mx-auto">
            <form action="{{route('products.update', $product->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Thông báo lỗi -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Có lỗi xảy ra!</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Name Field -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Title:</label>
                    <input type="text" id="name" name="name" value="{{$product->name}}"
                           class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200"
                           placeholder="Enter name">
                </div>

                <!-- price Field -->
                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700">Description:</label>
                    <input id="price" name="price" type="number" step="0.01" min="0" value="{{$product->price}}"
                           class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200"
                           placeholder="Enter price" >
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold shadow-md hover:bg-blue-700 transition">
                        🚀 Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
