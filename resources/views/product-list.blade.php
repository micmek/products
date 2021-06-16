@extends('templates.main')

@section('content')
<div class="container" id="vueProductList">

    <div id="addProductModal" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>

                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" v-model="form.name">

                        <label class="form-label">Quantity in Stock</label>
                        <input type="text" class="form-control" v-model="form.stock">

                        <label class="form-label">Price per item</label>
                        <input type="text" class="form-control" v-model="form.price">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="saveProduct()">Save</button>
                </div>
            </div>
        </div>
    </div>


    <div class="row py-4">
        <div class="col">
            <h3>@{{ title }}</h3>
        </div>
        <div class="col-lg-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="fas fa-plus"></i>&nbsp; Add Product</button>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity in Stock</th>
                    <th>Price per item</th>
                    <th>Datetime Submitted</th>
                    <th>Total Value Number</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>


                    <tr v-for="product in productList">
                        <td>@{{ product.name }}</td>
                        <td>@{{ product.stock }}</td>
                        <td>@{{ product.price }}</td>
                        <td>@{{ product.date }}</td>
                        <td>@{{ product.total }}</td>
                        <td><button class="btn btn-primary" @click="editProduct(product.id)">Edit</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        new Vue({
            el: '#vueProductList',
            data: {
                title: 'Product List',
                productList:[],
                form: {
                    id:'', name:'', stock:'', price:''
                },
            },
            mounted: function() {
                let _this = this;
                this.reload();
                $('#addProductModal').on('hide.bs.modal', function (event) {
                    _this.clearForm();
                })
            },
            methods: {
                reload: function() {
                    let _this = this;
                    $.get('/product-list-data', function(data) {
                        _this.productList = data;
                    })
                },
                saveProduct: function () {
                    let _this = this;
                    let data = {};
                    data.id = this.form.id;
                    data.name = this.form.name;
                    data.stock = this.form.stock;
                    data.price = this.form.price;
                    $.post('/product-save', data, function(data) {
                        _this.reload();
                        $('#addProductModal').modal('hide')
                    })
                },
                clearForm: function(){
                    this.form.id = '';
                    this.form.name = '';
                    this.form.stock = '';
                    this.form.price = '';
                },
                editProduct: function(id) {
                    let _this = this;
                    $.get('product-get-data/'+id, function(data) {
                        _this.form.id = data.id;
                        _this.form.name = data.name;
                        _this.form.stock = data.stock;
                        _this.form.price = data.price;
                    })
                    $('#addProductModal').modal('show')
                }
            }
        })
    </script>
@endsection
