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
                    <h1 class="h3 mb-4 text-gray-800">Product List</h1>

                    <div class="row mb-2">
                        <div class="col-lg-12">
                          <div class="float-right">
                            <a href="{{ route('productForm') }}"><button class="btn btn-primary">New Product</button></a>
                          </div>
                        </div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <form class="form-inline" action="{{ route('productList') }}" method="GET">
                                <label class="sr-only" for="inlineFormInputName2">Product Title</label>
                                <input type="text" 
                                name="search" 
                                class="form-control mb-2 mr-sm-2" 
                                id="inlineFormInputName2" 
                                placeholder="Product Keyword"
                                value="{{ request('search') }}">
                              
                                <button type="submit" class="btn btn-primary mb-2">Submit</button>
                              </form>
                        </div>
                        <div class="card-body">
                          @include('global.message')
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                      <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Price(RM)</th>
                                        <th scope="col">Action</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @foreach ($products as $product)
                                        <tr>
                                          <th scope="row">{{ $product->id }}</th>
                                          <td><div class="kalim"><img src="{{ $product->images[0]->url }}"></div></td>
                                          <td>{{ $product->title }}</td>
                                          <td>{{ $product->quantity }}</td>
                                          <td>{{ $product->price }}</td>
                                          <td>
                                            <a href="{{ route('productForm', ['id' => $product->id]) }}"><button type="button" class="btn btn-primary"><i class="far fa-edit"></i></button></a>
                                            <form action="{{ route('productDestroy', ['id' => $product->id]) }}" method="POST">
                                              @csrf
                                              @method('delete')
                                              <button 
                                              type="submit" 
                                              class="btn btn-danger"
                                              onclick="return confirm('Are you sure you want to delete this item?');"
                                              ><i class="far fa-trash-alt"></i></button> 
                                            </form>
                                          </td>
                                        </tr>
                                      @endforeach
                                      
                                    </tbody>
                                  </table>
                            </div>
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