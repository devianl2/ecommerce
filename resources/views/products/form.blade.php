@include('global.header')

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('global.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                @include('global.top-bar')

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Product Form</h1>

                   
                    <div class="card shadow mb-4">
                        <div class="card-body">
                          @include('global.message')

                          <div class="row">
                            @if (!empty($product))

                                @foreach ($product->images as $image)
                                    <div class="col-md-2">
                                        <img class="img-fluid" src="{{ $image->url }}" alt="{{ $image->title }}">
                                    </div>
                                @endforeach
                                
                            @endif
                          </div>

                          <form action="{{ route('productStore', ['id' => request()->route('id')]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                              <label for="titleInput">Product Title</label>
                              <input type="text" 
                              class="form-control" 
                              name="title" 
                              id="titleInput" 
                              value="{{ old('title') ? old('title') : (!empty($product) ? $product->title : '') }}"
                              placeholder="E.g: Intel Core i3.."
                              required>
                            </div>
                            <div class="form-group">
                              <label for="descriptionInput">Description</label>
                              <textarea name="description" 
                              class="form-control" 
                              id="descriptionInput">{{ old('description') ? old('description') : (!empty($product) ? $product->description : '') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="quantityInput">Quantity</label>
                                <input type="number" 
                                class="form-control" 
                                name="quantity" 
                                id="quantityInput" 
                                value="{{ old('quantity') ? old('quantity') : (!empty($product) ? $product->quantity : 1) }}"
                                required>
                            </div>
                            <div class="form-group">
                                <label for="priceInput">Price (RM)</label>
                                <input type="number" 
                                step="0.01"
                                class="form-control" 
                                name="price" 
                                id="priceInput" 
                                value="{{ old('price') ? old('price') : (!empty($product) ? $product->price : 0) }}"
                                required>
                            </div>

                            <div class="form-group">
                                <label for="productPictures">Product Pictures</label>
                                <input type="file" 
                                accept="image/*"
                                class="form-control-file" 
                                name="images[]" 
                                multiple
                                id="productPictures">
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                          </form>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            @include('global.footer')

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    @include('global.footer-js')

</body>

</html>