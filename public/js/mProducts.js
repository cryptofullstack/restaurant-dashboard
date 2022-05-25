var ManageProduct = function() {
  var productManage = function() {

      var productDatatable = $('#m_products_datatable');

      productDatatable.mDatatable({
        // datasource definition
        data: {
          type: 'remote',
          source: {
  			read: {
  				url: '/admin/product/getAllDatas',
                  method: 'GET',
  			},
  		},
          pageSize: 5,
        },

        // column sorting
        sortable: true,

        pagination: true,

        toolbar: {
          // toolbar items
          items: {
            // pagination
            pagination: {
              // page size select
              pageSizeSelect: [5, 10, 20, 30, 50, 100],
            },
          },
        },

        // columns definition
        columns: [
           {
            field: 'image',
            title: 'Image',
            width: 100,
            template: function(row, index, datatable) {
                return '<img style="width: 100%;" src="/uploads/products/'+row.pro_image+'" />'
            }
          }, {
            field: 'pro_name',
            title: 'Title',
            width: 150,
          }, {
            field: 'pro_description',
            title: 'Description',
            width: 400
          }, {
            field: 'pro_price',
            title: 'Price',
            width: 100,
            template: function(row, index, datatable) {
                return row.pro_price+" €";
            }
          }, {
              field: "Actions",
              width: 120,
              title: "Actions",
              sortable: false,
              overflow: 'visible',
              template: function (row, index, datatable) {
                  var dropup = (datatable.getPageSize() - index) <= 4 ? 'dropup' : '';

                  return '\
                      <a href="javascript:;" data-product_id="'+row.id+'" class="product_delete_btn m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete ">\
                      <i class="la la-trash"></i>\
                      </a>\
                      <a href="javascript:;" data-product_id="'+row.id+'" class="product_edit_btn m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit ">\
                      <i class="la la-edit"></i>\
                      </a>\
                      <a href="/admin/product/groups/'+row.id+'" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Add Extra ">\
                      <i class="la la-plus"></i>\
                      </a>\
                  ';
              }
          }],
      });

      var productAddForm = $('#new_product_add_form');

      var productAddFormValid = productAddForm.validate({
          rules: {
              pro_name: {
                  required: true
              },
              pro_price: {
                  required: true
              },
              pro_description: {
                  required: true,
              },
              slim: {
                  required: true,
              }
          },
          messages: {},
          errorPlacement: function(error, element) {
          }
      });

      productAddForm.on('submit', function(e) {
          e.preventDefault();

          var form = $(this);

          if (!productAddFormValid.form()) {
              swal({
                  title: "",
                  text: "Please add correct data.",
                  type: "error",
                  confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
              });

              return false;
          };

          $.ajaxSetup({
              headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
              }
          });

          var url = form.attr('action');

          var formData = new FormData(form[0]);
          var submit_btn = form.find('.form-submit-btn');
          submit_btn.addClass('m-loader m-loader--right m-loader--accent').attr('disabled', true);

          $.ajax({
              url: url,
              type: 'POST',
              data: formData,
              success: function(response) {
                  submit_btn.removeClass('m-loader m-loader--right m-loader--accent').attr('disabled', false);
                  console.log(response)
                  if (response.result === "success") {
                      $('#new_product_add_modal').modal('hide');
                      productDatatable.reload();
                  } else {
                      swal({
                          title: "Error",
                          text: response.msg,
                          type: "error",
                          confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                      });
                  }
              },
              processData: false,
              contentType: false,
              error: function(error) {
                  console.log(error);
              }
          });
      })

      $(document).on('click', '.product_edit_btn', function() {
          var productId = $(this).data('product_id');

          $.ajax({
              url: '/admin/product/getProductData/'+productId,
              type: 'get',
              success: function(response){
                  if (response.result == "success") {
                      var editForm = $('#exist_product_edit_form');
                      editForm.find('#edit_product_id').val(response.product.id);
                      editForm.find('#_pro_name').val(response.product.pro_name);
                      editForm.find('#_pro_price').val(response.product.pro_price);
                      editForm.find('#_pro_description').val(response.product.pro_description);
                      $('#edit_product_image_slim>img').attr('src', response.product.image_url);
                      productEditImgSlim = new Slim(document.getElementById('edit_product_image_slim'), {
                          ratio: '1:1',
                          minSize: {
                              width: 100,
                              height: 100
                          },
                          download: false,
                          label: 'Drop your image here or Click',
                          statusImageTooSmall: 'Image too small. Min Size is $0 pixel. Try again.'
                      });

                      productEditImgSlim.size = {
                          width: 300,
                          height: 300
                      };

                      $('#exist_product_edit_modal').modal('show');
                  } else if (response.result == "error") {
                      swal({
                          "title": "Faild",
                          "text": "something went wrong !.",
                          "type": "error",
                          "confirmButtonClass": "btn m-btn--air m-btn btn-outline-accent m-btn--wide"
                      });
                  }
              },
              error: function(error){
                  console.log(error);
              }
          });
      });

      var productEditForm = $('#exist_product_edit_form');

      var productEditFormValid = productEditForm.validate({
          rules: {
              _pro_name: {
                  required: true
              },
              _pro_price: {
                  required: true
              },
              _pro_description: {
                  required: true,
              },
          },
          messages: {},
          errorPlacement: function(error, element) {
          }
      });

      productEditForm.on('submit', function(e) {
          e.preventDefault();

          var form = $(this);

          if (!productEditFormValid.form()) {
              swal({
                  title: "",
                  text: "Please add correct data.",
                  type: "error",
                  confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
              });

              return false;
          };

          $.ajaxSetup({
              headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
              }
          });

          var url = form.attr('action');

          var formData = new FormData(form[0]);
          var submit_btn = form.find('.form-submit-btn');
          submit_btn.addClass('m-loader m-loader--right m-loader--accent').attr('disabled', true);

          $.ajax({
              url: url,
              type: 'POST',
              data: formData,
              success: function(response) {
                  submit_btn.removeClass('m-loader m-loader--right m-loader--accent').attr('disabled', false);
                  console.log(response)
                  if (response.result === "success") {
                      $('#exist_product_edit_modal').modal('hide');
                      productDatatable.reload();
                  } else {
                      swal({
                          title: "Error",
                          text: response.msg,
                          type: "error",
                          confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                      });
                  }
              },
              processData: false,
              contentType: false,
              error: function(error) {
                  console.log(error);
              }
          });
      });

      $(document).on('click', '.product_delete_btn', function(){
          var $this = $(this);

          swal({
              title: 'Are you sure?',
              text: "Delete Product !",
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: ' Yes !',
              confirmButtonClass: "btn m-btn--air btn-outline-danger m-btn m-btn--wide",
              cancelButtonClass: "btn m-btn--air btn-outline-primary m-btn m-btn--wide",
          }).then(function(result) {
              if (result.value) {
                  var productId = $this.data('product_id');
                  $.ajax({
                      url: '/admin/product/destroy/'+productId,
                      type: 'get',
                      success: function(response){
                          if (response.result == "success") {
                              swal({
                                  "title": "Success",
                                  "text": "Product Deleted !.",
                                  "type": "success",
                                  "confirmButtonClass": "btn m-btn--air m-btn btn-outline-accent m-btn--wide"
                              });
                              productDatatable.reload();
                          } else if (response.result == "error") {
                              swal({
                                  "title": "Faild",
                                  "text": response.msg,
                                  "type": "error",
                                  "confirmButtonClass": "btn m-btn--air m-btn btn-outline-accent m-btn--wide"
                              });
                          }
                      },
                      error: function(error){
                          console.log(error);
                      }
                  });
              }
          });
      });
  };

  var slimInit = function() {
      productImgSlim = new Slim(document.getElementById('product_image_slim'), {
          ratio: '1:1',
          minSize: {
              width: 100,
              height: 100
          },
          download: false,
          label: 'Drop your image here or Click',
          statusImageTooSmall: 'Image too small. Min Size is $0 pixel. Try again.'
      });

      productImgSlim.size = {
          width: 300,
          height: 300
      };
  }

  var slimDestory = function() {
      productImgSlim.destroy();
  }

  return {
    // public functions
    init: function() {
        var productImgSlim,
        productEditImgSlim;
        productManage();
        slimInit();
    },
  };
}();

jQuery(document).ready(function() {
  ManageProduct.init();
});
